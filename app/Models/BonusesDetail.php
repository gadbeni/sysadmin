<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BonusesDetail extends Model
{
    use HasFactory, SoftDeletes;
    protected $dates = ['deleted_at'];
    protected $fillable = [
        'bonus_id',
        'contract_id',
        'procedure_type_id',
        'partial_salary_1',
        'seniority_bonus_1',
        'partial_salary_2',
        'seniority_bonus_2',
        'partial_salary_3',
        'seniority_bonus_3',
        'days',
        'status',
        'observations'
    ];

    public function contract(){
        return $this->belongsTo(Contract::class, 'contract_id');
    }

    public function procedure_type(){
        return $this->belongsTo(ProcedureType::class, 'procedure_type_id');
    }

    public function bonus(){
        return $this->belongsTo(Bonus::class, 'bonus_id');
    }
}
