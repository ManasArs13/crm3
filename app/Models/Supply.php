<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Supply extends Model
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
            $this->belongsToMany(Product::class, 'supply_positions', 'supply_id', 'product_id')
                 ->withPivot('id', 'quantity', 'price');
    }
}
