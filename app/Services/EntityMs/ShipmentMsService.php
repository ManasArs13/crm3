<?php

namespace App\Services\EntityMs;

use App\Models\Delivery;
use App\Models\Option;
use App\Models\Product;
use App\Models\Shipment;
use App\Services\Api\MoySkladService;
use App\Services\EntityMs\CounterpartyMsService;
use Illuminate\Support\Facades\Log;

class ShipmentMsService
{
    private $moySkladService;
    private $counterpartyMsService;

    public function __construct(MoySkladService $moySkladService, CounterpartyMsService $counterpartyMsService)
    {
        $this->moySkladService = $moySkladService;
        $this->counterpartyMsService = $counterpartyMsService;
    }

    function createChipmentToMs($msChipment)
    {
       $shipment=Shipment::with('order', 'contact', 'delivery', 'transport', 'transport_type', 'products')->find($msChipment);
       $vat = Option::where('code', '=', "ms_vat")->first()?->value;
       $url="https://api.moysklad.ru/api/remap/1.2/entity/demand/";

        $array = [];
        // $array["name"]="06099";
        $array['organization'] = [
            "meta" => [
                "href" => "https://api.moysklad.ru/api/remap/1.2/entity/organization/03957745-4672-11ee-0a80-0dbe00139b20",
                "type" => "organization",
                "mediaType" => "application/json",
            ]
        ];

        $msId=$shipment->contact->ms_id;
        if ($msId==null){
            $arContact=["name"=>$shipment->contact->name, "phone"=>$shipment->contact->phone];
            $agent = $this->counterpartyMsService->updateCounterpartyMs($arContact);
            $msId = $agent->id;
        }

        $array['agent']=[
            "meta" => [
                "href" => "https://api.moysklad.ru/api/remap/1.2/entity/counterparty/".$shipment->contact->ms_id,
                "type"=>"counterparty",
                "mediaType"=>"application/json"
            ]
        ];


        $array["store"]=[
              "meta"=> [
                "href"=>"https://api.moysklad.ru/api/remap/1.2/entity/store/4fee76ec-824d-11e7-7a69-8f5500016b03",
                "type"=>"store",
                "mediaType"=>"application/json"
              ]
        ];


        $date=new \DateTime($shipment->created_at);
        $array["moment"]=$date->format('Y-m-d H:i:s');


        if ($shipment->order!=null && $shipment->order->ms_id !=null){
            $array["customerOrder"]=[
                "meta"=>[
                    "href"  => "https://api.moysklad.ru/api/remap/1.2/entity/customerorder/".$shipment->order->ms_id,
                    "type" =>"customerorder",
                    "mediaType"=> "application/json"
                ]
            ];
        }


        if ($shipment->description!=null) {
            $array["shipmentAddressFull"]["comment"] = $shipment->description;
        }


        foreach ($shipment->products as $position) {

            $product=Product::find($position["product_id"]);
            $ms_id=$product->ms_id;

            if ($product->category_id==20){
                // $array["positions"][] = [
                //     "quantity" => (float)$position["quantity"],
                //     "price" => (float)$position["price"] * 100,
                //     'vat' => (int)$vat,
                //     "assortment" => [
                //         "meta" => [
                //             "href" => "https://api.moysklad.ru/api/remap/1.2/entity/service/" . $ms_id,
                //             "type" => "service",
                //             "mediaType" => "application/json"
                //         ]
                //     ]
                // ];
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


        if ($shipment->delivery!=null && $shipment->delivery->ms_id !=null){
            $array["attributes"][] = [
                'meta' => [
                    'href' => "https://api.moysklad.ru/api/remap/1.2/entity/demand/metadata/attributes/368d7401-25d9-11ec-0a80-0844000fc7ea",
                    'type' => "attributemetadata",
                    "mediaType" => "application/json"
                ],
                'value' => [
                    'meta' => [
                        'href' => "https://api.moysklad.ru/api/remap/1.2/entity/customentity/8b306150-5c8b-11ea-0a80-02ed000aa214/".$shipment->delivery->ms_id ,
                        'type' => "customentity",
                        "mediaType" => "application/json",
                    ],
                ],
                "id" => "368d7401-25d9-11ec-0a80-0844000fc7ea",
                "name" => "Доставка",
                "type" => "customentity"
            ];
        }

        if ($shipment->transport_type!=null && $shipment->transport_type->ms_id!=null ){
            $array["attributes"][] = [
                'meta' => [
                    'href' => "https://api.moysklad.ru/api/remap/1.2/entity/demand/metadata/attributes/ba39ab18-a5ee-11ec-0a80-00d3000c54d7",
                    'type' => "attributemetadata",
                    "mediaType" => "application/json"
                ],
                'value' => [
                    'meta' => [
                        'href' => "https://api.moysklad.ru/api/remap/1.2/entity/customentity/86e0e802-8d7e-11ec-0a80-05e6002ff525/".$shipment->transport_type->ms_id ,
                        'type' => "customentity",
                        "mediaType" => "application/json",
                    ],
                ],
                "id" => "ba39ab18-a5ee-11ec-0a80-00d3000c54d7",
                "name" => "Тип доставки",
                "type" => "customentity"
            ];
       }


       if ($shipment->transport!=null){
            $array["attributes"][] = [
                'meta' => [
                    'href' => "https://api.moysklad.ru/api/remap/1.2/entity/demand/metadata/attributes/82e830fe-9e05-11ec-0a80-01d700314656",
                    'type' => "attributemetadata",
                    "mediaType" => "application/json"
                ],
                'value' => [
                    'meta' => [
                        'href' => "https://api.moysklad.ru/api/remap/1.2/entity/customentity/45afdcd7-88cb-11ec-0a80-0e2e000bdce1/".$shipment->transport->ms_id ,
                        'type' => "customentity",
                        "mediaType" => "application/json",
                    ],
                ],
                "id" => "82e830fe-9e05-11ec-0a80-01d700314656",
                "name" => "Транспорт",
                "type" => "customentity"
            ];
        }

        if ($shipment->ms_id==null) {
            return $this->moySkladService->actionPostRowsFromJson($url, $array);
        } else {
            return $this->moySkladService->actionPutRowsFromJson($url . $shipment->ms_id, $array);
        }
    }
}
