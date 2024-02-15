<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SupplyPosition extends Model
{
    use HasFactory;

    protected $fillable = ['id'];

    protected $table = 'supply_positions';

    public function supply()
    {
        return $this->belongsTo(Supply::class, 'supply_id');
    }

    public function products()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
}
