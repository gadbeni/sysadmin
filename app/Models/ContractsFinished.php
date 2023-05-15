<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ContractsFinished extends Model
{
    use HasFactory, SoftDeletes;
    protected $dates = ['deleted_at'];

    protected $fillable = [
        'contract_id',
        'user_id',
        'code',
        'previus_date',
        'technical_report',
        'nci',
        'legal_report',
        'details',
        'observations'
    ];

    public function contract(){
        return $this->belongsTo(Contract::class, 'contract_id');
    }
}
