<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PayrollPayment extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'planilla_haber_id', 'date_payment_afp', 'fpc_number', 'payment_id', 'penalty_payment', 'date_payment_cc', 'gtc_number', 'recipe_number', 'deposit_number', 'check_id', 'penalty_check'
    ];

    public function user(){
        return $this->belongsTo(User::class);
    }
}
