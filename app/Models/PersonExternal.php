<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PersonExternal extends Model
{
    use HasFactory, SoftDeletes;
    protected $dates = ['deleted_at'];
    protected $fillable = [
        'person_external_type_id', 'person_id', 'city_id', 'full_name', 'ci_nit', 'number_acount', 'birthday', 'gender', 'job', 'family', 'phone', 'address', 'email', 'observations', 'docs'
    ];
}
