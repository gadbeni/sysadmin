<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VaultsClosure extends Model
{
    use HasFactory;

    protected $fillable = [
        'vault_id', 'user_id', 'observations'
    ];

    public function details(){
        return $this->hasMany(VaultsClosuresDetail::class);
    }

    public function user(){
        return $this->belongsTo(User::class)->withTrashed();
    }
}
