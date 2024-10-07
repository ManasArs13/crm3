<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Payment extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = false;

    public function contact()
    {
        return $this->hasOne(Contact::class, 'id', 'contact_id');
    }

    public function getDateMomentAttribute($value)
    {
        return Carbon::parse($value)->format('d-m-Y H:i');
    }

    public function getCreatedAtAttribute($value)
    {
        return Carbon::parse($value)->format('d-m-Y H:i');
    }

    public function getUpdatedAtAttribute($value)
    {
        return Carbon::parse($value)->format('d-m-Y H:i');
    }

    public function getSumAttribute($value)
    {
        return $value / 100;
    }

    public function getTypeAttribute($value)
    {
        $type = $value;

        switch ($value) {
            case 'paymentin':
                $type = 'входящий платеж';
                break;

            case 'paymentout':
                $type = 'исходящий платёж';
                break;

            case 'cashin':
                $type = 'приходный ордер';
                break;

            case 'cashout':
                $type = 'расходный ордер';
                break;
        }

        return $type;
    }

    public function getOperationAttribute($value)
    {
        $type = $value;

        switch ($value) {
            case 'supply':
                $type = 'приход';
                break;

            case 'demand':
                $type = 'отгрузка';
                break;

            case 'customerorder':
                $type = 'заказ';
                break;

            case 'invoiceout':
                $type = 'выставление счета';
                break;
        }

        return $type;
    }
}
