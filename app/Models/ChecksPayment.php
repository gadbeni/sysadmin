<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChecksPayment extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'planilla_haber_id', 'number', 'amount', 'checks_beneficiary_id', 'date_print', 'observations'
    ];

    public function user(){
        return $this->belongsTo(User::class);
    }
}
