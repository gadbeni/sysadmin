<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AssetsSubcategory extends Model
{
    use HasFactory, SoftDeletes;
    protected $dates = ['deleted_at'];
    protected $fillable = [
        'assets_category_id', 'name', 'description	'
    ];

    public function category(){
        return $this->belongsTo(AssetsCategory::class, 'assets_category_id');
    }
}
