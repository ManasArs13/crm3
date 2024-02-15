<?php

namespace App\Services\Entity;

use App\Models\Option;
use App\Services\Api\MoySkladService;

class OrderService
{
    private $service;
    private $options;

    public function __construct(MoySkladService  $service, Option $options)
    {
        $this->service = $service;
        $this->options = $options;
    }


    function updateOrderMs($msOrder)
    {
        $url = $this->options::where('code', '=',"ms_orders_url")->first()?->value;
        $urlProduct = $this->options::where('code', '=',"ms_product_for_order_url")->first()?->value;
        $urlCounterparty = $this->options::where('code', '=',"ms_counterparty_url")->first()?->value;
        $urlDelivery = $this->options::where('code', '=',"ms_delivery_url")->first()?->value;
        $urlVehicleCategory = $this->options::where('code', '=',"ms_vehicle_category_url")->first()?->value;

        $urlState =$this->options::where('code', '=',"ms_orders_need_status_url")->first()?->value;

        $urlAttr = $this->options::where('code', '=','ms_attributes_order_date_url')->first()?->value;
        $guidAttrPallet = $this->options::where('code', '=','ms_order_pallet_guid')->first()?->value;
        $guidAttrWeight = $this->options::where('code', '=','ms_order_weight_guid')->first()?->value;
        $guidAttrDelivery = $this->options::where('code', '=','ms_order_delivery_guid')->first()?->value;
        $guidAttrDateFact = $this->options::where('code', '=','ms_order_date_fact_guid')->first()?->value;
        $guidAttrVehicleCategory = $this->options::where('code', '=','ms_order_vehicle_category_guid')->first()?->value;
        $guidAttrDeliveryPrice = $this->options::where('code', '=','ms_order_delivery_price_guid')->first()?->value;
        $urlOrganization = $this->options::where('code', '=',"ms_organization_url")->first()?->value;
        $guidOrganization = $this->options::where('code', '=','ms_organization_guid')->first()?->value;

        $array=[];

        if (isset($msOrder["positions"])) {
            $array["positions"] = [];
            foreach ($msOrder["positions"] as $position) {
                $array["positions"][] = [
                    "quantity" => (float)$position["quantity"],
                    "price" => (float)$position["price"] * 100,
                    "assortment" => [
                        "meta" => [
                            "href" => $urlProduct . $position["product_id"],
                            "type" => "product",
                            "mediaType" => "application/json"
                        ]
                    ]
                ];
            }
        }

        //"2021-10-13 16:34:00.000"
        if (isset($msOrder["comment"])){
            $array["description"]=$msOrder["comment"];
        }

        if (isset($msOrder["deliveryPlannedMoment"])){
            $array["deliveryPlannedMoment"]=$msOrder["deliveryPlannedMoment"];
        }

        if (!isset($msOrder["guid"]) || $msOrder["guid"]==null) {
            $array["organization"] = [
                "meta" => [
                    "href" => $urlOrganization.$guidOrganization,
                    "type" => "organization",
                    "mediaType" => "application/json"
                ]
            ];
        }

        if (isset($msOrder["contact"])) {
            $array["agent"] = [
                "meta" => [
                    "href" => $urlCounterparty . $msOrder["contact"],
                    "type" => "counterparty",
                    "mediaType" => "application/json"
                ]
            ];
        }

        if(isset($msOrder["countPallets"]))
            $array["attributes"][]=
                [
                    'meta' => [
                        'href' => $urlAttr . $guidAttrPallet,
                        'type' => "attributemetadata",
                        "mediaType" => "application/json"
                    ],
                    'value' => $msOrder["countPallets"]
                ];

        if (isset($msOrder["weight"]))
            $array["attributes"][]=[
                'meta' => [
                    'href' => $urlAttr . $guidAttrWeight,
                    'type' => "attributemetadata",
                    "mediaType" => "application/json"
                ],
                'value' => $msOrder["weight"]
            ];

        if (isset($msOrder["deliveryPrice"]))
            $array["attributes"][]=[
                'meta' => [
                    'href' => $urlAttr . $guidAttrDeliveryPrice,
                    'type' => "attributemetadata",
                    "mediaType" => "application/json"
                ],
                'value' => $msOrder["deliveryPrice"]
            ];

        if (isset($msOrder["delivery"]))
            $array["attributes"][]=[
                'meta' => [
                    'href' => $urlAttr . $guidAttrDelivery,
                    'type' => "attributemetadata",
                    "mediaType" => "application/json"
                ],
                'value' => [
                    'meta' => [
                        'href' => $urlDelivery . $msOrder["delivery"]["id"],
                        'type' => "customentity",
                        "mediaType" => "application/json",
                    ],
                    "name"=>$msOrder["delivery"]["name"]
                ],
                "id"=> $guidAttrDelivery,
                "type"=> "customentity"
            ];

        if (isset($msOrder["vehicle_type"]))
            $array["attributes"][]=[
                'meta' => [
                    'href' => $urlAttr . $guidAttrVehicleCategory,
                    'type' => "attributemetadata",
                    "mediaType" => "application/json"
                ],
                'value' => [
                    'meta' => [
                        'href' => $urlVehicleCategory . $msOrder["vehicle_type"]["id"],
                        'type' => "customentity",
                        "mediaType" => "application/json",
                    ],
                    "name"=>$msOrder["vehicle_type"]["name"]
                ],
                "id"=> $guidAttrVehicleCategory,
                "type"=> "customentity"
            ];


        if (isset($msOrder["dateFact"]))
            $array["attributes"][] = [
                [
                    'meta' => [
                        'href' => $urlAttr . $guidAttrDateFact,
                        'type' => "attributemetadata",
                        "mediaType" => "application/json"
                    ],
                    'value' => $msOrder["dateFact"]
                ]
            ];

//        if (isset($msOrder["amoId"])) {
//            $array["attributes"][] = [
//                'meta' => [
//                    'href' => $urlAttr . $guidAttrAmo,
//                    'type' => "attributemetadata",
//                    "mediaType" => "application/json"
//                ],
//                'value' => ($msOrder["amoId"]=='')?'':$urlToAmo.$msOrder["amoId"]
//            ];
//        }

        if (isset($msOrder["state"])){
            $array["state"]  = [
                'meta' => [
                    'href' => $urlState . $msOrder["state"],
                    'type' => "state",
                    "mediaType" => "application/json"
                ]
            ];
        }

        if (!isset($msOrder["guid"]) || $msOrder["guid"]==null) {
            return $this->service->actionPostRowsFromJson($url, $array);
        }else{
            return $this->service->actionPutRowsFromJson($url.$msOrder["guid"], $array);
        }
    }
}
