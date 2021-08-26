<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VaultsDetail extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'user_id', 'vault_id', 'cashier_id', 'bill_number', 'name_sender', 'description', 'type', 'status'
    ];

    public function cash(){
        return $this->hasMany(VaultsDetailsCash::class);
    }

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function cashier(){
        return $this->belongsTo(Cashier::class);
    }
}
