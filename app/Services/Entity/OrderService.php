<?php

namespace App\Services\Entity;

use App\Contracts\EntityInterface;
use App\Models\Contact;
use App\Models\Delivery;
use App\Models\Option;
use App\Models\Order;
use App\Models\ShipingPrice;
use App\Models\Status;
use App\Models\Transport;
use App\Models\TransportType;
use App\Services\Api\MoySkladService;
use Carbon\Carbon;
use DateTime;
use Exception;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Support\Arr;


class OrderService implements EntityInterface
{
    private Option $options;
    private OrderPositionService $orderPositionService;
    private MoySkladService $service;


    public function __construct(Option $options, OrderPositionService $orderPositionService, MoySkladService $service)
    {
        $this->options = $options;
        $this->orderPositionService = $orderPositionService;
        $this->service = $service;
    }

    /**
     * @throws Exception
     */
    public function import(array $rows): void
    {
        $attributeDelivery = $this->options::query()->where('code', '=', "ms_order_delivery_guid")->first()?->value;
        $attributeTransport = $this->options::query()->where("code", '=', "ms_order_transport_guid")->first()?->value;
        $attributeVehicleType = $this->options::query()->where("code", '=', "ms_order_vehicle_type_guid")->first()?->value;
        $attributeDeliveryPrice = $this->options::query()->where("code", '=', "ms_order_delivery_price_guid")->first()?->value;
        $attributeIsMade = $this->options::query()->where("code", '=', "ms_order_made_guid")->first()?->value;
        $attributeLinkToAmo = $this->options::query()->where("code", '=', "ms_orders_amo_url_guid")->first()?->value;

        foreach ($rows['rows'] as $row) {
            $entity = Order::query()->firstOrNew(['ms_id' => $row["id"]]);
            if (Arr::exists($row, 'deleted')) {
                if ($entity->ms_id === null) {
                    $entity->positions()->delete();
                    $entity->delete();
                }
            } else {
                if ($entity->ms_id === null) {
                    $entity->ms_id = $row['id'];
                }

                $datePlan = isset($row["deliveryPlannedMoment"]) ? new DateTime($row["deliveryPlannedMoment"]) : null;
                $dateCreated = isset($row["moment"]) ? new DateTime($row["moment"]) : null;
                $delivery = null;
                $transport = null;
                $deliveryPrice = 0;
                $vehicleType = null;
                $isMade = 0;
                $amoOrder = null;
                $amoOrderLink = null;
                $comment = null;

                $entity->name = $row["name"];

                $contact_bd = Contact::where('ms_id', $row["agent"]["id"])->first();
                $entity->contact_id = $contact_bd ? $contact_bd->id : null;

                $status_bd = Status::where('ms_id', $row["state"]["id"])->first();
                $entity->status_id = $status_bd->id;

                $entity->date_plan = $datePlan;
                $entity->date_moment = $dateCreated;
                $entity->sum = $row["sum"] / 100;
                $entity->shipped_sum = $row["shippedSum"] / 100;
                $entity->reserved_sum = $row["reservedSum"] / 100;
                $entity->payed_sum = $row["payedSum"] / 100;
                $statusShipped = ($row["shippedSum"] == $row["sum"]) ? 1 : ($row["shippedSum"] > 0 ? -1 : 0);
                $entity->status_shipped = $statusShipped;
                $entity->debt = ($row["shippedSum"] - $row["payedSum"]) / 100;

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
                            case $attributeIsMade:
                                $isMade = $attribute["value"];
                                break;
                            case $attributeLinkToAmo:
                                $amoOrderLink = $attribute["value"];
                                $amoOrder = $this->getGuidFromUrl($amoOrderLink);
                                break;
                        }
                    }
                }

                $transport_bd = Transport::where('ms_id', $transport)->first();
                $entity->transport_id = $transport_bd ? $transport_bd->id : null;

                $delivery_bd = Delivery::where('ms_id', $delivery)->first();
                $entity->delivery_id = $delivery_bd ? $delivery_bd->id : null;

