<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChecksHistory extends Model
{
    use HasFactory;
protected $fillable =['check_id', 'office_id', 'user_id', 'status', 'observacion', 'fentregar'];



}
