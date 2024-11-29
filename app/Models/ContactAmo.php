<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;

class ContactAmo extends Model
{
    use HasFactory;

    protected $guarded = ['created_at', 'updated_at'];

    public function amo_order()
    {
        return $this->hasMany(OrderAmo::class, 'contact_amo_id');
    }

    public function contact(): HasOneThrough
    {
        return $this->hasOneThrough(Contact::class, ContactAmoContact::class, 'contact_amo_id', 'id', 'id', 'contact_id');
    }

    public function manager()
    {
        return $this->hasOne(Manager::class, 'id', 'manager_id');
    }

    public function getCreatedAtAttribute($value)
    {
        return Carbon::parse($value)->format('d-m-Y H:i');
    }

    public function getUpdatedAtAttribute($value)
    {
        return Carbon::parse($value)->format('d-m-Y H:i');
    }

    public function contact_type()
    {
        return $this->hasOne(ContactTypeAmo::class, 'id', 'contact_type_amo_id');
    }
}
