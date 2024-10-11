<?php

namespace App\Observers\Shipment;

use App\Models\ShipmentProduct;
use Illuminate\Contracts\Events\ShouldHandleEventsAfterCommit;

class ShipmentProductObserver implements ShouldHandleEventsAfterCommit
{
    /**
     * Handle the ShipmentProduct "created" event.
     */
    public function created(ShipmentProduct $shipmentProduct): void
    {
        $shipment = $shipmentProduct->shipment;

        if ($shipment->order && $shipment->order->positions) {
            foreach ($shipment->order->positions as $position) {
                if ($shipmentProduct->product_id == $position->product_id) {
                    $position->shipped_crm += $shipmentProduct->quantity;
                    $position->push();
                }
            }
        }
    }

    /**
     * Handle the ShipmentProduct "updated" event.
     */
    public function updated(ShipmentProduct $shipmentProduct): void
    {
        if ($shipmentProduct->isDirty('quantity')) {
            $shipment = $shipmentProduct->shipment;

            if ($shipment->order && $shipment->order->positions) {
                foreach ($shipment->order->positions as $position) {
                    if ($shipmentProduct->product_id == $position->product_id) {
                        $position->shipped_crm += $shipmentProduct->getOriginal('quantity') - $shipmentProduct->quantity;
                        $position->push();
                    }
                }
            }
        }
    }

    /**
     * Handle the ShipmentProduct "deleted" event.
     */
    public function deleted(ShipmentProduct $shipmentProduct): void
    {
        $shipment = $shipmentProduct->shipment;

        if ($shipment->order && $shipment->order->positions) {
            foreach ($shipment->order->positions as $position) {
                if ($shipmentProduct->product_id == $position->product_id) {
                    $position->shipped_crm -= $shipmentProduct->quantity;
                    $position->push();
                }
            }
        }
    }

    /**
     * Handle the ShipmentProduct "restored" event.
     */
    public function restored(ShipmentProduct $shipmentProduct): void
    {
        //
    }

    /**
     * Handle the ShipmentProduct "force deleted" event.
     */
    public function forceDeleted(ShipmentProduct $shipmentProduct): void
    {
        $shipment = $shipmentProduct->shipment;

        if ($shipment->order && $shipment->order->positions) {
            foreach ($shipment->order->positions as $position) {
                if ($shipmentProduct->product_id == $position->product_id) {
                    $position->shipped_crm -= $shipmentProduct->quantity;
                    $position->push();
                }
            }
        }
    }
}
