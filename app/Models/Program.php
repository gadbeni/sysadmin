<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Program extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $dates = ['deleted_at'];
    protected $fillable = [
        'direccion_administrativa_id', 'unidad_administrativa_id', 'procedure_type_id', 'name', 'description', 'type', 'class', 'number', 'programatic_category', 'amount', 'year', 'start', 'finish'
    ];

    public function direccion_administrativa(){
        return $this->belongsTo(Direccion::class, 'direccion_administrativa_id');
    }

    public function direcciones_administrativas(){
        return $this->belongsToMany(Direccion::class, 'direccion_program', 'program_id', 'direccion_id');
    }

    public function unidad_administrativa(){
        return $this->belongsTo(Unidad::class, 'unidad_administrativa_id');
    }

    public function procedure_type(){
        return $this->belongsTo(ProcedureType::class, 'procedure_type_id');
    }

    public function contracts(){
        return $this->hasMany(Contract::class, 'program_id');
    }
}
