<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Shifts extends Model
{
    use HasFactory;
    protected $fillable = ['transport_id', 'description', 'start_shift', 'end_shift'];
    public $timestamps = false;

    public function transport()
    {
        return $this->belongsTo(Transport::class);
    }
}
