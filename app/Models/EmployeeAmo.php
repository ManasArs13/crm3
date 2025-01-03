<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeeAmo extends Model
{
    use HasFactory;

    protected $guarded = false;

    public function calls()
    {
        return $this->hasMany(Call::class);
    }

    public function order_amo()
    {
        return $this->hasMany(OrderAmo::class);
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
