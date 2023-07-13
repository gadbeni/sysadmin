<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PersonIrremovability extends Model
{
    use HasFactory, SoftDeletes;
    protected $dates = ['deleted_at'];

    protected $fillable = [
        'person_id', 'user_id', 'irremovability_type_id', 'start', 'finish', 'observations'
    ];

    public function type(){
        return $this->belongsTo(IrremovabilityType::class, 'irremovability_type_id');
    }
    public function user(){
        return $this->belongsTo(User::class);
    }
}
