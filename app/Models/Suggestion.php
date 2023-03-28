<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Suggestion extends Model
{
    use HasFactory, SoftDeletes;
    protected $dates = ['deleted_at'];

    protected $fillable = [
        'user_id', 'type', 'category', 'title', 'details', 'image', 'status'
    ];

    public function user(){
        return $this->belongsTo(User::class)->withTrashed();
    }
}
