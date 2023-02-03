<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TcPersona extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'people';


    protected $dates = ['deleted_at'];

    protected $fillable = [
        'city_id', 'user_id', 'first_name', 'last_name', 'ci', 'profession', 'issued', 'phone', 'address', 'email', 'afp', 'afp_status', 'retired', 'cc', 'gender', 'birthday', 'civil_status', 'nua_cua'
    ];

}
