<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;

class OrderAmo extends Model
{
    use HasFactory;

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

    public function source()
    {
        return $this->hasOne(OrderSourceAmo::class, 'id', 'source_id');
    }

    public function reason_for_closure()
    {
        return $this->hasOne(OrderReasonForClosureAmo::class, 'id', 'reason_for_closure_id');
    }

    public function employeeAmo()
    {
        return $this->hasOne(EmployeeAmo::class, 'id', 'employee_amo_id');
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
