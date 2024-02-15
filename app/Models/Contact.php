<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;

class Contact extends Model
{
    use HasFactory;

    protected $guarded = false;

    public function contactAmo(): HasOneThrough
    {
        return $this->hasOneThrough(ContactAmo::class, ContactAmoContact::class, 'contact_ms_id', 'id', 'id', 'contact_id');
    }
}
