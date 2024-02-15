<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Shipment extends Model
{
    use HasFactory;

    public const PAID = 'Оплачен';
    public const NOT_PAID = 'Не оплачен';
    public const APPOINTED = 'Назначен';

    protected $fillable =[
        'id',
        'order_id',

    ];
    protected $guarded = false;

    /**
     * @return BelongsTo
     */
    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
