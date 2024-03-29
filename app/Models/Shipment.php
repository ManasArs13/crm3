<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Shipment extends Model
{
    use HasFactory;

    public const PAID = 'Оплачен';
    public const NOT_PAID = 'Не оплачен';
    public const APPOINTED = 'Назначен';

    protected $guarded = false;

    /**
     * @return BelongsTo
     */
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function products()
    {
        return $this->hasMany(ShipmentProduct::class);
    }

    public function status()
    {
        return $this->belongsTo(Status::class);
    }

    public function contact()
    {
        return $this->hasManyThrough(Contact::class, Order::class,  'contact_id','order_id', 'id', 'id');
    }
}
