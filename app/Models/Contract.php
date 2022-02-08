<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contract extends Model
{
    use HasFactory;

    protected $fillable = [
        'person_id',
        'program_id',
        'cargo_id',
        'job_id',
        'direccion_administrativa_id',
        'unidad_administrativa_id',
        'procedure_type_id',
        'user_id',
        'code',
        'details_work',
        'preventive_number',
        'organizational_source',
        'start',
        'finish',
        'date_invitation',
        'date_limit_invitation',
        'date_response',
        'date_statement',
        'date_memo',
        'workers_memo',
        'date_memo_res',
        'date_note',
        'date_report',
        'table_report',
        'details_report',
        'date_autorization',
        'certification_poa',
        'certification_pac',
        'date_presentation',
        'status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function person()
    {
        return $this->belongsTo(Person::class);
    }

    public function program()
    {
        return $this->belongsTo(Program::class);
    }

    public function type(){
        return $this->belongsTo(ProcedureType::class, 'procedure_type_id');
    }

    public function direccion_administrativa(){
        return $this->belongsTo(DireccionAdministrativa::class, 'direccion_administrativa_id', 'ID');
    }

    public function unidad_administrativa(){
        return $this->belongsTo(UnidadAdministrativa::class, 'unidad_administrativa_id', 'ID');
    }

    public function cargo(){
        return $this->belongsTo(Cargo::class, 'cargo_id', 'ID');
    }

    public function job(){
        return $this->belongsTo(Job::class, 'job_id');
    }
}
