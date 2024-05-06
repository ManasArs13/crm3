<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;

class ContactAmo extends Model
{
    use HasFactory;

    protected $fillable = [
        'id'
    ];

    public function contact(): HasOneThrough
    {
        return $this->hasOneThrough(ContactAmo::class, ContactAmoContact::class, 'contact_amo_id', 'id', 'id', 'contact_id');
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
