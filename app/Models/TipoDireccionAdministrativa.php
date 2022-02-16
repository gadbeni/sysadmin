<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TipoDireccionAdministrativa extends Model
{
    use HasFactory;

    protected $connection = 'mysqlgobe';
    protected $table = 'grupoda';

    public function direcciones_administrativas(){
        return $this->hasMany(DireccionAdministrativa::class, 'Tipo', 'ID');
    }
}
