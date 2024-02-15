<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TechProcessProduct extends Model
{
    use HasFactory;

    protected $fillable = ['id'];

    public function processing()
    {
        return $this->belongsTo(TechProcess::class, 'tech_process_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
