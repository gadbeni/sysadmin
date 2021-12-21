<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Aguinaldo;

class HomeController extends Controller
{
    public function index(){
        return view('welcome');
    }

    public function search_payroll_by_ci(Request $request){
        $search = $request->search;
        $person = DB::connection('mysqlgobe')->table('contribuyente')
                        ->where('N_Carnet', $search)
                        ->first();
        if($person){
            $data = DB::connection('mysqlgobe')->table('planillahaberes as p')
                        ->join('planillaprocesada as pp', 'pp.id', 'p.idPlanillaprocesada')
                        ->where('p.pagada', 1)
                        ->where('p.CedulaIdentidad', $search)
                        ->where('p.Anio', date('Y'))
                        ->select('p.*', 'p.ITEM as item', 'pp.Estado as estado_planilla_procesada')
                        ->orderBy('p.ID', 'DESC')->limit(3)->get();
            $aguinaldo = Aguinaldo::with('payment.cashier.user')->where('ci', $search)->where('deleted_at', NULL)->where('estado', 'pendiente')->first();
            return response()->json(['search' => $data, 'aguinaldo' => $aguinaldo]);
        }else{
            return response()->json(['error' => 'La cédula de identidad ingresada no está registrada en el sistema.']);
        }
    }
}
