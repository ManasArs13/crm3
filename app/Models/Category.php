<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    public const PRODUCTS = "продукция";
    public const MATERIAL = "материал";
    public const NOT_SELECTED = "не выбрано";
    public const CONCRETE = "бетон";
    public const BLOCK = "блок";

    protected $guarded = false;

    public function products()
    {
        return $this->hasMany(Product::class, 'id', 'category_id');
    }
}
