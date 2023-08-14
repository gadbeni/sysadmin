<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ContractsExternal extends Model
{
    use HasFactory, SoftDeletes;

    protected $dates = ['deleted_at'];
    protected $fillable = [
        'user_id',
        'person_id',
        'direccion_id',
        'procedure_type_id',
        'start',
        'finish',
        'salary',
        'job_location',
        'observations'
    ];
}
