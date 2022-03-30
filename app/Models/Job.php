<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Job extends Model
{
    use HasFactory, SoftDeletes;
    protected $dates = ['deleted_at'];

    public function direccion_administrativa(){
        return $this->belongsTo(DireccionAdministrativa::class, 'direccion_administrativa_id', 'ID');
    }

    public function contract(){
        return $this->hasOne(Contract::class);
    }
}
