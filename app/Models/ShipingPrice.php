<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShipingPrice extends Model
{
    use HasFactory;

    protected $guarded = false;

    public function transport_type()
    {
        return $this->hasOne(TransportType::class, 'id', 'transport_type_id');
    }
}
