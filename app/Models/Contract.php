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
        'current_program_id',
        'cargo_id',
        'job_id',
        'job_description',
        'salary',
        'bonus',
        'job_location',
        'direccion_administrativa_id',
        'unidad_administrativa_id',
        'procedure_type_id',
        'user_id',
        'signature_id',
        'signature_alt_id',
        'signature_date',
        'code',
        'details_work',
        'preventive_number',
        'organizational_source',
        'requested_by',
        'start',
        'finish',
        'date_invitation',
        'date_limit_invitation',
        'date_response',
        'date_statement',
        'date_memo',
        'workers_memo',
        'workers_memo_alt',
        'date_memo_res',
        'date_note',
        'date_report',
        'table_report',
        'details_report',
        'date_autorization',
        'certification_poa',
        'certification_pac',
        'date_presentation',
        'name_job_alt',
        'work_location',
        'documents_contract',
        'status',
    ];

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function person(){
        return $this->belongsTo(Person::class);
    }

    public function program(){
        return $this->belongsTo(Program::class);
    }

    public function current_program(){
        return $this->belongsTo(Program::class, 'current_program_id');
    }

    public function type(){
        return $this->belongsTo(ProcedureType::class, 'procedure_type_id');
    }

    public function direccion_administrativa(){
        return $this->belongsTo(Direccion::class, 'direccion_administrativa_id', 'id');
    }

    public function unidad_administrativa(){
        return $this->belongsTo(Unidad::class, 'unidad_administrativa_id');
    }

    public function cargo(){
        return $this->belongsTo(Cargo::class, 'cargo_id', 'ID');
    }

    public function job(){
        return $this->belongsTo(Job::class);
    }

    public function finished(){
        return $this->hasOne(ContractsFinished::class, 'contract_id');
    }

    public function rotations(){
        return $this->hasMany(PersonRotation::class);
    }

    public function signature(){
        return $this->belongsTo(Signature::class, 'signature_id', 'id');
    }
    
    public function signature_alt(){
        return $this->belongsTo(Signature::class, 'signature_alt_id', 'id');
    }

    public function paymentschedules_details(){
        return $this->hasMany(PaymentschedulesDetail::class);
    }

    public function addendums(){
        return $this->hasMany(Addendum::class);
    }

    public function alternate_job(){
        return $this->hasMany(ContractsAlternatesJob::class);
    }

    public function ratifications(){
        return $this->hasMany(ContractRatification::class);
    }

    public function transfers(){
        return $this->hasMany(ContractsTransfer::class);
    }

    public function promotions(){
        return $this->hasMany(ContractsPromotion::class);
    }

    public function jobs(){
        return $this->hasMany(ContractsJob::class);
    }

    public function files(){
        return $this->hasMany(ContractsFile::class);
    }

    public function schedules(){
        return $this->hasMany(ContractSchedule::class);
    }
}
