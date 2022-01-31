<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UnidadAdministrativa extends Model
{
    use HasFactory;

    protected $connection = 'mysqlgobe';
    protected $table = 'unidadadminstrativa';

    public function direccion_administrativa(){
        return $this->belongsTo(DireccionAdministrativa::class, 'idDa', 'ID');
    }
}
