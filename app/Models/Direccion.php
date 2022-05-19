<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Direccion extends Model
{
    use HasFactory;

    protected $table = 'direcciones';

    public function unidades_administrativas(){
        return $this->hasMany(unidad::class, 'direccion_id');
    }

    public function type(){
        return $this->belongsTo(DireccionesTipo::class, 'direcciones_tipo_id');
    }
}
