<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Cashier extends Model
{
    use HasFactory, SoftDeletes;
    protected $dates = ['deleted_at'];
    protected $fillable = [
        'user_id', 'title', 'observations', 'status', 'closed_at'
    ];

    public function movements(){
        return $this->hasMany(CashiersMovement::class);
    }

    public function payments(){
        return $this->hasMany(CashiersPayment::class);
    }

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function details(){
        return $this->hasMany(CashiersDetail::class);
    }

    public function transfers(){
        return $this->hasMany(CashiersTransfer::class, 'from');
    }

}
