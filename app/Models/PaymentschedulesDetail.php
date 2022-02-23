<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PaymentschedulesDetail extends Model
{
    use HasFactory, SoftDeletes;
    protected $dates = ['deleted_at'];

    protected $fillable = [
        'paymentschedule_id',
        'contract_id',
        'item',
        'worked_days',
        'salary',
        'partial_salary',
        'job',
        'job_level',
        'seniority_bonus_percentage',
        'seniority_bonus_amount',
        'solidary',
        'common_risk',
        'afp_commission',
        'retirement',
        'solidary_national',
        'labor_total',
        'solidary_employer',
        'housing_employer',
        'health',
        'rc_iva_amount',
        'faults_quantity',
        'faults_amount',
        'liquid_payable'
    ];

    public function contract(){
        return $this->belongsTo(Contract::class, 'contract_id');
    }
}