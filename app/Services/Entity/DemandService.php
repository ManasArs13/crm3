<?php

namespace App\Services\Entity;

use App\Contracts\EntityInterface;
use App\Models\Delivery;
use App\Models\Option;
use App\Models\Product;
use App\Services\Api\MoySkladService;
use Illuminate\Support\Arr;
use App\Helpers\Math;
use App\Models\Carrier;
use App\Models\Contact;
use App\Models\Order;
use App\Models\ShipingPrice;
use App\Models\Shipment;
use App\Models\ShipmentProduct;
use App\Models\Transport;
use App\Models\TransportType;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

class DemandService implements EntityInterface
{
    private Option $options;
    public OrderService $orderService;
    public MoySkladService $service;
    public ContactMsService $contactMsService;
    private string $auth;
    private $client;

    public function __construct(Option $options, MoySkladService $service, OrderService $orderService, ContactMsService $contactMsService)
    {
        $login = $options::where('code', '=', 'ms_login')->first()?->value;
        $password = $options::where('code', '=', 'ms_password')->first()?->value;
        $this->options = $options;
        $this->service = $service;
        $this->orderService = $orderService;
        $this->contactMsService = $contactMsService;
        $this->auth = base64_encode($login . ':' . $password);
        $this->client = new Client();
    }

