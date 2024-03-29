<?php

namespace App\Models;

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

    protected $guarded = false;

    public function color()
    {
        return $this->hasOne(Color::class, 'id', 'color_id');
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
