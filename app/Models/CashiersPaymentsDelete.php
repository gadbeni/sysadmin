<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CashiersPaymentsDelete extends Model
{
    use HasFactory;

    protected $fillable = [
        'cashiers_payment_id', 'user_id', 'observations'
    ];

    public function user(){
        return $this->belongsTo(User::class)->withTrashed();
    }
}
