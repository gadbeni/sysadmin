<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PersonAsset extends Model
{
    use HasFactory, SoftDeletes;
    protected $dates = ['deleted_at'];
    protected $fillable = [
        'person_id',
        'contract_id',
        'user_id',
        'code',
        'date',
        'observations'
    ];

    public function person(){
        return $this->belongsTo(Person::class, 'person_id');
    }

    public function contract(){
        return $this->belongsTo(Contract::class, 'contract_id');
    }

    public function user(){
        return $this->belongsTo(User::class, 'user_id');
    }

    public function details(){
        return $this->hasMany(PersonAssetDetail::class, 'person_asset_id');
    }

    public function signatures(){
        return $this->hasMany(PersonAssetSignature::class, 'person_asset_id');
    }
}
