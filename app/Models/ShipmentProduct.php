<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShipmentProduct extends Model
{
    use HasFactory;

    protected $guarded = false;

    /**
     * @return BelongsTo
     */
    public function shipment()
    {
        return $this->hasOne(Shipment::class, 'shipment_id');
    }

    public function product()
    {
        return $this->hasOne(Product::class, 'id', 'product_id');
    }
}
