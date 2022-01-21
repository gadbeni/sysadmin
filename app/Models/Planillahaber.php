<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Planillahaber extends Model
{
    use HasFactory;

    protected $connection = 'mysqlgobe';
    protected $table = 'planillahaberes';

    public function tipo(){
        return $this->belongsTo(TipoPlanilla::class, 'Tplanilla', 'ID');
    }

    public function planilla_procesada(){
        return $this->belongsTo(PlanillaProcesada::class, 'idPlanillaprocesada', 'ID');
    }
    
}
