<?php

namespace App\Services\EntityMs;

use App\Models\Delivery;
use App\Models\Option;
use App\Models\Order;
use App\Models\Product;
use App\Services\Api\MoySkladService;
use App\Services\EntityMs\CounterpartyMsService;
use Illuminate\Support\Facades\Log;

class OrderMsService
{

    private $moySkladService;
    private $counterpartyMsService;

    public function __construct(MoySkladService $moySkladService, CounterpartyMsService $counterpartyMsService)
    {
        $this->moySkladService = $moySkladService;
        $this->counterpartyMsService = $counterpartyMsService;
    }

    function updateOrderMs($msOrder)
    {
        $url = 'https://api.moysklad.ru/api/remap/1.2/entity/customerorder';
        $urlProduct = Option::where('code', '=', "ms_product_for_order_url")->first()?->value;
        $customentity = Option::where('code', '=', "ms_customentity_url")->first()?->value;

        $urlCounterparty = Option::where('code', '=', "ms_counterparty_url")->first()?->value;
        $urlDelivery = Option::where('code', '=', "ms_delivery_url")->first()?->value;
        $urlVehicleCategory = Option::where('code', '=', "ms_vehicle_type_url")->first()?->value;
        $urlService = Option::where('code', '=', "ms_service_url")->first()?->value;

        $urlState = Option::where('code', '=', "ms_orders_need_status_url")->first()?->value;
        $urlAttr = Option::where('code', '=', 'ms_attributes_order_date_url')->first()?->value;

        $guidAttrDelivery = Option::where('code', '=', 'ms_order_delivery_guid')->first()?->value;
        $guidAttrDeliveryPrice = Option::where('code', '=', 'ms_order_delivery_price_guid')->first()?->value;
        $guidAttrTransportType = Option::where('code', '=', 'ms_order_transport_type_guid')->first()?->value;

        $urlOrganization = Option::where('code', '=', "ms_organization_url")->first()?->value;
        $guidOrganization = Option::where('code', '=', 'ms_organization_guid')->first()?->value;

        $vat = Option::where('code', '=', "ms_vat")->first()?->value;
        $service_delivery_block = Option::where('code', '=', "ms_service_delivery_block_id")->first()?->value;
        $service_delivery_beton = Option::where('code', '=', "ms_service_delivery_beton_id")->first()?->value;

        $array = [];

        if (isset($msOrder["positions"])) {
            $array["positions"] = [];
            $quantity = 1;

            foreach ($msOrder["positions"] as $position) {
                if ($position["quantity"] != 0) {
                    $array["positions"][] = [
                        "quantity" => (float)$position["quantity"],
                        "price" => (float)$position["price"] * 100,
                        'vat' => (int)$vat,
                        "assortment" => [
                            "meta" => [
                                "href" => $urlProduct . $position["product_id"],
                                "type" => "product",
                                "mediaType" => "application/json"
                            ]
                        ]
                    ];
                    $quantity = (float)$position["quantity"];
                }
            }

            if (count($array["positions"]) == 0)
                throw new \Exception(trans("error.noPositions"));

            $delivery_id = $service_delivery_block;


            if ($msOrder["form"] == "calcBeton") {
                $delivery_id = $service_delivery_beton;
                if ($quantity <= 8) {
                    $quantity = 8;
                // } else if ($quantity > 8 && $quantity !== 0) {
                //     $quantity = round($quantity/8) * 8;
                }
            } else {
                $quantity = 1;
            }
            $price = 0;


            if (\Arr::exists($msOrder, "attributes") && \Arr::exists($msOrder["attributes"], "deliveryPrice")) {
                $price = (float)$msOrder["attributes"]["deliveryPrice"] * 100;
            }

            if (\Arr::exists($msOrder, "attributes") && \Arr::exists($msOrder["attributes"], "delivery") &&  \Arr::exists($msOrder["attributes"]["delivery"], "id")) {

                if ($msOrder["attributes"]["delivery"]["id"]!='28803b00-5c8f-11ea-0a80-02ed000b1ce1')
                    $array["positions"][] = [
                        "quantity" => (float)$quantity,
                        "price" => (float)$price,
                        'vat' => (int)$vat,
                        "assortment" => [
                            "meta" => [
                                "href" => $urlService . $delivery_id,
                                "type" => "service",
                                "mediaType" => "application/json"
                            ]
                        ]
                    ];
            }

            if (\Arr::exists($msOrder, "services")){
                foreach($msOrder["services"] as $key=>$position){

                    $array["positions"][] = [
                        "quantity" => (float)$position["quantity"],
                        "price" => (float)$position["price"] * 100,
                        'vat' => (int)$vat,
                        "assortment" => [
                            "meta" => [
                                "href" => $urlService . $position["product_id"],
                                "type" => "service",
                                "mediaType" => "application/json"
                            ]
                        ]
                    ];
                }
            }
        }

        if (isset($msOrder["description"])) {
            $array["description"] = $msOrder["description"];
        }


        if (isset($msOrder["shipmentAddressFull"]) && isset($msOrder["shipmentAddressFull"]["comment"])) {
            $array["shipmentAddressFull"]["comment"] = $msOrder["shipmentAddressFull"]["comment"];
        }

        if (isset($msOrder["shipmentAddressFull"]) && isset($msOrder["shipmentAddressFull"]["addInfo"])) {
            $array["shipmentAddressFull"]["addInfo"] = $msOrder["shipmentAddressFull"]["addInfo"];
        }

        if (isset($msOrder["deliveryPlannedMoment"])) {
            $array["deliveryPlannedMoment"] = $msOrder["deliveryPlannedMoment"];
        }

        if (!isset($msOrder["id"]) || $msOrder["id"] == null) {
            $array["organization"] = [
                "meta" => [
                    "href" => $urlOrganization . $guidOrganization,
                    "type" => "organization",
                    "mediaType" => "application/json"
                ]
            ];
        }

        if (isset($msOrder["agent"])) {
            if (is_array($msOrder["agent"])) {
                if (isset($msOrder["agent"]["id"]) && $msOrder["agent"]["id"] != null) {
                    $msOrder["agent"] = $msOrder["agent"]["id"];
                } else {
                    $agent = $this->counterpartyMsService->updateCounterpartyMs($msOrder["agent"]);
                    $msOrder["agent"] = $agent->id;
                }
            }

            $array["agent"] = [
                "meta" => [
                    "href" => $urlCounterparty . $msOrder["agent"],
                    "type" => "counterparty",
                    "mediaType" => "application/json"
                ]
            ];
        }


        if (isset($msOrder["state"]) && $msOrder["state"] != null) {
            $array["state"]  = [
                'meta' => [
                    'href' => $urlState . $msOrder["state"],
                    'type' => "state",
                    "mediaType" => "application/json"
                ]
            ];
        }

        if (\Arr::exists($msOrder, "attributes")) {
            if (\Arr::exists($msOrder["attributes"], "deliveryPrice"))
                $array["attributes"][] = [
                    'meta' => [
                        'href' => $urlAttr . $guidAttrDeliveryPrice,
                        'type' => "attributemetadata",
                        "mediaType" => "application/json"
                    ],
                    'id' => $guidAttrDeliveryPrice,
                    'name' => 'Цена доставки',
                    'type' => 'long',
                    'value' => (int)$msOrder["attributes"]["deliveryPrice"]
                ];

            if (\Arr::exists($msOrder["attributes"], "delivery"))
                $array["attributes"][] = [
                    'meta' => [
                        'href' => $urlAttr . $guidAttrDelivery,
                        'type' => "attributemetadata",
                        "mediaType" => "application/json"
                    ],
                    'value' => [
                        'meta' => [
                            'href' => $urlDelivery . $msOrder["attributes"]["delivery"]["id"],
                            'type' => "customentity",
                            "mediaType" => "application/json",
                        ],
                        //   "name" => $msOrder["attributes"]["delivery"]["name"]
                    ],
                    "id" => $guidAttrDelivery,
                    "name" => "Доставка",
                    "type" => "customentity"
                ];


            if (\Arr::exists($msOrder["attributes"], "vehicle_type"))
                $array["attributes"][] = [
                    'meta' => [
                        'href' => $urlAttr . $guidAttrTransportType,
                        'type' => "attributemetadata",
                        "mediaType" => "application/json"
                    ],
                    'value' => [
                        'meta' => [
                            'href' => $urlVehicleCategory . $msOrder["attributes"]["vehicle_type"]["id"],
                            'type' => "customentity",
                            "mediaType" => "application/json",
                        ],
                        //         "name" => $msOrder["attributes"]["vehicle_type"]["name"]
                    ],
                    "id" => $guidAttrTransportType,
                    "name" => "Тип доставки",
                    "type" => "customentity"
                ];
        }

        // $array["attributes"][] = [
        //     "meta" => [
        //         "href" => "https://api.moysklad.ru/api/remap/1.2/entity/customerorder/metadata/attributes/6e6203f8-5a08-11ef-0a80-037e00063255",
        //         "type" => "attributemetadata",
        //         "mediaType" => "application/json"
        //     ],
        //     "id" => "6e6203f8-5a08-11ef-0a80-037e00063255",
        //     "name" => "Заказ",
        //     "type" => "customentity",
        //     "value" => [
        //         "meta" => [
        //             "href" => "https://api.moysklad.ru/api/remap/1.2/entity/customentity/4573aa45-5a08-11ef-0a80-19040006123f/58c20898-5a08-11ef-0a80-184800060a0a",
        //             "type" => "customentity",
        //             "mediaType" => "application/json",
        //         ],
        //         "name" => "Первый раз"
        //     ]
        // ];

        $array['organization'] = [
            "meta" => [
                "href" => "https://api.moysklad.ru/api/remap/1.2/entity/organization/03957745-4672-11ee-0a80-0dbe00139b20",
                "type" => "organization",
                "mediaType" => "application/json",
            ]
        ];

        if (!isset($msOrder["id"]) || $msOrder["id"] == null) {
            return $this->moySkladService->actionPostRowsFromJson($url, $array);
        } else {
            return $this->moySkladService->actionPutRowsFromJson($url . $msOrder["guid"], $array);
        }
    }

