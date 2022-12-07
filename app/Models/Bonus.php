<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Bonus extends Model
{
    use HasFactory, SoftDeletes;
    protected $dates = ['deleted_at'];
    protected $fillable = [
        'user_id',
        'direccion_id',
        'year',
        'status',
    ];

    public function user(){
        return $this->belongsTo(User::class, 'user_id');
    }

    public function direccion(){
        return $this->belongsTo(Direccion::class, 'direccion_id');
    }

    public function details(){
        return $this->hasMany(BonusesDetail::class, 'bonus_id');
    }
}
