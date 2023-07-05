<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ContractsPromotion extends Model
{
    use HasFactory, SoftDeletes;
    protected $dates = ['deleted_at'];
    protected $fillable = [
        'user_id',
        'contract_id',
        'previus_contract_id',
        'code',
        'date',
        'observations',
    ];

    public function previus_contract(){
        return $this->belongsTo(Contract::class, 'previus_contract_id');
    }
}
