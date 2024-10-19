<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Manager extends Model
{
    use HasFactory;

    protected $guarded = false;

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function order_amos()
    {
        return $this->hasMany(OrderAmo::class);
    }

    public function contacts()
    {
        return $this->hasMany(Contact::class);
    }

    public function contactAmos()
    {
        return $this->hasMany(ContactAmo::class);
    }

    public function calls()
    {
        return $this->hasMany(Call::class);
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
