<?php

namespace App\Models;

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
}
