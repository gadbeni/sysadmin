<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DireccionesTipo extends Model
{
    use HasFactory;

    protected $table = 'direcciones_tipos';

    public function direcciones_administrativas(){
        return $this->hasMany(Direccion::class, 'direcciones_tipo_id', 'id');
    }
}
