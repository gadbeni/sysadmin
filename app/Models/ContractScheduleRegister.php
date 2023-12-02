<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ContractScheduleRegister extends Model
{
    use HasFactory, SoftDeletes;
    protected $dates = ['deleted_at'];
    protected $fillable = [
        'user_id', 'contract_schedule_id', 'date', 'time', 'type'
    ];

    public function user(){
        return $this->belongsTo(User::class, 'user_id');
    }

    public function contract_schedule(){
        return $this->belongsTo(ContractSchedule::class, 'contract_schedule_id');
    }
}
