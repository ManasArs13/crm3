<?php

use App\Models\Product;
use App\Models\ShipmentsProducts;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;

if (!function_exists('consumption')){

    function consumption ($id){
        $product = Product::query()->where('id',$id)->first();
        $shipments=ShipmentsProducts::query()
            ->where('product_id',$product->id )
            ->whereHas('shipment', function (Builder $query) {
                $query->where('created_at', '>', Carbon::now()->subYear());
            })
            ->get();
        $sumYear = 0;
        if ($shipments){
            foreach ($shipments as $shipment){
                $sumYear +=  $shipment->quantity;
            }
        }
       return  $sumYear ;
     }

 }
