<?php

namespace App\Services\Entity;

use App\Contracts\EntityInterface;
use App\Models\Contact;
use App\Models\Delivery;
use App\Models\Manager;
use App\Models\Option;
use App\Models\Order;
use App\Models\OrderPosition;
use App\Models\Product;
use App\Models\ShipingPrice;
use App\Models\Shipment;
use App\Models\ShipmentProduct;
use App\Models\Status;
use App\Models\Transport;
use App\Models\TransportType;
use App\Services\Api\MoySkladService;
use App\Services\EntityMs\CounterpartyMsService;
use Carbon\Carbon;
use DateTime;
use Exception;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use GuzzleHttp\Client;

class OrderService implements EntityInterface
{
    private Option $options;
    private OrderPositionService $orderPositionService;
    private CounterpartyMsService $counterpartyMsService;
    private MoySkladService $service;
    private string $auth;
    private $client;


    public function __construct(
        Option $options,
        OrderPositionService $orderPositionService,
        CounterpartyMsService $counterpartyMsService,
        MoySkladService $service
    ) {
        $login = $options::where('code', '=', 'ms_login')->first()?->value;
        $password = $options::where('code', '=', 'ms_password')->first()?->value;
        $this->options = $options;
        $this->orderPositionService = $orderPositionService;
        $this->counterpartyMsService = $counterpartyMsService;
        $this->service = $service;
        $this->auth = base64_encode($login . ':' . $password);
        $this->client = new Client();
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
        $attributeManager = '5acf51d7-4339-11ef-0a80-04b600053534';

        foreach ($rows['rows'] as $row) {
            $entity = Order::query()->firstOrNew(['ms_id' => $row["id"]]);

            if (isset($row["deleted"])) {
                $shipments = Shipment::where('order_id', $entity->id)->get();

                foreach ($shipments as $shipment) {
                    if ($shipment) {
                        $shipment->products()->forceDelete();
                        $shipment->forceDelete();
                    }
                }


                $entity->positions()->forceDelete();
                $entity->forceDelete();
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

                usleep(2000);
                $contactMs = $this->service->actionGetRowsFromJson($row["agent"]['meta']["href"], false);

                if (isset($contactMs['balance'])) {

                    $entityContact = Contact::first(['ms_id' => $contactMs["id"]]);

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

                if (isset($row['state'])) {
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
                            case $attributeManager:
                                $managerMS_id = $this->getGuidFromUrl($attribute['value']['meta']['href']);
                                $manager = Manager::where('ms_id', $managerMS_id)->first();
                                if ($manager) {
                                    $entity->manager_id = $manager->id;
                                }
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

                $entity->save();

                $needDelete = $this->orderPositionService->import($row["positions"], $entity->id);

                if ($needDelete["needDelete"]) {
                    $shipments = Shipment::where('order_id', $entity->id)->get();

                    foreach ($shipments as $shipment) {
                        if ($shipment) {
                            $shipment->products()->forceDelete();
                            $shipment->forceDelete();
                        }
                    }

                    $entity->positions()->forceDelete();
                    $entity->forceDelete();
                } else {
                    $entity->is_demand = $needDelete["isDemand"];
                    $entity->save();
                }
            }
        }
    }

    public function calcLateAndNoShipmentForTheOrder($date)
    {
        $orders = Order::leftJoin(DB::raw('(select order_id , min(created_at) as moment from shipments where order_id is not null group by order_id) as t0'), 't0.order_id', '=', 'orders.id')
            ->whereNotIn("orders.status_id", [1, 7])
            ->whereNotNull("date_plan")
            ->whereRaw("date_plan<now()")
            ->where("updated_at", ">", $date)
            ->where(function ($query) {
                $query->whereRaw("t0.moment>orders.date_plan or t0.moment is null");
            })->get();

        foreach ($orders as $order) {
            $order->late = 1;
            $order->save();
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
            ->whereHas('positions', function (Builder $query) {})->with('positions')->get();

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


        $orders = Order::with('positions')->chunkById(100, function ($orders) use ($url) {

            foreach ($orders as $order) {

                try {
                    usleep(200);

                    $response = $this->client->request('GET', $url . $order->ms_id, [
                        'headers' => [
                            'Accept-Encoding' => 'gzip',
                            'Authorization' => 'Basic ' . $this->auth
                        ],
                    ]);

                    $result = json_decode($response->getBody()->getContents(), true);

                    if (isset($result["deleted"])) {
                        $shipments = Shipment::where('order_id', $order->id)->get();

                        foreach ($shipments as $shipment) {
                            if ($shipment) {
                                $shipment->products()->forceDelete();
                                $shipment->forceDelete();
                            }
                        }

                        $order->positions()->forceDelete();

                        $order->forceDelete();

                        info('Order №' . $order->ms_id . ' has been deleted!');
                    }
                } catch (RequestException  $e) {

                    if ($e->getCode() == 404) {
                        $shipments = Shipment::where('order_id', $order->id)->get();

                        foreach ($shipments as $shipment) {
                            if ($shipment) {
                                $shipment->products()->forceDelete();
                                $shipment->forceDelete();
                            }
                        }

                        $order->positions()->forceDelete();

                        $order->forceDelete();

                        info($e->getMessage());
                        info('Order №' . $order->ms_id . ' has been deleted!');
                    }
                }
            }
        });
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

    function createOrder($array)
    {
        $order = new Order();

        $status_bd = Status::where('ms_id', $array["state"])->first();
        $order->status_id = $status_bd->id;

        if (isset($array["agent"]["id"])) {
            $contact_bd = Contact::where('ms_id', $array["agent"]["id"])->first();
            $order->contact_id = $contact_bd->id;
        } else {
            $contact = new Contact();
            $contact->name = $array["agent"]["name"];
            $contact->phone = $array["agent"]["phone"];
            //check it;
            $agent = $this->counterpartyMsService->updateCounterpartyMs($array["agent"]);
            $contact->ms_id = $agent->id;

            $contact->save();
            $order->contact_id = $contact->id;
        }

        $delivery_bd = Delivery::where('ms_id', $array["attributes"]["delivery"]['id'])->first();
        $order->delivery_id = $delivery_bd ? $delivery_bd->id : null;

        $transport_type_bd = TransportType::where('ms_id', $array["attributes"]["vehicle_type"]["id"])->first();
        $order->transport_type_id = $transport_type_bd ? $transport_type_bd->id : null;


        $order->comment = $array["description"];
        $order->date_plan = $array["deliveryPlannedMoment"];
        $order->date_moment = new DateTime();
        $order->sum = 0;
        $order->weight = 0;
        $order->name = "CRM-";

        $order->save();

        $sum = 0;
        $weight = 0;

        if (isset($array["positions"])) {
            foreach ($array["positions"] as $product) {
                if (isset($product['product_id'])) {
                    $position = new OrderPosition();

                    $product_bd = Product::where('ms_id', $product['product_id'])->first();
                    $position->product_id = $product_bd->id;
                    $position->order_id = $order->id;
                    $position->quantity = $product['quantity'];
                    $position->price = $product_bd->price;
                    $position->weight_kg = $product_bd->weight_kg * $product['quantity'];
                    $position->shipped = 0;
                    $position->reserve = 0;

                    $position->save();

                    $sum += $position->price * $product['quantity'];
                    $weight += $position->weight_kg;
                }
            }
        }

        if (isset($array["services"])) {
            foreach ($array["services"] as $product) {
                if (isset($product['product_id'])) {
                    $position = new OrderPosition();

                    $product_bd = Product::where('ms_id', $product['product_id'])->first();
                    $position->product_id = $product_bd->id;
                    $position->order_id = $order->id;
                    $position->quantity = $product['quantity'];
                    $position->price = $product_bd->price;
                    $position->weight_kg = $product_bd->weight_kg * $product['quantity'];
                    $position->shipped = 0;
                    $position->reserve = 0;

                    $position->save();

                    $sum += $position->price * $product['quantity'];
                    $weight += $position->weight_kg;
                }
            }

            $order->sum = $sum;
            $order->weight = $weight;
        }

        $order->name = "CRM-" . $order->id;
        $order->sum = $sum;
        $order->weight = $weight;


        $order->update();

        return ["id" => $order->id, "name" => $order->name];
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

            if (\Arr::exists($msOrder, "services")) {
                foreach ($msOrder["services"] as $key => $position) {

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
}
