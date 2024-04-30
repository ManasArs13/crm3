<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IncomingProduct extends Model
{
    use HasFactory;

    protected $fillable = ['id'];

    protected $table = 'incoming_products';

    public function incoming()
    {
        return $this->belongsTo(Supply::class, 'incoming_id');
    }

    public function products()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
}
