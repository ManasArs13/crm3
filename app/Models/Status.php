<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Status extends Model
{
    use HasFactory;

    protected $guarded = false;

    public function status_amo()
    {
        return $this->belongsTo(StatusAmo::class);
    }
}