<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Person extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $dates = ['deleted_at'];

    protected $fillable = [
        'city_id', 'user_id', 'first_name', 'last_name', 'ci', 'profession', 'issued', 'phone', 'address', 'email', 'afp', 'afp_status', 'retired', 'gender', 'birthday', 'civil_status', 'nua_cua'
    ];

    public function contracts(){
        return $this->hasMany(Contract::class);
    }

    public function seniority_bonus(){
        return $this->hasMany(SeniorityBonusPerson::class, 'person_id');
    }
}
