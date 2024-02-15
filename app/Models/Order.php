<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $guarded = false;

    public function delivery()
    {
        return $this->hasOne(Delivery::class, 'id', 'delivery_id');
    }

    public function transport_type()
    {
        return $this->hasOne(TransportType::class, 'id', 'transport_type_id');
    }

    public function transport()
    {
        return $this->hasOne(Transport::class, 'id', 'transport_id');
    }

    public function status()
    {
        return $this->belongsTo(Status::class);
    }

    public function contact()
    {
        return $this->hasOne(Contact::class, 'id', 'contact_id');
    }

    public function positions()
    {
        return $this->hasMany(OrderPosition::class);
    }

    /**
     * @return HasOneThrough
     */
    public function orderAmo()
    {
        return $this->hasOneThrough(OrderAmo::class, OrderAmoOrder::class, 'order_id', 'id', 'id', 'order_amo_id');
    }
}
