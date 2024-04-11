<?php

namespace App\Observers;

use App\Helpers\Help;
use App\Models\Product;

class ProductObserver
{
    /**
     * Handle the Product "updated" event.
     */
    public function updated(Product $product): void
    {
        if ($product->type == Product::PRODUCTS) {
            Product::query()->where('id', $product->id)->update([
                'consumption_year' => Help::consumption($product->id),
            ]);
        } else {
            Product::query()->where('id', $product->id)->update([
                'consumption_year' => null
            ]);
        }
    }
}
