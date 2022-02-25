<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use TCG\Voyager\Facades\Voyager;
use Illuminate\Database\Eloquent\SoftDeletes;

class Role extends Model
{
    use HasFactory, SoftDeletes;
    protected $dates = ['deleted_at'];

    protected $guarded = [];

    public function users()
    {
        $userModel = Voyager::modelClass('User');

        return $this->belongsToMany($userModel, 'user_roles')
                    ->select(app($userModel)->getTable().'.*')
                    ->union($this->hasMany($userModel))->getQuery();
    }

    public function permissions()
    {
        return $this->belongsToMany(Voyager::modelClass('Permission'));
    }
}