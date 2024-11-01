<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Loss extends Model
{
    use HasFactory, SoftDeletes;

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'name' => 'string',
        'description' => 'string',
        'moment' => 'datetime',
        'sum' => 'float',
        'ms_id' => 'string'
    ];

    protected $fillable = [
        'created_at',
        'updated_at',
        'name',
        'description',
        'moment',
        'sum',
        'ms_id'
    ];

    public function positions()
    {
        return $this->hasMany(LossPosition::class);
    }

    public function getCreatedAtAttribute($value)
    {
        return Carbon::parse($value)->format('d-m-Y H:i');
    }

    public function getUpdatedAtAttribute($value)
    {
        return Carbon::parse($value)->format('d-m-Y H:i');
    }

    public function getMomentAttribute($value)
    {
        return Carbon::parse($value)->format('d-m-Y H:i');
    }
}
