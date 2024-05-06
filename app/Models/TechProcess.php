<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TechProcess extends Model
{
    use HasFactory;

    protected $fillable = ['id'];

    public function products()
    {
        return 
            $this->belongsToMany(Product::class, 'tech_process_products', 'processing_id', 'product_id')
                 ->withPivot('id', 'quantity', 'sum');
    }

    public function materials()
    {
        return 
            $this->belongsToMany(Product::class, 'tech_process_materials', 'processing_id', 'product_id')
                 ->withPivot('id', 'quantity', 'quantity_norm', 'sum');
    }

    public function tech_chart()
    {
        return $this->belongsTo(TechChart::class, 'tech_chart_id');
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
