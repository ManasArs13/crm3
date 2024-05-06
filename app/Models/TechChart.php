<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TechChart extends Model
{
    use HasFactory;

    public const CONCRETE = "Техкарта Бетон";
    public const PRESS = "Техкарта Пресс";

    protected $fillable = ['id'];

    public function materials()
    {
        return 
            $this->belongsToMany(Product::class, 'tech_chart_materials', 'tech_chart_id', 'product_id')
                 ->withPivot('id', 'quantity');
    }

    public function products()
    {
        return 
            $this->belongsToMany(Product::class, 'tech_chart_products', 'tech_chart_id', 'product_id')
                 ->withPivot('id', 'quantity');
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
