<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AttendancePermit extends Model
{
    use HasFactory, SoftDeletes;
    protected $dates = ['deleted_at'];
    protected $fillable = [
        'user_id', 'attendance_permit_type_id', 'category', 'date', 'start', 'finish', 'time_start', 'time_finish', 'purpose', 'justification', 'type_transport', 'file', 'observations', 'status'
    ];

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function type(){
        return $this->belongsTo(AttendancePermitType::class, 'attendance_permit_type_id')->withTrashed();
    }

    public function details(){
        return $this->hasMany(AttendancePermitContract::class);
    }
}
