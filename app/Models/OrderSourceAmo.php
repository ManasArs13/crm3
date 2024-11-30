<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderSourceAmo extends Model
{
    use HasFactory;

    protected $guarded = ['created_at', 'updated_at'];

    public function order_amo()
    {
        return $this->hasMany(OrderAmo::class);
    }
}
