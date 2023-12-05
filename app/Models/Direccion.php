<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Direccion extends Model
{
    use HasFactory, SoftDeletes;
    protected $dates = ['deleted_at'];
    protected $table = 'direcciones';

    public function unidades_administrativas(){
        return $this->hasMany(Unidad::class, 'direccion_id');
    }

    public function tipo(){
        return $this->belongsTo(DireccionesTipo::class, 'direcciones_tipo_id');
    }

    public function city(){
        return $this->belongsTo(City::class, 'city_id');
    }

    public function programs(){
        return $this->hasMany(Program::class, 'direccion_administrativa_id');
    }

    public function signatures(){
        return $this->hasMany(Signature::class, 'direccion_administrativa_id');
    }

    public function bonuses(){
        return $this->hasMany(Bonus::class, 'direccion_id');
    }
}
