<?php

namespace App\Services\EntityMs;

use App\Models\Option;
use App\Services\Api\MoySkladService;
use App\Services\EntityMs\CounterpartyMsService;

class OrderMsService
{

    private $moySkladService;
    private $counterpartyMsService;

    public function __construct(MoySkladService $moySkladService, CounterpartyMsService $counterpartyMsService){
        $this->moySkladService=$moySkladService;
        $this->counterpartyMsService=$counterpartyMsService;
    }

    function updateOrderMs($msOrder)
    {
        $url = Option::where('code', '=',"ms_orders_url")->first()?->value;
        $urlProduct = Option::where('code', '=',"ms_product_for_order_url")->first()?->value;
        $customentity= Option::where('code', '=',"ms_customentity_url")->first()?->value;

        $urlCounterparty = Option::where('code', '=',"ms_counterparty_url")->first()?->value;
        $urlDelivery = Option::where('code', '=',"ms_delivery_url")->first()?->value;
        $urlVehicleCategory = Option::where('code', '=',"ms_vehicle_type_url")->first()?->value;

        $urlState =Option::where('code', '=',"ms_orders_need_status_url")->first()?->value;
        $urlAttr = Option::where('code', '=','ms_attributes_order_date_url')->first()?->value;

        $guidAttrDelivery = Option::where('code', '=','ms_order_delivery_guid')->first()?->value;
        $guidAttrDeliveryPrice = Option::where('code', '=','ms_order_delivery_price_guid')->first()?->value;
        $guidAttrTransportType = Option::where('code', '=','ms_order_transport_type_guid')->first()?->value;

        $urlOrganization = Option::where('code', '=',"ms_organization_url")->first()?->value;
        $guidOrganization = Option::where('code', '=','ms_organization_guid')->first()?->value;

        $array=[];

        if (isset($msOrder["positions"])) {
            $array["positions"] = [];
            foreach ($msOrder["positions"] as $position) {
                if ($position["quantity"]!=0){
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
        }

        if (isset($msOrder["description"])){
            $array["description"]=$msOrder["description"];
        }


        if (isset($msOrder["deliveryPlannedMoment"])){
            $array["deliveryPlannedMoment"]=$msOrder["deliveryPlannedMoment"];
        }

        if (!isset($msOrder["id"]) || $msOrder["id"]==null) {
            $array["organization"] = [
                "meta" => [
                    "href" => $urlOrganization.$guidOrganization,
                    "type" => "organization",
                    "mediaType" => "application/json"
                ]
            ];
        }

        if (isset($msOrder["agent"])) {
            if (is_array($msOrder["agent"])){
                if (isset($msOrder["agent"]["id"]) && $msOrder["agent"]["id"]!=null){
                    $msOrder["agent"] = $msOrder["agent"]["id"];
                }else{
                    $agent=$this->counterpartyMsService->updateCounterpartyMs($msOrder["agent"]);
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


        if (isset($msOrder["state"])){
            $array["state"]  = [
                'meta' => [
                    'href' => $urlState . $msOrder["state"],
                    'type' => "state",
                    "mediaType" => "application/json"
                ]
            ];
        }

        if (\Arr::exists($msOrder,"attributes")){
            if (\Arr::exists($msOrder["attributes"], "deliveryPrice"))
                $array["attributes"][]=[
                    'meta' => [
                        'href' => $urlAttr . $guidAttrDeliveryPrice,
                        'type' => "attributemetadata",
                        "mediaType" => "application/json"
                    ],
                    'value' => $msOrder["attributes"]["deliveryPrice"]
                ];

            if (\Arr::exists($msOrder["attributes"], "delivery"))
                $array["attributes"][]=[
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
                        // "name"=>$msOrder["attributes"]["delivery"]["name"]
                    ],
                    "id"=> $guidAttrDelivery,
                    "type"=> "customentity"
                ];


            if (\Arr::exists($msOrder["attributes"], "vehicle_type"))
                $array["attributes"][]=[
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
                        // "name"=>$msOrder["attributes"]["vehicle_type"]["name"]
                    ],
                    "id"=> $guidAttrTransportType,
                    "type"=> "customentity"
                ];
        }

        if (!isset($msOrder["id"]) || $msOrder["id"]==null) {
            return $this->moySkladService->actionPostRowsFromJson($url, $array);
        }else{
            return $this->moySkladService->actionPutRowsFromJson($url.$msOrder["guid"], $array);
        }
    }
}
