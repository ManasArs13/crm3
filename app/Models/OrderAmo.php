<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;

class OrderAmo extends Model
{
    use HasFactory;

    protected $AllColumns = [
        "id",
        "name",
        'status_amo_id',
        'contact_amo_id',
        'price',
        'comment',
        'is_exist',
        'order_link',
        'order_id',
        'created_at',
        'updated_at',
        'manager_id',
        'is_success'
    ];

    protected $defaultColumn = [
        "id",
        "name",
        'status_amo_id',
        'contact_amo_id',
        'price',
        'comment',
        'is_exist',
        'order_link',
        'order_id',
        'created_at',
        'updated_at',
        'manager_id',
        'is_success'
    ];

    protected $fillable = [
        '*'
    ];

    public function getColumns()
    {
        return $this->AllColumns;
    }

    public function getDefaultColumn()
    {
        return $this->defaultColumn;
    }

    // Relationship

    public function manager()
    {
        return $this->hasOne(Manager::class, 'id', 'manager_id');
    }

    public function contact_amo()
    {
        return $this->belongsTo(ContactAmo::class);
    }

    public function status_amo()
    {
        return $this->belongsTo(StatusAmo::class);
    }

    public function orderMs(): HasOneThrough
    {
        return $this->hasOneThrough(
            Order::class,
            OrderAmoOrder::class,
            'order_amo_id',
            'id',
            'ms_id',
            'order_id'
        );
    }
}
