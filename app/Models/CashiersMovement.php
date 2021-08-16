<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CashiersMovement extends Model
{
    use HasFactory;
    protected $fillable = [
        'cashier_id', 'user_id', 'amount', 'description', 'type'
    ];
}
