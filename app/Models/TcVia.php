<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TcVia extends Model
{
    use HasFactory, SoftDeletes;
    protected $dates = ['deleted_at'];
    protected $connection = 'siscor';
    protected $table = 'vias';


    protected $fillable = [
        'funcionario_id', 'nombre', 'cargo', 'entrada_id',
        'people_id'
    ];
}
