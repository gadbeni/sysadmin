<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ContractSchedule extends Model
{
    use HasFactory, SoftDeletes;
    protected $dates = ['deleted_at'];
    protected $fillable = [
        'user_id', 'schedule_id', 'contract_id', 'start', 'finish'
    ];

    public function contract(){
        return $this->belongsTo(Contract::class, 'contract_id');
    }

    public function schedule(){
        return $this->belongsTo(Schedule::class, 'schedule_id')->withTrashed();
    }
}
