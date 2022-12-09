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
        'salary_1',
        'salary_2',
        'salary_3',
        'days',
        'amount',
        'status',
        'observations'
    ];

    public function contract(){
        return $this->belongsTo(Contract::class, 'contract_id');
    }

    public function procedure_type(){
        return $this->belongsTo(ProcedureType::class, 'procedure_type_id');
    }
}
