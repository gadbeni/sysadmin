<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CashiersPayment extends Model
{
    use HasFactory;
    protected $fillable = [
        'cashier_id', 'planilla_haber_id', 'amount', 'description', 'observations'
    ];

    public function cashier(){
        return $this->belongsTo(Cashier::class);
    }
}
