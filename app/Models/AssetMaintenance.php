<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AssetMaintenance extends Model
{
    use HasFactory, SoftDeletes;
    protected $dates = ['deleted_at'];
    protected $fillable = [
        'asset_id',
        'technical_id',
        'destiny_id',
        'supervisor_id',
        'direccion_id',
        'type',
        'code',
        'date_start',
        'date_finish',
        'income_status',
        'origin',
        'reference',
        'report',
        'observations',
        'images',
        'work_place'
    ];

    public function asset(){
        return $this->belongsTo(Asset::class, 'asset_id');
    }

    public function technical(){
        return $this->belongsTo(Contract::class, 'technical_id');
    }

    public function destiny(){
        return $this->belongsTo(Contract::class, 'destiny_id');
    }

    public function supervisor(){
        return $this->belongsTo(Contract::class, 'supervisor_id');
    }

    public function direccion(){
        return $this->belongsTo(Direccion::class, 'direccion_id');
    }

    public function details(){
        return $this->hasMany(AssetMaintenanceDetail::class, 'asset_maintenance_id');
    }
}
