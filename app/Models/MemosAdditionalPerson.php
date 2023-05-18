<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MemosAdditionalPerson extends Model
{
    use HasFactory, SoftDeletes;
    protected $dates = ['deleted_at'];
    protected $fillable = [
        'memo_id', 'person_external_id', 'observations'
    ];

    public function person_external(){
        return $this->belongsTo(PersonExternal::class, 'person_external_id');
    }
}
