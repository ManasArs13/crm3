<?php

namespace App\Observers;

use App\Models\Order;
use App\Models\OrderAmo;
use App\Models\OrderAmoOrder;
use App\Models\StatusAmo;

class OrderAmoObserver
{
    public function created(OrderAmo $orderAmo): void
    {
        if ($orderAmo->order_id) {

            $order = Order::where('id', $orderAmo->order_id)->select('id', 'ms_id')->First();

            if ($order) {
                $orderAmoOrder = OrderAmoOrder::query()->firstOrNew(['order_amo_id' => $orderAmo->id]);
                $orderAmoOrder->ms_id = $order->ms_id;
                $orderAmoOrder->order_id = $order->id;
                $orderAmoOrder->order_amo_id = $orderAmo->id;
                $orderAmoOrder->save();
            }
        }
    }

    public function updated(OrderAmo $orderAmo): void
    {
        $orderMs = $orderAmo->orderMs;

        $statusMsId = StatusAmo::query()->where('id', $orderAmo->status_amo_id)->value('status');

        if (
            $orderAmo->isDirty('status_amo_id')
            && $orderAmo->status_amo_id !== $statusMsId
        ) {
            if ($statusMsId && $orderMs) {
                $orderMs->update(['status_id' => $statusMsId]);
            }
        }
    }
}