    /**
     * @param array $rows
     * @return void
     */
    public function import(array $rows): void
    {
        $attributeDelivery = '368d7401-25d9-11ec-0a80-0844000fc7ea';
        $attributeTransport = '82e830fe-9e05-11ec-0a80-01d700314656';
        $attributeVehicleType = 'ba39ab18-a5ee-11ec-0a80-00d3000c54d7';
        $attributeDeliveryPrice = "368d767a-25d9-11ec-0a80-0844000fc7ec";
        $attributeDeliveryFee = "bf195073-ebc2-11ec-0a80-0173001b47dd";
        $attributeCarrier = '368d75db-25d9-11ec-0a80-0844000fc7eb';

        foreach ($rows['rows'] as $row) {

            $urlService = 'https://online.moysklad.ru/app/#demand/edit?id=';

            $entity = Shipment::query()->firstOrNew(['ms_id' => $row["id"]]);

            if (Arr::exists($row, 'deleted')) {
                if ($entity->ms_id === null) {
                    $entity->delete();
                }
            } else {

                $delivery = null;
                $transport = null;
                $carrier = null;
                $deliveryPrice = 0;
                $vehicleType = null;
                $deliveryFee = null;
                $shipmentWeight = 0.0;
                $state = null;
                $order_db = null;
                $contact_db = null;
                $counterpartyLink = null;

                if (isset($row['customerOrder'])) {
                    $orderId = $this->getGuidFromUrl($row['customerOrder']['meta']['href']);
                    $order_db = Order::query()->where('ms_id', $orderId)->first();
                }

                if (isset($row['agent'])) {
                    $agentId = $this->getGuidFromUrl($row['agent']['meta']['href']);
                    $contact_db = Contact::query()->where('ms_id', $agentId)->first();

                    if ($contact_db) {
                        $entity->contact_id = $contact_db->id;
                    } else {
                        $row = $this->service->actionGetRowsFromJson($row['agent']['meta']['href'], false);
                        $this->contactMsService->importOne($row);

                        $contact_db = Contact::query()->where('ms_id', $agentId)->first();
                    }

                    $counterpartyLink = 'https://online.moysklad.ru/app/#company/edit?id=' . $agentId;
                }

                if (isset($row['state'])) {
                    $state = $this->service->actionGetRowsFromJson($row['state']['meta']['href'], false)['name'];
                }

                $entity->order_id = $order_db ? $order_db->id : null;
                $entity->contact_id = $contact_db ? $contact_db->id : null;
                $entity->counterparty_link = $counterpartyLink;

                $entity->status = $state;
                $entity->ms_id = $row['id'];
                $entity->name = $row['name'];
                $entity->description = !empty($row['description']) ? $row['description'] : null;
                $entity->shipment_address = $row['shipmentAddress'] ?? null;

                $entity->service_link = $urlService . $row['id'];
                $entity->paid_sum = isset($row['payedSum']) ? $row['payedSum'] / 100 : 0;
                $entity->suma = isset($row['sum']) ? Math::rounding_up_to($row['sum'] / 100, 500) : 0;

                $entity->created_at = isset($row['moment']) ? $row['moment'] : Carbon::now();
                $entity->updated_at = isset($row['updated']) ? $row['updated'] : Carbon::now();
                $entity->moment_at = isset($row['moment']) ? $row['moment'] : Carbon::now();

                if (isset($row["attributes"])) {
                    foreach ($row["attributes"] as $attribute) {
                        switch ($attribute["id"]) {
                            case $attributeDelivery:
                                $delivery = $attribute["value"]["id"];
                                break;
                            case $attributeTransport:
                                $transport = $attribute["value"]["id"];
                                break;
                            case $attributeVehicleType:
                                $vehicleType = $attribute["value"]["id"];
                                break;
                            case $attributeDeliveryPrice:
                                $deliveryPrice = $attribute["value"];
                                break;
                            case $attributeDeliveryFee:
                                $deliveryFee = $attribute["value"];
                                break;
                            case $attributeCarrier:
                                $carrier = $this->getGuidFromUrl($attribute["value"]["meta"]["href"]);
                                $carrier_bd = Carrier::where('ms_id', $carrier)->firstorNew();

                                if ($carrier_bd != null) {
                                    $carrier = $carrier_bd->id;
                                } else {
                                    $carrier_bd->name = $attribute["value"];
                                    $carrier_bd->ms_id = $carrier;
                                    $carrier_bd->save();
                                    $carrier = $carrier_bd->id;
                                }
                                break;
                        }
                    }
                }

                $transport_bd = Transport::where('ms_id', $transport)->first();
                $entity->transport_id = $transport_bd ? $transport_bd->id : null;

                $delivery_bd = Delivery::where('ms_id', $delivery)->first();
                $entity->delivery_id = $delivery_bd ? $delivery_bd->id : null;
                $distance = $delivery_bd ? $delivery_bd->distance : null;

                $transport_type_bd = TransportType::where('ms_id', $vehicleType)->first();
                $entity->transport_type_id = $transport_type_bd ? $transport_type_bd->id : null;

                $entity->carrier_id = $carrier;
                $entity->delivery_price = $deliveryPrice;
                $entity->delivery_fee = $deliveryFee;
                $entity->weight = $shipmentWeight;
                $entity->save();


                if (isset($row["positions"])) {

                    $positions = $this->service->actionGetRowsFromJson($row['positions']['meta']['href']);
                    $guids = [];

                    if ($positions) {
                        foreach ($positions as $position) {
                            $entity_position = ShipmentProduct::firstOrNew(['ms_id' => $position['id']]);

                            if ($entity_position->ms_id === null) {
                                $entity_position->ms_id = $position['id'];
                            }

                            $entity_position->shipment_id = $entity->id;
                            $entity_position->quantity = $position['quantity'];
                            $entity_position->price = $position['price'] / 100;

                            $guids[] = $position["id"];
                            $product_bd = Product::where('ms_id', $this->getGuidFromUrl($position['assortment']['meta']['href']))->first();

                            if ($product_bd) {
                                $entity_position->product_id = $product_bd['id'];
                                $entity_position->save();

                                $shipmentWeight += $position["quantity"] * $product_bd->weight_kg;
                            }
                        }

                        if (count($guids) > 0) {
                            $this->deleteDeletedPositionsFromMS($entity->id, $guids);
                        }
                    }
                }

                $entity->delivery_price_norm = 0;
                $entity->saldo = $entity->delivery_price;
                $entity->weight = $shipmentWeight;
                if ($entity->transport_type_id != null && $distance != null && in_array($entity->transport_type_id, [2, 3, 4, 5, 6])) {
                    $weight_tn = ($entity->transport_type_id == 2) ? round($shipmentWeight / 1000, 1) : ceil($shipmentWeight / 1000);
                    $entity->delivery_price_norm = $this->getPrice(["distance" => $distance, "weightTn" => $weight_tn, "vehicleType" => $entity->transport_type_id]);
                    $entity->saldo = $entity->delivery_price - $entity->delivery_price_norm;
                }
                $entity->update();
            }
        }
    }


    public function deleteDeletedPositionsFromMS($shipment, $guids)
    {
        ShipmentProduct::where("shipment_id", $shipment)->whereNotIn('ms_id', $guids)->delete();
    }

