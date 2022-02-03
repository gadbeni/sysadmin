<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NivelSalarial extends Model
{
    use HasFactory;

    protected $connection = 'mysqlgobe';
    protected $table = 'nivelsalarial';
}
