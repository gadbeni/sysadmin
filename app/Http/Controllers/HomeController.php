<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    public function index(){
        return view('welcome');
    }

    public function search(Request $request){
        $search = $request->search;
        $person = DB::connection('mysqlgobe')->table('contribuyente')
                        ->where('N_Carnet', $search)
                        ->first();
        if($person){
            $data = DB::connection('mysqlgobe')->table('planillahaberes as p')
                        ->join('planillaprocesada as pp', 'pp.id', 'p.idPlanillaprocesada')
                        ->where('p.pagada', 1)
                        ->where('p.CedulaIdentidad', $search)
                        ->select('p.*', 'p.ITEM as item', 'pp.Estado as estado_planilla_procesada')
                        ->get();
            return response()->json(['search' => $data]);
        }else{
            return response()->json(['error' => 'La cédula de identidad ingresada no está registrada en el sistema.']);
        }
    }
}
