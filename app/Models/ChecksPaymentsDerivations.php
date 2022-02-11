<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChecksPaymentsDerivations extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'checks_payment_id',
        'office_id',
        'observations',
    ];

    public function office(){
        return $this->belongsTo(Office::class, 'office_id');
    }

    public function user(){
        return $this->belongsTo(User::class, 'user_id');
    }
}
