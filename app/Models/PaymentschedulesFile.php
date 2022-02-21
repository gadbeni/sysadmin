<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PaymentschedulesFile extends Model
{
    use HasFactory, SoftDeletes;
    protected $dates = ['deleted_at'];

    protected $fillable = [
        'user_id', 'direccion_administrativa_id', 'period_id', 'procedure_type_id', 'type', 'observations', 'url', 'status'
    ];

    public function details(){
        return $this->hasMany(PaymentschedulesFilesDetails::class, 'paymentschedules_file_id');
    }
}
