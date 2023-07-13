<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PersonRotation extends Model
{
    use HasFactory, SoftDeletes;
    protected $dates = ['deleted_at'];

    protected $fillable = [
        'user_id', 'destiny_id', 'destiny_job', 'destiny_dependency', 'responsible_id', 'responsible_job', 'contract_id', 'office_id', 'date', 'observations'
    ];

    public function contract(){
        return $this->belongsTo(Contract::class);
    }

    public function destiny(){
        return $this->belongsTo(Person::class, 'destiny_id');
    }

    public function responsible(){
        return $this->belongsTo(Person::class, 'responsible_id');
    }

    public function user(){
        return $this->belongsTo(User::class);
    }
}
