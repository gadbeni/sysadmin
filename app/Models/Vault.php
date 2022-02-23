<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Vault extends Model
{
    use HasFactory, SoftDeletes;

    protected $dates = ['deleted_at'];
    protected $fillable = [
        'user_id', 'name', 'description', 'status'
    ];

    public function details(){
        return $this->hasMany(VaultsDetail::class);
    }

    public function user(){
        return $this->belongsTo(User::class)->withTrashed();
    }
}
