<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShipmentProduct extends Model
{
    use HasFactory;

    protected $fillable =[
        'shipment_id',
        'quantity',
        'product_id',
    ];

    /**
     * @return BelongsTo
     */
    public function shipment()
    {
        return $this->belongsTo(Shipment::class, 'shipment_id');
    }
}
