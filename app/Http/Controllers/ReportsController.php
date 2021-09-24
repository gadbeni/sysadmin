<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

// Models
use App\Models\PayrollPayment;

// Exports
use App\Exports\PaymentsExport;

class ReportsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    public function humans_resources_contraloria_index(){
        return view('reports.rr_hh.contraloria-browse');
    }

    public function humans_resources_contraloria_list(Request $request){
        $periodo = $request->periodo;
        $afp = $request->afp;
        switch ($request->type) {
            case 'activos':
                $query_type = 'p.Periodo = "'.$request->periodo.'" and c.Estado = 1';
                break;
            case 'inactivos':
                $query_type = 'c.Pfin = "'.$request->periodo.'"';
                break;
            default:
                $query_type = '1';
                break;
        }
        $funcionarios = DB::connection('mysqlgobe')->table('planillahaberes as p')
                            ->join('planillaprocesada as pp', 'pp.id', 'p.idPlanillaprocesada')
                            ->join('contratos as c', 'c.idContribuyente', 'p.CedulaIdentidad')
                            ->join('tplanilla as tp', 'tp.id', 'p.Tplanilla')
                            ->where('p.Estado', 1)->whereRaw($query_type)
                            ->where('p.Tplanilla', $request->t_planilla ?? 0)
                            ->whereRaw('(p.idGda=1 or p.idGda=2)')
                            ->where('p.Centralizado', 'SI')
                            ->whereRaw($request->afp ? 'p.Afp = '.$request->afp : 1)
                            ->select('p.*', 'p.ITEM as item', 'tp.Nombre as tipo_planilla', 'pp.Estado as estado_planilla_procesada', 'c.Fecha_Inicio', 'c.Fecha_Conclusion')
                            ->orderBy('p.Apaterno')
                            ->groupBy('p.CedulaIdentidad')
                            ->get();
        // dd($funcionarios);
        if($request->print){
            return view('reports.rr_hh.contraloria-print', compact('funcionarios', 'periodo', 'afp'));
        }else{
            return view('reports.rr_hh.contraloria-list', compact('funcionarios'));
        }
    }

    public function humans_resources_aniversarios_index(){
        return view('reports.rr_hh.aniversarios-browse');
    }

    public function humans_resources_aniversarios_list(Request $request){
        $mes = $request->mes;
        $funcionarios = DB::connection('mysqlgobe')->table('planillahaberes as p')
                            ->join('planillaprocesada as pp', 'pp.id', 'p.idPlanillaprocesada')
                            ->join('contratos as c', 'c.idContribuyente', 'p.CedulaIdentidad')
                            ->join('tplanilla as tp', 'tp.id', 'p.Tplanilla')
                            ->where('p.Estado', 1)->where('c.Estado', 1)
                            ->where('p.Tplanilla', $request->t_planilla ?? 0)
                            ->whereRaw('(p.idGda=1 or p.idGda=2)')
                            ->where('p.Centralizado', 'SI')
                            ->whereMonth('p.fechanacimiento', $mes)
                            ->select('p.*', 'p.ITEM as item', 'tp.Nombre as tipo_planilla', 'pp.Estado as estado_planilla_procesada')
                            ->orderBy('p.Apaterno')
                            ->groupBy('p.CedulaIdentidad')
                            ->get();
        // dd($funcionarios);
        if($request->print){
            return view('reports.rr_hh.aniversarios-print', compact('funcionarios', 'mes'));
        }else{
            return view('reports.rr_hh.aniversarios-list', compact('funcionarios'));
        }
    }

    public function social_security_payments_index(){
        $direcciones_administrativa = DB::connection('mysqlgobe')->table('direccionadministrativa')->get();
        return view('reports.social_security.payments-browse', compact('direcciones_administrativa'));
    }

    public function social_security_payments_list(Request $request){
        $periodo = $request->periodo;
        $query_range = 1;
        if(strpos($periodo, '-') != false){
            $periodos = explode('-', $periodo);
            $query_range = '(ph.Periodo >= "'.$periodos[0].'" and ph.Periodo <= "'.$periodos[1].'")';
        }else{
            $query_range = 'ph.Periodo = '.$request->periodo;
        }
        $planillas = DB::connection('mysqlgobe')->table('planillahaberes as ph')
                        ->join('planillaprocesada as pp', 'pp.ID', 'ph.idPlanillaprocesada')
                        ->join('tplanilla as tp', 'tp.ID', 'ph.Tplanilla')
                        ->whereRaw($request->afp ? 'ph.Afp = '.$request->afp : 1)
                        ->whereRaw($request->id_da ? 'pp.idDa = '.$request->id_da : 1)
                        ->whereRaw($query_range)
                        ->whereRaw($request->id_planilla ? 'ph.idPlanillaprocesada = '.$request->id_planilla : 1)
                        ->whereRaw('(tp.ID = 1 or tp.ID = 2)')
                        ->groupBy('ph.Afp', 'ph.idPlanillaprocesada')
                        ->orderBy('ph.idPlanillaprocesada')
                        ->selectRaw('ph.idPlanillaprocesada, ph.Periodo, tp.Nombre as tipo_planilla, sum(ph.Total_Aportes_Afp) as Total_Aportes_Afp, count(ph.Total_Aportes_Afp) as cantidad_personas, sum(ph.Total_Ganado) as total_ganado, ph.Direccion_Administrativa, ph.Afp')
                        ->get();
        $cont = 0;
        foreach ($planillas as $item) {
            // Obtener datos de certificación
            $certificacion = DB::connection('mysqlgobe')->table('certiplanilla as cp')
                                    ->join('planilla as p', 'p.ID', 'cp.IDplanilla')
                                    ->where('Num_planilla', 'like', '%'.$item->idPlanillaprocesada.'%')
                                    ->select('cp.*', 'p.Nombre as nombre_planilla')
                                    ->first();
            $planillas[$cont]->certificacion = $certificacion;

            // Obtener detalle de pago
            $planillahaberes = DB::connection('mysqlgobe')->table('planillahaberes')->where('idPlanillaprocesada', $item->idPlanillaprocesada)->get();
            $pago = PayrollPayment::whereIn('planilla_haber_id', $planillahaberes->pluck('ID'))->where('deleted_at', NULL)->first();
            $planillas[$cont]->detalle_pago = $pago;

            $cont++;
        }
        
        // dd($planillas);
        if($request->type == 'print'){
            return view('reports.social_security.payments-list-print', compact('planillas'));
        }elseif($request->type == 'excel'){
            return Excel::download(new PaymentsExport, 'users.xlsx');
        }else{
            return view('reports.social_security.payments-list', compact('planillas'));
        }
    }
}
