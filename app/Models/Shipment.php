<?php

namespace App\Models;

use Carbon\Carbon;
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

    public function transport()
    {
        return $this->belongsTo(Transport::class);
    }

    public function delivery()
    {
        return $this->belongsTo(Delivery::class);
    }

    public function transport_type()
    {
        return $this->belongsTo(TransportType::class);
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
        return $this->hasOne(Contact::class, 'id', 'contact_id');
    }

    public function getCreatedAtAttribute($value)
    {
        return Carbon::parse($value)->format('d-m-Y H:i:s');
    }

    public function getUpdatedAtAttribute($value)
    {
        return Carbon::parse($value)->format('d-m-Y H:i:s');
    }
}
