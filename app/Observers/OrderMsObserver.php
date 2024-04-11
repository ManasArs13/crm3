<?php

namespace App\Observers;

use App\Models\Order;
use App\Models\StatusAmo;

class OrderMsObserver
{

    /**
     * Handle the OrderMs "updated" event.
     */
    public function updated(Order $orderMs): void
    {
        if ($orderMs->isDirty('status_id')){

            $statusAmoId = StatusAmo::query()->where('status', $orderMs->status_id)->value('id');

            if ($statusAmoId) {
                $orderMs->orderAmo()->update(['status_amo_id' => $statusAmoId]);
            }
        }
    }

}
