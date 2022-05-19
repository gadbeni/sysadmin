<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class unidad extends Model
{
    use HasFactory;

    protected $table = 'unidades';

    public function direccion_administrativa(){
        return $this->belongsTo(Direccion::class, 'direccion_id', 'id');
    }
}
