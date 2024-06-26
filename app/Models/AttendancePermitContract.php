<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AttendancePermitContract extends Model
{
    use HasFactory, SoftDeletes;
    protected $dates = ['deleted_at'];
    protected $fillable = [
        'attendance_permit_id', 'contract_id'
    ];

    public function attendance_permit(){
        return $this->belongsTo(AttendancePermit::class);
    }

    public function contract(){
        return $this->belongsTo(Contract::class);
    }
}
