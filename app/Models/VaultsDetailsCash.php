<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VaultsDetailsCash extends Model
{
    use HasFactory;

    protected $fillable = [
        'vaults_detail_id', 'cash_value', 'quantity'
    ];
}
