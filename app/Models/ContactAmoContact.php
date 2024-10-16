<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContactAmoContact extends Model
{
    use HasFactory;

    public $fillable = [
        "*"
    ];

    public function contact()
    {
        return $this->belongsTo(Contact::class );
    }

    public function AmoContact()
    {
        return $this->belongsTo(ContactAmo::class, 'contact_amo_id' );
    }
}
