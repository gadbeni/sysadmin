<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChecksBeneficiary extends Model
{
    use HasFactory;

    protected $fillable = [
        'social_security_type_id', 'full_name', 'dni', 'details'
    ];

    public function type(){
        return $this->belongsTo(SocialSecurityType::class, 'social_security_type_id');
    }
}
