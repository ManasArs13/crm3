<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Errors extends Model
{
    use HasFactory;

    protected $fillable = ['status', 'allowed', 'type_id', 'link', 'description', 'responsible_user', 'user_description', 'tab_id', 'created_at', 'updated_at'];

    public function type()
    {
        return $this->belongsTo(ErrorTypes::class, 'type_id');
    }

    public function responsible()
    {
        return $this->belongsTo(User::class, 'responsible_user');
    }
}
