<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Paymentschedule extends Model
{
    use HasFactory, SoftDeletes;
    protected $dates = ['deleted_at'];

    protected $fillable = [
        'user_id', 'direccion_administrativa_id', 'period_id', 'procedure_type_id', 'observations', 'status'
    ];

    public function details(){
        return $this->hasMany(PaymentschedulesDetail::class, 'paymentschedules_id');
    }
}
