<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TechProcessProduct extends Model
{
    use HasFactory;

    protected $fillable = ['id'];

    protected $guarded = false;

    public function processing()
    {
        return $this->belongsTo(TechProcess::class, 'processing_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
