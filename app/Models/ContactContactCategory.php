<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContactContactCategory extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'contact_category_id',
        'contact_id'
    ];
}
