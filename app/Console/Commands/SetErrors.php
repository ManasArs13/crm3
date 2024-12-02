<?php

namespace App\Console\Commands;

use App\Models\ContactAmo;
use App\Models\Errors;
use App\Models\ErrorTypes;
use App\Models\Order;
use App\Models\Product;
use App\Models\Shipment;
use Carbon\Carbon;
use Illuminate\Console\Command;

class SetErrors extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:set-errors';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // Контакты АМО без менеджера

        $types = ErrorTypes::get()->keyBy('id');
        $now = now();

        ContactAmo::chunkById(500, function ($contacts) use ($types, $now) {
            $contactIds = $contacts->pluck('id')->toArray();

            $existingErrors = Errors::where('type_id', $types[1]['id'])
                ->whereIn('tab_id', $contactIds)
                ->get()
                ->keyBy('tab_id');

            $insertData = [];

            foreach ($contacts as $contact) {
                $link = url("/contactAmo/{$contact->id}/edit");

                if (is_null($contact->manager_id)) {
                    if (!isset($existingErrors[$contact->id])) {
                        $insertData[] = [
                            'status' => 1,
                            'allowed' => 1,
                            'type_id' => $types[1]['id'],
                            'link' => $link,
                            'description' => 'Отсутствует менеджер id',
                            'responsible_user' => $types[1]['responsible'],
                            'tab_id' => $contact->id,
                            'created_at' => $now,
                            'updated_at' => $now,
                        ];
                    }
                }
            }

            if (!empty($insertData)) {
                Errors::insert($insertData);
            }
        });


        // --------------------------
        // Контакты АМО без менеджера
        // --------------------------


        // у отгрузки нет заказа
        Shipment::chunkById(500, function ($shipments) use ($types, $now) {
            $shipmentIds = $shipments->pluck('id')->toArray();

            $existingErrors = Errors::where('type_id', $types[3]['id'])
                ->whereIn('tab_id', $shipmentIds)
                ->get()
                ->keyBy('tab_id');

            $insertData = [];

            foreach ($shipments as $shipment) {
                $link = url("/shipment/{$shipment->id}");

                if (is_null($shipment->order_id)) {
                    if (!isset($existingErrors[$shipment->id])) {
                        $insertData[] = [
                            'status' => 1,
                            'allowed' => 1,
                            'type_id' => $types[3]['id'],
                            'link' => $link,
                            'description' => 'Отсутствует заказ',
                            'responsible_user' => $types[3]['responsible'],
                            'tab_id' => $shipment->id,
                            'created_at' => $now,
                            'updated_at' => $now,
                        ];
                    }
                }
            }

            if (!empty($insertData)) {
                Errors::insert($insertData);
            }
        });


        // у заказа отгрузки в разных днях у бетона
        Shipment::chunkById(500, function ($shipments) use ($types, $now) {
            $shipmentIds = $shipments->pluck('id')->toArray();

            $existingErrors = Errors::where('type_id', $types[4]['id'])
                ->whereIn('tab_id', $shipmentIds)
                ->get()
                ->keyBy('tab_id');

            $shipmentsWithOrders = Shipment::whereIn('id', $shipmentIds)
                ->whereHas('order.positions', function ($query) {
                    $query->whereHas('product', function ($productQuery) {
                        $productQuery->where('building_material', Product::CONCRETE);
                    });
                })
                ->with('order')
                ->get();

            $groupedByOrder = [];
            foreach ($shipmentsWithOrders as $shipment) {
                $orderId = $shipment->order_id;
                $date = Carbon::parse($shipment->created_at)->toDateString();

                $groupedByOrder[$orderId][$date][] = $shipment;
            }

            $insertData = [];
            foreach ($groupedByOrder as $orderId => $dates) {
                if (count($dates) > 1) {
                    foreach ($dates as $date => $shipments) {
                        foreach ($shipments as $shipment) {
                            $link = url("/order/{$shipment->order_id}");
                            if(!isset($existingErrors[$shipment->id])){
                                $insertData[] = [
                                    'status' => 1,
                                    'allowed' => 1,
                                    'type_id' => $types[4]['id'],
                                    'link' => $link,
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


        // заказ и отгрузки не сходятся
        Order::chunkById(500, function ($orders) use ($types, $now) {
            $orderIds = $orders->pluck('id')->toArray();

            $existingErrors = Errors::where('type_id', $types[5]['id'])
                ->whereIn('tab_id', $orderIds)
                ->get()
                ->keyBy('tab_id');

            $insertData = [];

            foreach ($orders as $order) {

                $shipmentProductsSum = $order->shipment_products->sum(function ($shipmentProduct) {
                    return $shipmentProduct->quantity * $shipmentProduct->price;
                });

                $positionsSum = $order->positions->sum(function ($position) {
                    return $position->quantity * $position->price;
                });

                if ($shipmentProductsSum !== $positionsSum) {
                    $link = url("/order/{$order->id}");
                    if (!isset($existingErrors[$order->id])){
                        $insertData[] = [
                            'status' => 1,
                            'allowed' => 1,
                            'type_id' => $types[5]['id'],
                            'link' => $link,
                            'description' => 'заказ и отгрузки не сходятся',
                            'responsible_user' => $types[4]['responsible'],
                            'tab_id' => $order->id,
                            'created_at' => $now,
                            'updated_at' => $now,
                        ];
                    }
                }
            }

            if (!empty($insertData)) {
                Errors::insert($insertData);
            }
        });
    }
}