                $transport_type_bd = TransportType::where('ms_id', $vehicleType)->first();
                $entity->transport_type_id = $transport_type_bd ? $transport_type_bd->id : null;

                $entity->delivery_price = $deliveryPrice;
                $entity->is_made = $isMade;
                $entity->is_demand = 0;
                $entity->order_amo_link = $amoOrderLink;
                $entity->order_amo_id = $amoOrder;
                $entity->comment = $comment;

                if (isset($row["description"])) {
                    $entity->comment = $row["description"];
                }
                if (Arr::exists($row, 'created')) {
                    $entity->created_at = $row['created'];
                }

                if (Arr::exists($row, 'updated')) {
                    $entity->updated_at = $row['updated'];
                }
            }
            $entity->save();

            $needDelete = $this->orderPositionService->import($row["positions"], $entity->id);

            if ($needDelete["needDelete"]) {
                $entity->positions()->delete();
                $entity->delete();
            } else {
                $entity->is_demand = $needDelete["isDemand"];
                $entity->save();
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

    /**
     * @return void
     */
    public function reserve(): void
    {
        $url = 'https://api.moysklad.ru/api/remap/1.2/entity/customerorder/';
        $urlAttr = 'https://online.moysklad.ru/api/remap/1.2/entity/customerorder/metadata/attributes/';
        $guidAttrMade = 'f49dcd09-ba32-11ec-0a80-0105000445a6';
        $reserve_period = (int)Option::query()->where('code', 'reserve_period')->value('value');

        $orders = Order::query()
            ->where('date_plan', '!=', null)
            ->whereDate('date_plan', '<=', Carbon::now()->addDays($reserve_period))
            ->where('status_ms_id', 'c3308ff8-b57a-11ec-0a80-03c60005472c')
            ->orWhere('status_ms_id', '2ff7dd14-5b1e-11ea-0a80-012400161237')
            ->whereHas('positions', function (Builder $query) {
            })->with('positions')->get();

        foreach ($orders as $order) {
            $positions = $order->positions;

            foreach ($positions as $position) {

                if ($position->product != null) {

                    if ($position->product->type == 'продукция' && $position->product->building_material == 'блок') {
                        $this->service->actionPutRowsFromJson($url . $order->id . '/positions/' . $position->id, ['reserve' => abs($position->quantity - $position->shipped)]);
                    }
                } else {
                    $this->service->actionPutRowsFromJson($url . $order->id . '/positions/' . $position->id, ['reserve' => 0]);
                }
            }

        }
    }

    public function checkRows()
    {
        $url = 'https://api.moysklad.ru/api/remap/1.2/entity/customerorder/';


        $orders = Order::with('positions')->get();


        foreach ($orders as $order) {
            $positions = $order->positions;


            try {
                $this->service->actionGetRowsFromJson($url . $order->id, false);
                usleep(60000);
            } catch (RequestException  $e) {

                if ($e->getCode() == 404) {

                    foreach ($positions as $position) {
                        if ($position->product != null) {
                            $order->positions()->forceDelete();
                        }
                    }

                    $order->forceDelete();
                    info($order->id . ' delete');
                }
                info($e->getMessage());
            }
        }
    }

    public function calcOfDeliveryPriceNorm()
    {

        $orders = Order::with(['delivery', 'vehicle_type', 'transport'])->get();

        foreach ($orders as $order) {
            $delivery = $order->delivery;
            $weight_kg = $order->weight;
            $vehicleType = $order->vehicle_type;

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

                $weightNew = ceil($order->weight);

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
                        $orderUpdate = Order::where('id', $order->id)->First();
                        $orderUpdate->delivery_price_norm = $shipingPrice->price;
                        $orderUpdate->update();
                    }

                } else {

                    if ($shipingPrice) {
                        $orderUpdate = Order::where('id', $order->id)->First();
                        $orderUpdate->delivery_price_norm = $shipingPrice->price * $weightNew;
                        $orderUpdate->update();
                    }

                }
            }
        }
    }
}
