<?php

namespace App\Http\Controllers\Api\Site;

use App\Http\Controllers\Controller;
use App\Models\ContactAmo;
use App\Models\Errors;
use App\Models\ErrorTypes;
use App\Models\Order;
use App\Models\Product;
use App\Models\Shipment;
use Carbon\Carbon;

class ErrorController extends Controller
{
    public function update()
    {
        $types = ErrorTypes::get()->keyBy('id');
        $now = now();


        $this->processContactsWithoutManagers($types, $now);
        $this->processShipmentsWithoutOrders($types, $now);
        $this->processOrdersWithConcreteOnDifferentDays($types, $now);
        $this->processOrdersAndShipmentsMismatch($types, $now);

        return redirect()->back();
    }

    protected function processContactsWithoutManagers($types, $now)
    {
        ContactAmo::chunkById(100, function ($contacts) use ($types, $now) {
            $contactIds = $contacts->pluck('id')->toArray();
            $existingErrors = Errors::where('type_id', $types[1]['id'])
                ->whereIn('tab_id', $contactIds)
                ->get()
                ->keyBy('tab_id');

            $insertData = [];

            foreach ($contacts as $contact) {
                if (is_null($contact->manager_id) && !isset($existingErrors[$contact->id])) {
                    $insertData[] = [
                        'status' => 1,
                        'allowed' => 1,
                        'type_id' => $types[1]['id'],
                        'link' => url("/contactAmo/{$contact->id}/edit"),
                        'description' => 'Отсутствует менеджер id',
                        'responsible_user' => $types[1]['responsible'],
                        'tab_id' => $contact->id,
                        'created_at' => $now,
                        'updated_at' => $now,
                    ];
                }
            }

            if (!empty($insertData)) {
                Errors::insert($insertData);
            }
        });
    }

    protected function processShipmentsWithoutOrders($types, $now)
    {
        Shipment::chunkById(100, function ($shipments) use ($types, $now) {
            $shipmentIds = $shipments->pluck('id')->toArray();
            $existingErrors = Errors::where('type_id', $types[3]['id'])
                ->whereIn('tab_id', $shipmentIds)
                ->get()
                ->keyBy('tab_id');

            $insertData = [];

            foreach ($shipments as $shipment) {
                if (is_null($shipment->order_id) && !isset($existingErrors[$shipment->id])) {
                    $insertData[] = [
                        'status' => 1,
                        'allowed' => 1,
                        'type_id' => $types[3]['id'],
                        'link' => url("/shipment/{$shipment->id}"),
                        'description' => 'Отсутствует заказ',
                        'responsible_user' => $types[3]['responsible'],
                        'tab_id' => $shipment->id,
                        'created_at' => $now,
                        'updated_at' => $now,
                    ];
                }
            }

            if (!empty($insertData)) {
                Errors::insert($insertData);
            }
        });
    }

    protected function processOrdersWithConcreteOnDifferentDays($types, $now)
    {
        Shipment::chunkById(100, function ($shipments) use ($types, $now) {
            $shipmentIds = $shipments->pluck('id')->toArray();
            $existingErrors = Errors::where('type_id', $types[4]['id'])
                ->whereIn('tab_id', $shipmentIds)
                ->get()
                ->keyBy('tab_id');

            $shipmentsWithOrders = Shipment::whereIn('id', $shipmentIds)
                ->whereHas('order.positions.product', function ($query) {
                    $query->where('building_material', Product::CONCRETE);
                })
                ->with('order')
                ->get();

            $groupedByOrder = [];
            $insertData = [];

            foreach ($shipmentsWithOrders as $shipment) {
                $orderId = $shipment->order_id;
                $date = Carbon::parse($shipment->created_at)->toDateString();
                $groupedByOrder[$orderId][$date][] = $shipment;
            }

            foreach ($groupedByOrder as $orderId => $dates) {
                if (count($dates) > 1) {
                    foreach ($dates as $shipments) {
                        foreach ($shipments as $shipment) {
                            if (!isset($existingErrors[$shipment->id])) {
                                $insertData[] = [
                                    'status' => 1,
                                    'allowed' => 1,
                                    'type_id' => $types[4]['id'],
                                    'link' => url("/order/{$shipment->order_id}"),
                                    'description' => 'Заказ имеет отгрузки на разные дни для бетона',
                                    'responsible_user' => $types[4]['responsible'],
                                    'tab_id' => $shipment->id,
                                    'created_at' => $now,
                                    'updated_at' => $now,
                                ];
                            }
                        }
                    }
                }
            }

            if (!empty($insertData)) {
                Errors::insert($insertData);
            }
        });
    }

    protected function processOrdersAndShipmentsMismatch($types, $now)
    {
        Order::chunkById(100, function ($orders) use ($types, $now) {
            $orderIds = $orders->pluck('id')->toArray();
            $existingErrors = Errors::where('type_id', $types[5]['id'])
                ->whereIn('tab_id', $orderIds)
                ->get()
                ->keyBy('tab_id');

            $insertData = [];

            foreach ($orders as $order) {
                $shipmentProductsSum = $order->shipment_products->sum(fn($sp) => $sp->quantity * $sp->price);
                $positionsSum = $order->positions->sum(fn($pos) => $pos->quantity * $pos->price);

                if ($shipmentProductsSum !== $positionsSum && !isset($existingErrors[$order->id])) {
                    $insertData[] = [
                        'status' => 1,
                        'allowed' => 1,
                        'type_id' => $types[5]['id'],
                        'link' => url("/order/{$order->id}"),
                        'description' => 'Заказ и отгрузки не сходятся',
                        'responsible_user' => $types[5]['responsible'],
                        'tab_id' => $order->id,
                        'created_at' => $now,
                        'updated_at' => $now,
                    ];
                }
            }

            if (!empty($insertData)) {
                Errors::insert($insertData);
            }
        });
    }
}
