<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CashiersPayment extends Model
{
    use HasFactory;
    protected $fillable = [
        'cashier_id', 'planilla_haber_id', 'aguinaldo_id', 'amount', 'description', 'observations', 'stipend_id'
    ];

    public function cashier(){
        return $this->belongsTo(Cashier::class);
    }

    public function aguinaldo(){
        return $this->belongsTo(Aguinaldo::class);
    }

    public function stipend(){
        return $this->belongsTo(Stipend::class);
    }


    public function deletes(){
        return $this->hasOne(CashiersPaymentsDelete::class);
    }
}
