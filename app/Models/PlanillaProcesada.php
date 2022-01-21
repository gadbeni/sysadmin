<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PlanillaProcesada extends Model
{
    use HasFactory;
    protected $connection = 'mysqlgobe';
    protected $table = 'planillaprocesada';
}
