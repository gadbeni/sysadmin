<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SeniorityBonusPerson extends Model
{
    use HasFactory;

    public function type(){
        return $this->belongsTo(SeniorityBonusType::class, 'seniority_bonus_type_id');
    }
}