    function createOrderToMs($msOrder)
    {
       $order = Order::with('contact', 'delivery','status','positions')->find($msOrder);
       $vat = Option::where('code', '=', "ms_vat")->first()?->value;
       $url = "https://api.moysklad.ru/api/remap/1.2/entity/customerorder/";
       $urlCounterparty = Option::where('code', '=',"ms_counterparty_url")->first()?->value;

        $array = [];

        $array['organization'] = [
            "meta" => [
                "href" => "https://api.moysklad.ru/api/remap/1.2/entity/organization/03957745-4672-11ee-0a80-0dbe00139b20",
                "type" => "organization",
                "mediaType" => "application/json",
            ]
        ];

        $msId = $order->contact->ms_id;

        if ($msId == null){
            $arContact = ["name"=>$order->contact->name, "phone"=>$order->contact->phone];
            $agent = $this->moySkladService->actionPostRowsFromJson($urlCounterparty, $arContact);

            $msId = $agent->id;

            $order->contact->ms_id = $msId;
            $order->push();
        }

        $array["shipmentAddress"] = $order->address;

        $array['agent'] = [
            "meta" => [
                "href" => "https://api.moysklad.ru/api/remap/1.2/entity/counterparty/".$order->contact->ms_id,
                "type"=>"counterparty",
                "mediaType"=>"application/json"
            ]
        ];


        $date=new \DateTime($order->created_at);
        $array["moment"]=$date->format('Y-m-d H:i:s');

        if ($order->comment!=null) {
            $array["description"] = $order->comment;
        }

        if ($order->date_plan!=null){
            $date=new \DateTime($order->date_plan);
            $array["deliveryPlannedMoment"]=$date->format('Y-m-d H:i:s');
        }

        foreach ($order->positions as $position) {

            $product=Product::find($position["product_id"]);
            $ms_id=$product->ms_id;

            if ($ms_id!=null){
                if ($product->category_id==20){
                    $array["positions"][] = [
                        "quantity" => (float)$position["quantity"],
                        "price" => (float)$position["price"] * 100,
                        'vat' => (int)$vat,
                        "assortment" => [
                            "meta" => [
                                "href" => "https://api.moysklad.ru/api/remap/1.2/entity/service/" . $ms_id,
                                "type" => "service",
                                "mediaType" => "application/json"
                            ]
                        ]
                    ];
                }else{
                    if ($position["quantity"] != 0) {
                        $array["positions"][] = [
                            "quantity" => (float)$position["quantity"],
                            "price" => (float)$position["price"] * 100,
                            'vat' => (int)$vat,
                            "assortment" => [
                                "meta" => [
                                    "href" => "https://api.moysklad.ru/api/remap/1.2/entity/product/" . $ms_id,
                                    "type" => "product",
                                    "mediaType" => "application/json"
                                ]
                            ]
                        ];

                    }
                }
            }
        }


        if ($order->delivery!=null && $order->delivery->ms_id !=null){
            $array["attributes"][] = [
                'meta' => [
                    'href' => "https://api.moysklad.ru/api/remap/1.2/entity/customerorder/metadata/attributes/ebd3862f-5c92-11ea-0a80-0535000bb626",
                    'type' => "attributemetadata",
                    "mediaType" => "application/json"
                ],
                'value' => [
                    'meta' => [
                        'href' => "https://api.moysklad.ru/api/remap/1.2/entity/customentity/ebd3862f-5c92-11ea-0a80-0535000bb626/".$order->delivery->ms_id ,
                        'type' => "customentity",
                        "mediaType" => "application/json",
                    ],
                ],
                "id" => "ebd3862f-5c92-11ea-0a80-0535000bb626",
                "name" => "Доставка",
                "type" => "customentity"
            ];
        }

        if($order->status && $order->status->ms_id) {
            $array['state'] = [
                'meta' => [
                    'href' => "https://api.moysklad.ru/api/remap/1.2/entity/customerorder/metadata/states/" .$order->status->ms_id,
                    "metadataHref" => "https://api.moysklad.ru/api/remap/1.2/entity/customerorder/metadata",
                    'type' => "state",
                    "mediaType" => "application/json"
                ],
            ];
        }

        if ($order->ms_id == null) {
            return $this->moySkladService->actionPostRowsFromJson($url, $array);
        } else {
            return $this->moySkladService->actionPutRowsFromJson($url . $order->ms_id, $array);
        }
    }

}
