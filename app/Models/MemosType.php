<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MemosType extends Model
{
    use HasFactory, SoftDeletes;
    protected $dates = ['deleted_at'];
    protected $fillable = [
        'memos_types_group_id', 'origin_id', 'destiny_id', 'description', 'concept', 'status'
    ];

    public function group(){
        return $this->belongsTo(MemosTypesGroup::class, 'memos_types_group_id');
    }

    public function origin(){
        return $this->belongsTo(Contract::class, 'origin_id');
    }

    public function destiny(){
        return $this->belongsTo(Contract::class, 'destiny_id');
    }
}
