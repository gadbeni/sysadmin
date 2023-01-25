<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ContractRatification extends Model
{
    use HasFactory, SoftDeletes;
    protected $dates = ['deleted_at'];

    protected $fillable = [
        'contract_id',
        'user_id',
        'date',
        'observations',
    ];

    public function contract(){
        return $this->belongsTo(Contract::class);
    }

    public function user(){
        return $this->belongsTo(User::class);
    }
}
