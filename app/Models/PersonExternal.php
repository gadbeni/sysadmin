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
        'person_external_type_id', 'person_id', 'city_id', 'full_name', 'birthday', 'gender', 'job', 'family', 'ci_nit', 'phone', 'address', 'email', 'observations', 'docs'
    ];
}
