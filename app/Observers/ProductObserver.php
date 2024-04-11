<?php

namespace App\Observers;

use App\Models\Product;

use function App\Helpers\consumption;

class ProductObserver
{

    /**
     * Handle the Product "updated" event.
     */
    public function updated(Product $product): void
    {
        if ($product->type == Product::PRODUCTS) {
            Product::query()->where('id', $product->id)->update([
                'consumption_year' => consumption($product->id),
            ]);
        } else {
            Product::query()->where('id', $product->id)->update([
                'consumption_year' => null
            ]);
        }
    }
}
