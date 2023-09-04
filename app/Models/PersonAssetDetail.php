<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PersonAssetDetail extends Model
{
    use HasFactory, SoftDeletes;
    protected $dates = ['deleted_at'];
    protected $fillable = [
        'person_asset_id',
        'asset_id',
        'office_id',
        'observations',
        'status',
        'active'
    ];

    public function asset(){
        return $this->belongsTo(Asset::class, 'asset_id');
    }

    public function person_asset(){
        return $this->belongsTo(PersonAsset::class, 'person_asset_id');
    }
}
