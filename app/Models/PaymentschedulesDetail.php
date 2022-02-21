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
        'paymentschedule_id', 'contract_id', 'item', 'worked_days', 'job', 'job_level', 'salary', 'seniority_bonus_percentage', 'solidary', 'common_risk', 'afp_commission', 'retirement', 'solidary_national', 'solidary_employer', 'housing_employer', 'health', 'rc_iva_amount', 'faults_quantity'
    ];
}
