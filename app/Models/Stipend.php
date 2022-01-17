<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Stipend extends Model
{
    use HasFactory;
    protected $fillable = ['id', 'ci', 'funcionario','cargo', 'sueldo', 'rciva','dia', 'montofactura','total','liqpagable'];

}
