<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EnterPosition extends Model
{
    use HasFactory;

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'product_id' => 'int',
        'enter_id' => 'int',
        'quantity' => 'float',
        'price' => 'int',
        'sum' => 'int',
        'ms_id' => 'string'
    ];

    protected $fillable = [
        'created_at',
        'updated_at',
        'product_id',
        'enter_id',
        'quantity',
        'price',
        'sum',
        'ms_id'
    ];

    public function product()
    {
        return $this->hasOne(Product::class, 'id', 'product_id');
    }

    public function enter()
    {
        return $this->hasOne(Enter::class, 'id', 'enter_id');
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
