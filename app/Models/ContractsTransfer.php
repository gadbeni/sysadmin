<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ContractsTransfer extends Model
{
    use HasFactory, SoftDeletes;
    protected $dates = ['deleted_at'];
    protected $fillable = [
        'user_id',
        'contract_id',
        'previus_contract_id',
        'date',
        'observations',
    ];

    public function previus_contract(){
        return $this->belongsTo(Contract::class, 'previus_contract_id');
    }
}
