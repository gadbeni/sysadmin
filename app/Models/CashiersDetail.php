<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CashiersDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'cashier_id', 'cash_value', 'quantity'
    ];
}
