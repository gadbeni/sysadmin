<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TcPersonaExt extends Model
{
    use HasFactory, SoftDeletes;
    protected $dates = ['deleted_at'];
    protected $connection = 'siscor';
    protected $table = 'people_exts';


    protected $fillable = ['person_id', 'direccion_id', 'unidad_id', 'cargo', 'observacion', 'status', 'deleted_at'];

    // public function person(){
    //     return $this->belongsTo(Person::class, 'person_id', 'id');
    // }
}
