<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PaymentschedulesBonus extends Model
{
    use HasFactory, SoftDeletes;
    protected $dates = ['deleted_at'];
    protected $fillable = [
        'user_id',
        'contract_id',
        'direccion_id',
        'procedure_type_id',
        'year',
        'salary',
        'amount',
        'status',
        'observations'
    ];

    public function user(){
        return $this->belongsTo(User::class, 'user_id');
    }

    public function contract(){
        return $this->belongsTo(User::class, 'contract_id');
    }

    public function direccion(){
        return $this->belongsTo(User::class, 'direccion_id');
    }

    public function procedure_type(){
        return $this->belongsTo(User::class, 'procedure_type_id');
    }
}
