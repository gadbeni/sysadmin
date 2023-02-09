<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TcCategory extends Model
{
    use HasFactory, SoftDeletes;
    protected $dates = ['deleted_at'];
    protected $connection = 'siscor';
    protected $table = 'categories';


    protected $fillable = ['nombre','organization_id'];

    public function organization(){
        return $this->belongsTo(TcOrganization::class);
    }
}
