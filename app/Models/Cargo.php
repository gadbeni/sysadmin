<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cargo extends Model
{
    use HasFactory;

    protected $connection = 'mysqlgobe';
    protected $table = 'cargo';

    public function nivel(){
        return $this->hasMany(NivelSalarial::class, 'NumNivel', 'idNumNivel');
    }
}
