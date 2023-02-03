<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TcArchivo extends Model
{
    use HasFactory, SoftDeletes;
    protected $dates = ['deleted_at'];
    protected $connection = 'siscor';
    protected $table = 'archivos';

    protected $fillable = [
        'ruta',
        'descripcion',
        'nombre_origen',
        'extension',
        'entrada_id',
        'user_id',
        'created_at',
        'deleted_at',
        'nci',
        'deleteUser_id'
    ];

    function user(){
        return $this->belongsTo(TcUser::class, 'user_id');
    }
}
