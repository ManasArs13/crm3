<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContactTypeAmo extends Model
{
    use HasFactory;

    protected $guarded = ['created_at', 'updated_at'];

    public function contact_amo()
    {
        return $this->hasMany(ContactTypeAmo::class, 'contact_type_amo_id');
    }
}
