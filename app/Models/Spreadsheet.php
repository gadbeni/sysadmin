<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Spreadsheet extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'direccion_administrativa_id', 'tipo_planilla_id', 'codigo_planilla', 'year', 'month', 'people', 'afp_id', 'total'
    ];

    public function user(){
        return $this->belongsTo(User::class);
    }
}
