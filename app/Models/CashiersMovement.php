<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CashiersMovement extends Model
{
    use HasFactory;
    protected $fillable = [
        'cashier_id', 'cashier_id_from', 'user_id', 'amount', 'description', 'type'
    ];

    public function cashier(){
        return $this->belongsTo(Cashier::class, 'cashier_id');
    }

    public function cashier_from(){
        return $this->belongsTo(Cashier::class, 'cashier_id_from');
    }
}
