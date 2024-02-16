<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Unidad extends Model
{
    use HasFactory, SoftDeletes;
    protected $dates = ['deleted_at'];
    protected $table = 'unidades';

    public function direccion_administrativa(){
        return $this->belongsTo(Direccion::class, 'direccion_id', 'id');
    }

    public function scopeActivo($query){
        return $query->where('estado', 1);
    }
}
