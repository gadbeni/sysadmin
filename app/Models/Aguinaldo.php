<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Aguinaldo extends Model
{
    use HasFactory;

    protected $fillable = [
        'item', 'funcionario', 'ci', 'nro_dias', 'mes1', 'mes2', 'mes3', 'total', 'sueldo_promedio', 'liquido_pagable', 'estado'
    ];

    public function payment(){
        return $this->hasOne(CashiersPayment::class);
    }
}
