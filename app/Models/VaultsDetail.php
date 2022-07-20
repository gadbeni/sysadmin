<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class VaultsDetail extends Model
{
    use HasFactory, SoftDeletes;

    protected $dates = ['deleted_at'];
    protected $fillable = [
        'user_id', 'vault_id', 'cashier_id', 'bill_number', 'name_sender', 'description', 'type', 'status'
    ];

    public function cash(){
        return $this->hasMany(VaultsDetailsCash::class)->withTrashed();
    }

    public function user(){
        return $this->belongsTo(User::class)->withTrashed();
    }

    public function cashier(){
        return $this->belongsTo(Cashier::class)->withTrashed();
    }
}
