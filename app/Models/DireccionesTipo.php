<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DireccionesTipo extends Model
{
    use HasFactory, SoftDeletes;
    protected $dates = ['deleted_at'];
    protected $table = 'direcciones_tipos';

    public function direcciones_administrativas(){
        return $this->hasMany(Direccion::class, 'direcciones_tipo_id', 'id');
    }
}
