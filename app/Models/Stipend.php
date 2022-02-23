<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Stipend extends Model
{
    use HasFactory, SoftDeletes;

    protected $dates = ['deleted_at'];
    protected $fillable = ['id', 'ci', 'funcionario','cargo', 'sueldo', 'rciva','dia', 'montofactura','total','liqpagable','estado'];

    public function payment(){
        return $this->hasOne(CashiersPayment::class);
    }
}
