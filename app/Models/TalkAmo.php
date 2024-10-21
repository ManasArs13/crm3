<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TalkAmo extends Model
{
    use HasFactory;

    protected $fillable = [
        '*'
    ];

    public function employee()
    {
        return $this->hasOne(EmployeeAmo::class, 'id', 'employee_amo_id');
    }

    public function contact_amo()
    {
        return $this->belongsTo(ContactAmo::class);
    }

    public function contact_ms()
    {
        return $this->belongsTo(Contact::class);
    }
}
