<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Yadahan\AuthenticationLog\AuthenticationLogable;

class User extends \TCG\Voyager\Models\User
{
    use HasFactory, Notifiable, SoftDeletes, AuthenticationLogable;
    protected $dates = ['deleted_at'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'ci',
        'password',
        'direccion_administrativa_id',
        'person_id'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function direccion_administrativa(){
        return $this->belongsTo(Direccion::class, 'direccion_administrativa_id');
    }

    public function person(){
        return $this->belongsTo(Person::class, 'person_id');
    }
}
