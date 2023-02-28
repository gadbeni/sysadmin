<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Memo extends Model
{
    use HasFactory, SoftDeletes;
    protected $dates = ['deleted_at'];
    protected $fillable = [
        'user_id', 'direccion_administrativa_id', 'origin_id', 'origin_alternate_job', 'destiny_id', 'destiny_alternate_job', 'memos_type_id', 'person_external_id', 'type', 'number', 'code', 'da_sigep', 'source', 'amount', 'concept', 'imputation', 'date'
    ];

    public function origin(){
        return $this->belongsTo(Contract::class, 'origin_id');
    }

    public function destiny(){
        return $this->belongsTo(Contract::class, 'destiny_id');
    }

    public function memos_type(){
        return $this->belongsTo(MemosType::class, 'memos_type_id');
    }

    public function person_external(){
        return $this->belongsTo(PersonExternal::class, 'person_external_id');
    }

    public function direccion(){
        return $this->belongsTo(Direccion::class, 'direccion_administrativa_id');
    }
}
