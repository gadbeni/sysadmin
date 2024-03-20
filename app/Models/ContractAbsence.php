<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ContractAbsence extends Model
{
    use HasFactory, SoftDeletes;
    protected $dates = ['deleted_at'];

    protected $fillable = [
        'contract_id',
        'user_id',
        'period_id',
        'quantity',
        'date_register',
        'observations'
    ];

    public function period(){
        return $this->belongsTo(Period::class);
    }
}
