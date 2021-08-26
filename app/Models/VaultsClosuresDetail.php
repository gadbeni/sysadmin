<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VaultsClosuresDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'vaults_closure_id', 'cash_value', 'quantity'
    ];
}
