<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PersonAssetSignature extends Model
{
    use HasFactory, SoftDeletes;
    protected $dates = ['deleted_at'];
    protected $fillable = [
        'person_asset_id',
        'contract_id',
    ];

    public function contract(){
        return $this->belongsTo(Contract::class, 'contract_id');
    }
}
