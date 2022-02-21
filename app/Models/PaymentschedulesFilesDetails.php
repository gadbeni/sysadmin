<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PaymentschedulesFilesDetails extends Model
{
    use HasFactory, SoftDeletes;
    protected $dates = ['deleted_at'];

    protected $fillable = [
        'paymentschedules_file_id', 'person_id', 'details'
    ];

    public function person(){
        return $this->belongsTo(Person::class, 'person_id');
    }
}
