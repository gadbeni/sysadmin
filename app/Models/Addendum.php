<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Addendum extends Model
{
    use HasFactory, SoftDeletes;
    protected $dates = ['deleted_at'];

    protected $fillable = [
        'contract_id', 'code', 'signature_id', 'start', 'finish', 'details_payments', 'observations', 'status'
    ];

    public function signature(){
        return $this->belongsTo(Signature::class, 'signature_id');
    }
}
