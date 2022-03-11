<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SeniorityBonusPerson extends Model
{
    use HasFactory, SoftDeletes;
    protected $dates = ['deleted_at'];

    protected $fillable = [
        'seniority_bonus_type_id', 'person_id', 'user_id', 'years', 'months', 'days', 'quantity', 'start', 'observations', 'status'
    ];

    public function type(){
        return $this->belongsTo(SeniorityBonusType::class, 'seniority_bonus_type_id');
    }
}