    public function getPrice($data)
    {
        $distance = $data["distance"];
        $vehicleType = $data["vehicleType"];
        $weightTn = $data["weightTn"];

        $price = 0;
        $deliveryPrice = 0;

        $innerQuery = DB::table('shiping_prices')
            ->selectRaw('transport_type_id, min(distance) as minDistance')
            ->where('transport_type_id', $vehicleType)
            ->where('distance', '>=', $distance);

        $mainQuery0 = DB::table(DB::raw('(' . $innerQuery->toSql() . ') as tab'))
            ->mergeBindings($innerQuery)
            ->selectRaw("sh.distance, min(sh.tonnage) as tonnage, sh.transport_type_id")
            ->join("shiping_prices as sh", function ($join) {
                $join->on('tab.minDistance', '=', 'sh.distance');
                $join->on("tab.transport_type_id", "=", "sh.transport_type_id");
            })
            ->where("sh.tonnage", '>=', $weightTn);

        $mainQuery = DB::table(DB::raw('(' . $mainQuery0->toSql() . ') as tab0'))
            ->mergeBindings($mainQuery0)
            ->selectRaw("sh2.price, sh2.tonnage as ton, sh2.distance")
            ->join("shiping_prices as sh2", function ($join) {
                $join->on('tab0.distance', '=', 'sh2.distance');
                $join->on("tab0.transport_type_id", "=", "sh2.transport_type_id");
                $join->on("tab0.tonnage", "=", "sh2.tonnage");
            })->first();


        if ($mainQuery == null) {
            $mainQuery = DB::table(DB::raw('(' . $innerQuery->toSql() . ') as tab'))
                ->mergeBindings($innerQuery)
                ->selectRaw("sh2.tonnage as ton, sh2.distance, sh2.price")
                ->join("shiping_prices as sh2", function ($join) {
                    $join->on('tab.minDistance', '=', 'sh2.distance');
                    $join->on("tab.transport_type_id", "=", "sh2.transport_type_id");
                })
                ->orderBy("sh2.tonnage", "desc")
                ->first();

            if ($mainQuery == null) {
                return 0;
            } else {
                $price = $mainQuery->price;
            }
        } else {
            $price = $mainQuery->price;
        }

        if ($mainQuery->ton > $weightTn) {
            $weightTn = $mainQuery->ton;
        }

        $deliveryPrice = $price * $weightTn;

        if ($vehicleType == 5 || $vehicleType == 3)
            $deliveryPrice = round($deliveryPrice / 100) * 100;
        else if ($vehicleType == 4 && $weightTn <= 14)
            $deliveryPrice = round($deliveryPrice / 500) * 500;

        return $deliveryPrice;
    }

    public function DeviationForShipments($date)
    {

        ShipmentProduct::where("created_at", ">=", $date)
            ->update(['deviation_price' => 0]);


        $skip = 0;
        $take = 200;

        while (true) {
            $innerQuery = DB::table('shipments as sh')
                ->selectRaw('max(pl.created_at) as min ,sh.created_at, sh.id as shipment')
                ->join("price_lists as pl", "sh.created_at", ">=", "pl.created_at")
                ->where("sh.created_at", ">=", $date)->groupBy("sh.id")
                ->skip($skip)
                ->take($take);

            $positions = DB::table(DB::raw('(' . $innerQuery->toSql() . ') as tab'))
                ->selectRaw("plp.price as priceList, sp.price, sp.id  as position")
                ->mergeBindings($innerQuery)
                ->join("shipment_products as sp", "tab.shipment", "=", "sp.shipment_id")
                ->join("price_list_positions as plp", function ($join) {
                    $join->on("plp.created_at", "=", "tab.min");
                    $join->on("plp.product_id", "=", "sp.product_id");
                })->get();

            if (count($positions) === 0) {
                break;
            }

            foreach ($positions as $pos) {
                $shipmentPos = ShipmentProduct::where('id', '=', $pos->position)->first();
                $shipmentPos->deviation_price = $pos->priceList - $pos->price;
                $shipmentPos->save();
            }
            $skip = $skip + $take;
        }
    }



    public function DeliveryPriceNormAndSaldoForShipments($date)
    {
        Shipment::where("updated_at", ">", $date)
            ->update(['delivery_price_norm' => 0, 'saldo' => 0]);

        $skip = 0;
        $take = 100;

        while (true) {
            $demands = Shipment::whereIn("transport_type_id", [2, 3, 4, 5, 6])
                ->whereNotNull("delivery_id")->where("updated_at", ">", $date)
                ->skip($skip)
                ->take($take)
                ->with("delivery")
                ->get();

            if (count($demands) === 0) {
                break;
            }

            foreach ($demands as $demand) {
                $weight_tn = ($demand->transport_type_id == 2) ? round($demand->weight / 1000, 1) : ceil($demand->weight / 1000);
                $distance = $demand->delivery->distance;
                $demand->delivery_price_norm = $this->getPrice(["distance" => $distance, "weightTn" => $weight_tn, "vehicleType" => $demand->transport_type_id]);
                $demand->saldo = $demand->delivery_price - $demand->delivery_price_norm;
                $demand->save();
            }
            $skip = $skip + $take;
        }
    }

