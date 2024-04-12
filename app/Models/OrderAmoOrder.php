<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;

class OrderAmoOrder extends Model
{
    use HasFactory;

    public $fillable = [
        "*"
    ];

    // public function orderMs()
    // {
    //     return $this->belongsTo(Order::class );
    // }

    public function orderAmo()
    {
        return $this->belongsTo(OrderAmo::class );
    }


    public function contact_amo()
    {
        return $this->belongsTo(ContactAmo::class);
    }

    public function status_amo()
    {
        return $this->belongsTo(StatusAmo::class);
    }

    public function orderMs():HasOneThrough
    {
        return $this->hasOneThrough(Order::class, OrderAmo::class, 'order_amo_id', 'id', 'id', 'order_id');
    }
}
