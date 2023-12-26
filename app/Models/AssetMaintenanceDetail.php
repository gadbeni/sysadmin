<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssetMaintenanceDetail extends Model
{
    use HasFactory;
    protected $fillable = [
        'asset_maintenance_id', 'asset_maintenance_type_id', 'observations'
    ];

    public function type(){
        return $this->belongsTo(assetMaintenanceType::class, 'asset_maintenance_type_id');
    }
}
