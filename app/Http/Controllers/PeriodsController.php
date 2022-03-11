<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

// Models
use App\Models\Period;

class PeriodsController extends Controller
{
    public function periods_tipo_direccion_adminstrativa($id){
        $periods = Period::whereRaw("(tipo_direccion_administrativa_id like '%$id%' or tipo_direccion_administrativa_id like '%todos%')")
                            ->where('status', 1)
                            ->where('deleted_at', NULL)->get();
        return response()->json($periods);
    }
}
