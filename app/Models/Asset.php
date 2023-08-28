<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Asset extends Model
{
    use HasFactory, SoftDeletes;
    protected $dates = ['deleted_at'];
    protected $fillable = [
        'assets_subcategory_id',
        'city_id',
        'user_id',
        'code',
        'code_siaf',
        'code_internal',
        'tags',
        'description',
        'initial_price',
        'current_price',
        'address',
        'location',
        'observations',
        'images',
        'status',
        'active'
    ];

    public function user(){
        return $this->belongsTo(User::class, 'user_id');
    }

    public function subcategory(){
        return $this->belongsTo(AssetsSubcategory::class, 'assets_subcategory_id');
    }

    public function city(){
        return $this->belongsTo(City::class, 'city_id');
    }

    public function assignments(){
        return $this->hasMany(PersonAssetDetails::class, 'asset_id');
    }
}
