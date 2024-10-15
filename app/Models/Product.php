<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    public const PRODUCTS = "продукция";
    public const MATERIAL = "материал";
    public const NOT_SELECTED = "не выбрано";
    public const CONCRETE = "бетон";
    public const BLOCK = "блок";
    public const DELIVERY = "доставка";

    protected $guarded = false;

    public function color()
    {
        return $this->hasOne(Color::class, 'id', 'color_id');
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function orders()
    {
        return $this->hasMany(OrderPosition::class);
    }

    public function price_lists()
    {
        return $this->hasMany(PriceListPosition::class);
    }

    public function shipments()
    {
        return $this->hasMany(ShipmentProduct::class);
    }

    public function supplies()
    {
        return $this->hasMany(SupplyPosition::class);
    }

    public function tech_charts()
    {
        return
            $this->belongsToMany(TechChart::class, 'tech_chart_materials');
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
