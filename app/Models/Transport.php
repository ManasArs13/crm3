<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transport extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $fillable = ['name', 'description', 'tonnage', 'contact_id', 'car_number', 'driver', 'phone', 'type_id', 'start_shift', 'end_shift'];


    public function shifts()
    {
        return $this->hasMany(Shifts::class);
    }

    public function type()
    {
        return $this->belongsTo(TransportType::class, 'type_id');
    }

    public function contact()
    {
        return $this->belongsTo(Contact::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function shipments()
    {
        return $this->hasMany(Shipment::class);
    }

    public function getCreatedAtAttribute($value)
    {
        return Carbon::parse($value)->format('d-m-Y H:i');
    }

    public function getUpdatedAtAttribute($value)
    {
        return Carbon::parse($value)->format('d-m-Y H:i');
    }
}
