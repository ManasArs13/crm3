<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TechChartMaterial extends Model
{
    use HasFactory;

    protected $fillable = ['id'];

    protected $table = 'tech_chart_materials';

    public function tech_chart()
    {
        return $this->belongsTo(TechChart::class, 'tech_cart_id');
    }

    public function products()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
}
