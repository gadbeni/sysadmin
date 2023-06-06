<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Addendum extends Model
{
    use HasFactory, SoftDeletes;
    protected $dates = ['deleted_at'];

    protected $fillable = [
        'contract_id', 'signature_id', 'applicant_id', 'code', 'nci_date', 'nci_code', 'certification_date', 'certification_code', 'start', 'finish', 'signature_date', 'request_date', 'legal_report_date', 'observations', 'status'
    ];

    public function contract(){
        return $this->belongsTo(Contract::class, 'contract_id');
    }

    public function signature(){
        return $this->belongsTo(Signature::class, 'signature_id');
    }

    public function applicant(){
        return $this->belongsTo(Contract::class, 'applicant_id');
    }
}
