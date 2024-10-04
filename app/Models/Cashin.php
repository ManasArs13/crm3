<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Cashin extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = false;

    public function contact()
    {
        return $this->hasOne(Contact::class, 'id', 'contact_id');
    }
}
