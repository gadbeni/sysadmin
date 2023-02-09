<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TcAdditionalJob extends Model
{
    use HasFactory, SoftDeletes;
    protected $dates = ['deleted_at'];
    protected $connection = 'siscor';
    protected $table = 'additional_jobs';

    protected $fillable = ['person_id', 'cargo', 'observacion', 'status', 'deleted_at'];

}
