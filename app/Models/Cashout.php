<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Cashout extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = false;

    public function contact()
    {
        return $this->hasOne(Contact::class, 'id', 'contact_id');
    }

    public function getDateMomentAttribute($value)
    {
        return Carbon::parse($value)->format('d-m-Y H:i');
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
