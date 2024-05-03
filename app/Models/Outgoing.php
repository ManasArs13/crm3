<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Outgoing extends Model
{
    use HasFactory;

    
    protected $fillable = ['id'];

    public function contact()
    {
        return $this->hasOne(Contact::class, 'id', 'contact_id');
    }

    public function products()
    {
        return 
            $this->belongsToMany(Product::class, 'outgoing_products', 'outgoing_id', 'product_id')
                 ->withPivot('id', 'quantity', 'price', 'summa');
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
