<?php

namespace App\Helpers;

use App\Models\Product;
use App\Models\ShipmentProduct;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;

class Help
{
    public static function consumption($num)
    {
        $product = Product::query()->where('id', $num)->first();
        $shipments = ShipmentProduct::query()
            ->where('product_id', $product->id)
            ->whereHas('shipment', function (Builder $query) {
                $query->where('created_at', '>', Carbon::now()->subYear());
            })
            ->get();
        $sumYear = 0;
        if ($shipments) {
            foreach ($shipments as $shipment) {
                $sumYear +=  $shipment->quantity;
            }
        }
        return  $sumYear;
    }
}
