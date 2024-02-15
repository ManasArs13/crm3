<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TechProcess extends Model
{
    use HasFactory;

    protected $fillable = ['id'];

    public function products()
    {
        return 
            $this->belongsToMany(Product::class, 'tech_process_products', 'tech_process_id', 'product_id')
                 ->withPivot('id', 'quantity');
    }

    public function materials()
    {
        return 
            $this->belongsToMany(Product::class, 'tech_process_materials', 'tech_process_id', 'product_id')
                 ->withPivot('id', 'quantity', 'quantity_norm');
    }

    public function tech_chart()
    {
        return $this->belongsTo(TechChart::class, 'tech_chart_id');
    }
}
