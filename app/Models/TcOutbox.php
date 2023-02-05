<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TcOutbox extends Model
{
    // use HasFactory;
    use HasFactory, SoftDeletes;
    protected $connection = 'siscor';
    protected $table = 'entradas';

    protected $fillable = [
        'gestion', 'tipo', 'remitente', 'cite', 'referencia', 'nro_hojas', 'funcionario_id_remitente','deadline', 
        'unidad_id_remitente', 'direccion_id_remitente', 'funcionario_id_destino', 'funcionario_id_responsable', 
        'registrado_por', 'registrado_por_id_direccion', 'registrado_por_id_unidad', 'actualizado_por', 
        'fecha_actualizacion','fecha_registro','observacion_rechazo', 'detalles', 'entity_id', 'estado_id', 'tipo_id','details',
        'urgent','category_id',

        'people_id_de', 'job_de', 'people_id_para', 'job_para'
    ];


    public function inbox(){
        return $this->hasMany(TcInbox::class, 'entrada_id');
    }


    function entity(){
        return $this->belongsTo(TcEntity::class, 'entity_id');
    }

    function estado(){
        return $this->belongsTo(TcEstado::class, 'estado_id');
    }
    
    function archivos(){
        return $this->hasMany(TcArchivo::class, 'entrada_id');
    }
    public function person()
    {
        return $this->belongsTo(TcPersona::class, 'people_id_para');
    }
}
