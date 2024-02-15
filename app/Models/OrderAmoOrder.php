<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderAmoOrder extends Model
{
    use HasFactory;

    public $fillable = [
        "*"
    ];

    public function orderMs()
    {
        return $this->belongsTo(Order::class );
    }

    public function orderAmo()
    {
        return $this->belongsTo(OrderAmo::class );
    }
}
