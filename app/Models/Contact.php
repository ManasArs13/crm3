<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;
use Illuminate\Database\Eloquent\SoftDeletes;

class Contact extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = false;

    public function contactAmo(): HasOneThrough
    {
        return $this->hasOneThrough(ContactAmo::class, ContactAmoContact::class, 'contact_ms_id', 'id', 'id', 'contact_id');
    }

    public function getCreatedAtAttribute($value)
    {
        return Carbon::parse($value)->format('d-m-Y H:i');
    }

    public function getUpdatedAtAttribute($value)
    {
        return Carbon::parse($value)->format('d-m-Y H:i');
    }

    public function contact_categories()
    {
        return $this->belongsToMany(ContactCategory::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function shipments()
    {
        return $this->hasMany(Shipment::class);
    }

    public function manager()
    {
        return $this->hasOne(Manager::class, 'id', 'manager_id');
    }

    public function transport()
    {
        return $this->hasOne(Transport::class);
    }
}