    /**
     * @param $url
     * @return string
     */
    public function getGuidFromUrl($url): string
    {
        $arUrl = explode("/", $url);
        return $arUrl[count($arUrl) - 1];
    }

    public function calcOfDeliveryPriceNorm()
    {
        $shipments = Shipment::get();

        foreach ($shipments as $shipment) {
            $delivery = Delivery::where('id', $shipment->delivery_id)->First();
            $weight_kg = $shipment->weight;
            $vehicleType = TransportType::where('id', $shipment->vehicle_type_id)->First();

            if ($vehicleType && $weight_kg !== '0.0' && $weight_kg && $delivery) {

                $distanceNew = 0;

                switch ($delivery->distance) {
                    case $delivery->distance <= 25:
                        $distanceNew = 25;
                        break;
                    case $delivery->distance > 25 && $delivery->distance <= 30:
                        $distanceNew = 30;
                        break;
                    case $delivery->distance > 30 && $delivery->distance <= 40:
                        $distanceNew = 40;
                        break;
                    case $delivery->distance > 40 && $delivery->distance <= 50:
                        $distanceNew = 50;
                        break;
                    case $delivery->distance > 50 && $delivery->distance <= 60:
                        $distanceNew = 60;
                        break;
                    case $delivery->distance > 60 && $delivery->distance <= 70:
                        $distanceNew = 70;
                        break;
                    case $delivery->distance > 70 && $delivery->distance <= 80:
                        $distanceNew = 80;
                        break;
                    case $delivery->distance > 80 && $delivery->distance <= 90:
                        $distanceNew = 90;
                        break;
                    case $delivery->distance > 90 && $delivery->distance <= 100:
                        $distanceNew = 100;
                        break;
                    case $delivery->distance > 100 && $delivery->distance <= 120:
                        $distanceNew = 120;
                        break;
                    case $delivery->distance > 120 && $delivery->distance <= 140:
                        $distanceNew = 140;
                        break;
                    case $delivery->distance > 140 && $delivery->distance <= 160:
                        $distanceNew = 160;
                        break;
                    case $delivery->distance > 160 && $delivery->distance <= 180:
                        $distanceNew = 180;
                        break;
                    case $delivery->distance > 180 && $delivery->distance <= 200:
                        $distanceNew = 200;
                        break;
                    case $delivery->distance > 200:
                        $distanceNew = 220;
                        break;
                }

                $weightNew = ceil($shipment->weight * 0.001);

                $shipingPrice = ShipingPrice::where('vehicle_type_id', $vehicleType->id)
                    ->where('distance', $distanceNew)
                    ->where('tonnage', $weightNew)
                    ->first();

                if ($shipingPrice == null) {
                    $shipingPrice = ShipingPrice::where('vehicle_type_id', $vehicleType->id)
                        ->where('distance', $distanceNew)
                        ->where('tonnage', 1.0)
                        ->first();

                    if ($shipingPrice) {
                        $shipmentUpdate = Shipment::where('id', $shipment->id)->First();
                        $shipmentUpdate->delivery_price_norm = $shipingPrice->price;
                        $shipmentUpdate->update();
                    }
                } else {

                    if ($shipingPrice) {
                        $shipmentUpdate = Shipment::where('id', $shipment->id)->First();
                        $shipmentUpdate->delivery_price_norm = $shipingPrice->price * $weightNew;
                        $shipmentUpdate->update();
                    }
                }
            }
        }
    }

    public function checkRows()
    {
        $url = 'https://api.moysklad.ru/api/remap/1.2/entity/demand/';

        $shipments = Shipment::with('products')->chunkById(100, function ($shipments) use ($url) {

            foreach ($shipments as $shipment) {

                try {
                    usleep(200);

                    $response = $this->client->request('GET', $url . $shipment->ms_id, [
                        'headers' => [
                            'Accept-Encoding' => 'gzip',
                            'Authorization' => 'Basic ' . $this->auth
                        ],
                    ]);

                    $result = json_decode($response->getBody()->getContents(), true);

                    if (isset($result["deleted"])) {
                        $shipment->deleted_at = $result["deleted"];
                        $shipment->save();
                    }
                } catch (RequestException  $e) {

                    if ($e->getCode() == 404) {
                        $shipment->products()->cursor()->each->delete();

                        $shipment->forceDelete();

                        info($e->getMessage());
                        info('Shipment №' . $shipment->ms_id . ' has been deleted!');
                    }
                }
            }
        });
    }
}
