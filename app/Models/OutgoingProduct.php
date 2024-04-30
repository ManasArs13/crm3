<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OutgoingProduct extends Model
{
    use HasFactory;

    protected $fillable = ['id'];

    protected $table = 'outgoing_products';

    public function outgoing()
    {
        return $this->belongsTo(Supply::class, 'outgoing_id');
    }

    public function products()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
}
