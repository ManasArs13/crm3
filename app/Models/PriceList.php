<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PriceList extends Model
{
    use HasFactory;

    protected $guarded = false;

    public function positions()
    {
        return $this->hasMany(PriceListPosition::class);
    }
}
