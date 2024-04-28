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
        $guidAttrAmoContact = $this->options::where('code', '=', "ms_counterparty_amo_id_contact_guid")->first()?->value;
        $guidAttrAmoContactLink = 'bb95261f-972b-11ed-0a80-0e9300807fe0';

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

                $contactMs = $this->service->actionGetRowsFromJson($row["agent"]['meta']["href"], false);

                if (isset($contactMs['balance'])) {

                    $entityContact = Contact::firstOrNew(['ms_id' => $contactMs["id"]]);

                    if ($entityContact !== null) {
                        $entityContact->balance = $contactMs["balance"] / 100;
                    }
                } else {
                    $entityContact = Contact::firstOrNew(['ms_id' => $contactMs['id']]);

                    if ($entityContact->ms_id === null) {
                        $entityContact->ms_id = $contactMs['id'];
                    }

                    $entityContact->name = $contactMs['name'];

                    $phone = null;
                    $phoneNorm = null;

                    if (isset($contactMs['phone'])) {
                        $phone = $contactMs["phone"];
                        $pattern = "/(\+7|8|7)(\s?(\-|\()?\d{3}(\-|\))?\s?\d{3}-?\d{2}-?\d{2})/";
                        $phones = preg_replace('/[\(,\s,\),\-, \+]/', '', $contactMs["phone"]);
                        preg_match_all($pattern, $phones, $matches);
                        if (isset($matches[2]))
                            $phoneNorm = "+7" . implode('', $matches[2]);
                    }

                    $entityContact->phone = $phone;
                    $entityContact->phone_norm = $phoneNorm;

                    $email = null;

                    if (isset($contactMs['email'])) {
                        $email = $contactMs["email"];
                    }

                    $entityContact->email = $email;

                    $isArchived = 0;

                    if (isset($contactMs['archived'])) {
                        $isArchived = $contactMs["archived"];
                    }

                    $entityContact->is_archived = $isArchived;

                    $amoContact = null;
                    $amoContactLink = null;

                    if (isset($contactMs["attributes"])) {
                        foreach ($contactMs["attributes"] as $attribute) {
                            switch ($attribute["id"]) {
                                case $guidAttrAmoContact:
                                    $amoContact = $attribute["value"];
                                    break;
                                case $guidAttrAmoContactLink:
                                    $amoContactLink = $attribute["value"];
                                    break;
                            }
                        }
                    }

                    $entityContact->contact_amo_id = $amoContact;
                    $entityContact->contact_amo_link = $amoContactLink;
                    $entityContact->is_exist = 1;

                    if ($contactMs['created']) {
                        $entityContact->created_at = $contactMs['created'];
                    }

                    if ($contactMs['updated']) {
                        $entityContact->updated_at = $contactMs['updated'];
                    }
                }
                $entityContact->save();

                $entity->contact_id = $entityContact->id;

                if(isset($row['state'])) {
                    $status_bd = Status::where('ms_id', $this->getGuidFromUrl($row['state']['meta']['href']))->first();
                    $entity->status_id = $status_bd->id;
                } else {
                    $entity->status_id = null;
                }              

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
            ->where('status_id', 'c3308ff8-b57a-11ec-0a80-03c60005472c')
            ->orWhere('status_id', '2ff7dd14-5b1e-11ea-0a80-012400161237')
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

    public function checkContacts()
    {
        $orders = Order::whereNull('contact_id')->whereNotNull('ms_id')->get();

        $url = 'https://api.moysklad.ru/api/remap/1.2/entity/customerorder/';
        $guidAttrAmoContact = $this->options::where('code', '=', "ms_counterparty_amo_id_contact_guid")->first()?->value;
        $guidAttrAmoContactLink = 'bb95261f-972b-11ed-0a80-0e9300807fe0';

        foreach ($orders as $order) {

            try {
                $orderMs = $this->service->actionGetRowsFromJson($url . $order->ms_id, false);
                if ($orderMs) {
                    if ($orderMs['agent']['meta']['href']) {
                        $contactMs = $this->service->actionGetRowsFromJson($orderMs['agent']['meta']['href'], false);

                        if (isset($contactMs['balance'])) {

                            $entity = Contact::firstOrNew(['ms_id' => $contactMs["id"]]);

                            if ($entity !== null) {
                                $entity->balance = $contactMs["balance"] / 100;
                            }
                        } else {
                            $entity = Contact::firstOrNew(['ms_id' => $contactMs['id']]);

                            if ($entity->ms_id === null) {
                                $entity->ms_id = $contactMs['id'];
                            }

                            $entity->name = $contactMs['name'];

                            $phone = null;
                            $phoneNorm = null;

                            if (isset($contactMs['phone'])) {
                                $phone = $contactMs["phone"];
                                $pattern = "/(\+7|8|7)(\s?(\-|\()?\d{3}(\-|\))?\s?\d{3}-?\d{2}-?\d{2})/";
                                $phones = preg_replace('/[\(,\s,\),\-, \+]/', '', $contactMs["phone"]);
                                preg_match_all($pattern, $phones, $matches);
                                if (isset($matches[2]))
                                    $phoneNorm = "+7" . implode('', $matches[2]);
                            }

                            $entity->phone = $phone;
                            $entity->phone_norm = $phoneNorm;

                            $email = null;

                            if (isset($contactMs['email'])) {
                                $email = $contactMs["email"];
                            }

                            $entity->email = $email;

                            $isArchived = 0;

                            if (isset($contactMs['archived'])) {
                                $isArchived = $contactMs["archived"];
                            }

                            $entity->is_archived = $isArchived;

                            $amoContact = null;
                            $amoContactLink = null;

                            if (isset($contactMs["attributes"])) {
                                foreach ($contactMs["attributes"] as $attribute) {
                                    switch ($attribute["id"]) {
                                        case $guidAttrAmoContact:
                                            $amoContact = $attribute["value"];
                                            break;
                                        case $guidAttrAmoContactLink:
                                            $amoContactLink = $attribute["value"];
                                            break;
                                    }
                                }
                            }

                            $entity->contact_amo_id = $amoContact;
                            $entity->contact_amo_link = $amoContactLink;
                            $entity->is_exist = 1;

                            if ($contactMs['created']) {
                                $entity->created_at = $contactMs['created'];
                            }

                            if ($contactMs['updated']) {
                                $entity->updated_at = $contactMs['updated'];
                            }
                        }
                        $entity->save();

                        $order->contact_id = $entity->id;
                        $order->update();
                    }
                }
            } catch (RequestException  $e) {
                info($e->getMessage());
            }
        }
    }
}
