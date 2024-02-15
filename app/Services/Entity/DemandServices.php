<?php

namespace App\Services\Entity;

use App\Contracts\EntityInterface;
use App\Models\Delivery;
use App\Models\OrderMs;
use App\Models\Option;
use App\Models\Product;
use App\Models\Shipments;
use App\Models\ShipmentsProducts;
use App\Models\ShippingPrice;
use App\Models\VehicleType;
use App\Services\Api\MoySkladService;
use Illuminate\Support\Arr;
use App\Helpers\Math;

class DemandServices implements EntityInterface
{
    private Option $options;

    public MoySkladService $service;
    public function __construct(Option $options, MoySkladService $service)
    {
        $this->service = $service;
        $this->options = $options;
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

        foreach ($rows['rows'] as $row) {
            $products = $row['positions']['rows'];
            $urlService = 'https://api.moysklad.ru/app/#demand/edit?id=';

            $entity = Shipments::query()->firstOrNew(['id' => $row["id"]]);
            if (Arr::exists($row, 'deleted')) {
                if ($entity->id === null) {
                    $entity->delete();
                }
            } else {

                $delivery = null;
                $transport = null;
                $deliveryPrice = 0;
                $vehicleType = null;
                $deliveryFee= null;
                $shipmentWeight = 0.0;

                $orderId = isset($row['customerOrder']) ? $this->getGuidFromUrl($row['customerOrder']['meta']['href']) : null;
                $entity->id = $row['id'];
                $entity->name = $row['name'];
                $entity->description = !empty($row['description']) ? $row['description'] : null;
                $entity->shipment_address = $row['shipmentAddress'] ?? null;
                $entity->order_id = OrderMs::query()->where('id', $orderId)->exists() ? $orderId : null;
                $entity->counterparty_link = $row['agent']['meta']['uuidHref'];
                $entity->service_link = $urlService . $row['id'];
                $entity->paid_sum = $row['payedSum'] / 100;
                $entity->status = isset($row['state']) ? $row['state']['name'] : null;
                $entity->created_at = $row['moment'];
                $entity->suma = Math::rounding_up_to($row['sum'] / 100, 500);
                $entity->updated_at = $row['updated'];


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
                        }
                    }
                }

                $entity->transport_id=$transport;
                $entity->delivery_id=$delivery;
                $entity->vehicle_type_id=$vehicleType;
                $entity->delivery_price=$deliveryPrice;
                $entity->delivery_fee=$deliveryFee;
                $entity->weight = $shipmentWeight;
                $entity->save();

                foreach ($products as $product) {
                    $productData = null;
                    if (isset($product['assortment']['meta']['href'])) {
                        $productData = $this->service->actionGetRowsFromJson($product['assortment']['meta']['href'], false);
                    }
                    $product_db = Product::query()->where('id', $productData['id'])->first();
                    
                    if ($product_db) {
                        ShipmentsProducts::query()->updateOrCreate(
                            ['shipment_id' => $row['id']],
                            [
                                'shipment_id' => $row['id'],
                                'quantity' => $product['quantity'],
                                'product_id' => $productData['id'],
                            ]
                        );

                        $shipmentWeight += $product["quantity"] * Product::query()->where('id', $productData['id'])->first()->weight_kg;

                    }
                }

                $entity->weight = $shipmentWeight;
                $entity->update();
            }
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
        $shipments = Shipments::get();

        foreach ($shipments as $shipment) {
            $delivery = Delivery::where('id', $shipment->delivery_id)->First();
            $weight_kg = $shipment->weight;
            $vehicleType = VehicleType::where('id', $shipment->vehicle_type_id)->First();

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

                $shipingPrice = ShippingPrice::where('vehicle_type_id', $vehicleType->id)
                    ->where('distance', $distanceNew)
                    ->where('tonnage', $weightNew)
                    ->first();

                if ($shipingPrice == null) {
                    $shipingPrice = ShippingPrice::where('vehicle_type_id', $vehicleType->id)
                        ->where('distance', $distanceNew)
                        ->where('tonnage', 1.0)
                        ->first();
 
                    if ($shipingPrice) {
                        $shipmentUpdate = Shipments::where('id', $shipment->id)->First();
                        $shipmentUpdate->delivery_price_norm = $shipingPrice->price;
                        $shipmentUpdate->update();
                    }

                } else {

                    if ($shipingPrice) {
                        $shipmentUpdate = Shipments::where('id', $shipment->id)->First();
                        $shipmentUpdate->delivery_price_norm = $shipingPrice->price * $weightNew;
                        $shipmentUpdate->update();
                    }

                }

            
            }


        }
       
    }
}
