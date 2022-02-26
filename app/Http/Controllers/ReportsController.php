<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;

// Models
use App\Models\PayrollPayment;
use App\Models\ChecksPayment;
use App\Models\Cashier;
use App\Models\CashiersPayment;
use App\Models\VaultsClosure;
use App\Models\Spreadsheet;
use App\Models\DireccionAdministrativa;
use App\Models\Contract;

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

    public function contracts_contracts_index(){
        return view('reports.contracts.contracts-browse');
    }

    public function contracts_contracts_list(Request $request){
        $contracts = Contract::with(['user', 'person', 'program', 'cargo.nivel' => function($q){
                            $q->where('Estado', 1);
                        }, 'job.direccion_administrativa', 'direccion_administrativa', 'type'])
                        ->whereRaw($request->procedure_type_id ? "procedure_type_id = ".$request->procedure_type_id : 1)
                        ->where('deleted_at', NULL)->get();
        // dd($contracts);
        if($request->print){
            return view('reports.contracts.contracts-print', compact('contracts'));
        }else{
            return view('reports.contracts.contracts-list', compact('contracts'));
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
        // Planilla no centralizada

        switch ($request->tipo_planilla) {
            case '1':
                $periodo = $request->periodo;
                $query_range = 1;
                if(strpos($periodo, '-') != false){
                    $periodos = explode('-', $periodo);
                    $query_range = '(ph.Periodo >= "'.$periodos[0].'" and ph.Periodo <= "'.$periodos[1].'")';
                }else if($periodo){
                    $query_range = 'ph.Periodo = '.$request->periodo;
                }

                if($request->group_afp){
                    $planillas = DB::connection('mysqlgobe')->table('planillahaberes as ph')
                                        ->join('planillaprocesada as pp', 'pp.ID', 'ph.idPlanillaprocesada')
                                        ->join('tplanilla as tp', 'tp.ID', 'ph.Tplanilla')
                                        ->whereRaw($request->t_planilla ? 'ph.Tplanilla = '.$request->t_planilla : 1)
                                        ->whereRaw($request->afp ? 'ph.Afp = '.$request->afp : 1)
                                        ->whereRaw($request->id_da ? 'pp.idDa = '.$request->id_da : 1)
                                        ->whereRaw($query_range)
                                        ->whereRaw($request->id_planilla ? 'ph.idPlanillaprocesada = '.$request->id_planilla : 1)
                                        ->whereRaw('(tp.ID = 1 or tp.ID = 2)')
                                        ->groupBy('ph.Afp', 'ph.idPlanillaprocesada')
                                        ->orderBy('ph.idPlanillaprocesada')
                                        ->selectRaw('ph.idPlanillaprocesada, ph.Periodo, tp.Nombre as tipo_planilla, ROUND(SUM(ph.Total_Aportes_Afp), 2) as Total_Aportes_Afp, ROUND(SUM(ph.Riesgo_Comun), 2) as riesgo_comun, count(ph.Total_Aportes_Afp) as cantidad_personas, sum(ph.Total_Ganado) as total_ganado, ph.Direccion_Administrativa, ph.Afp')
                                        ->get();
                }else{
                    $planillas = DB::connection('mysqlgobe')->table('planillahaberes as ph')
                                        ->join('planillaprocesada as pp', 'pp.ID', 'ph.idPlanillaprocesada')
                                        ->join('tplanilla as tp', 'tp.ID', 'ph.Tplanilla')
                                        ->whereRaw($request->t_planilla ? 'ph.Tplanilla = '.$request->t_planilla : 1)
                                        ->whereRaw($request->afp ? 'ph.Afp = '.$request->afp : 1)
                                        ->whereRaw($request->id_da ? 'pp.idDa = '.$request->id_da : 1)
                                        ->whereRaw($query_range)
                                        ->whereRaw($request->id_planilla ? 'ph.idPlanillaprocesada = '.$request->id_planilla : 1)
                                        ->whereRaw('(tp.ID = 1 or tp.ID = 2)')
                                        ->orderBy('ph.idPlanillaprocesada')
                                        ->selectRaw('ph.*')
                                        ->get();
                }
                break;
 
            case '2':
                if($request->group_afp){
                    $planillas = DB::connection('mysqlgobe')->table('planillahaberes as ph')
                                        ->join('planillaprocesada as pp', 'pp.ID', 'ph.idPlanillaprocesada')
                                        ->join('tplanilla as tp', 'tp.ID', 'ph.Tplanilla')
                                        ->where('ph.Estado', 1)
                                        ->where('ph.Tplanilla', $request->t_planilla_alt ?? 0)
                                        ->whereRaw('(ph.idGda=1 or ph.idGda=2)')
                                        ->where('ph.Periodo', $request->periodo_alt ?? 0)
                                        ->where('ph.Centralizado', 'SI')
                                        ->whereRaw($request->afp_alt ? 'ph.Afp = '.$request->afp_alt : 1)
                                        ->groupBy('ph.Afp', 'ph.idPlanillaprocesada')
                                        ->orderBy('ph.idPlanillaprocesada')
                                        ->selectRaw('ph.idPlanillaprocesada, ph.Periodo, tp.Nombre as tipo_planilla, ROUND(SUM(ph.Total_Aportes_Afp), 2) as Total_Aportes_Afp, ROUND(SUM(ph.Riesgo_Comun), 2) as riesgo_comun, count(ph.Total_Aportes_Afp) as cantidad_personas, sum(ph.Total_Ganado) as total_ganado, ph.Direccion_Administrativa, ph.Afp')
                                        ->get();
                }else{
                    $planillas = DB::connection('mysqlgobe')->table('planillahaberes as ph')
                                        ->join('planillaprocesada as pp', 'pp.ID', 'ph.idPlanillaprocesada')
                                        ->join('tplanilla as tp', 'tp.ID', 'ph.Tplanilla')
                                        ->where('ph.Estado', 1)
                                        ->where('ph.Tplanilla', $request->t_planilla_alt ?? 0)
                                        ->whereRaw('(ph.idGda=1 or ph.idGda=2)')
                                        ->where('ph.Periodo', $request->periodo_alt ?? 0)
                                        ->where('ph.Centralizado', 'SI')
                                        ->whereRaw($request->afp_alt ? 'ph.Afp = '.$request->afp_alt : 1)
                                        ->orderBy('ph.idPlanillaprocesada')
                                        ->selectRaw('ph.*')
                                        ->get();
                }
                break;
            case '3':
                $id_da = '';
                if($request->id_da_detallada){
                    foreach ($request->id_da_detallada as $item) {
                        $id_da .= $item.',';
                    }
                }
                $da = DB::connection('mysqlgobe')->table('direccionadministrativa as da')
                            ->whereRaw($id_da != '' ? 'da.ID in ('.substr($id_da, 0, -1).')' : 1)->get();
                $cont = 0;
                foreach ($da as $item) {
                    $planillas = DB::connection('mysqlgobe')->table('planillahaberes as ph')
                                                ->join('tplanilla as tp', 'tp.ID', 'ph.Tplanilla')
                                                ->where('ph.idDa', $item->ID)
                                                ->where('ph.Periodo', $request->periodo_detallada)
                                                ->whereRaw($request->t_planilla_detallada ? 'ph.Tplanilla = '.$request->t_planilla_detallada : '(ph.Tplanilla = 1 or ph.Tplanilla = 2)')
                                                ->groupBy('ph.Afp', 'ph.Tplanilla')
                                                ->selectRaw('ph.Afp as afp, tp.Nombre as tipo_planilla, COUNT(ph.ID) as personas, SUM(ph.Total_Ganado) as total_ganado, ph.idPlanillaprocesada, ph.Periodo')
                                                ->orderBy('ph.Tplanilla')
                                                ->get();
                    $index = 0;
                    foreach ($planillas as $item) {        
                        // Obtener detalle de pago
                        $planillahaberes = DB::connection('mysqlgobe')->table('planillahaberes')->where('Afp', $item->afp)->where('idPlanillaprocesada', $item->idPlanillaprocesada)->get();
                        $pago = PayrollPayment::whereIn('planilla_haber_id', $planillahaberes->pluck('ID'))->where('deleted_at', NULL)->orderBy('id', 'DESC')->get();
                        $planillas[$index]->detalle_pago = $pago;
                        // Obtener detalle de cheques afp
                        $cheques = ChecksPayment::with('beneficiary')->whereHas('beneficiary.type', function($q){
                            $q->where('name', 'not like', '%salud%');
                        })->whereIn('planilla_haber_id', $planillahaberes->pluck('ID'))->where('deleted_at', NULL)->orderBy('id', 'DESC')->get();
                        $planillas[$index]->detalle_cheque_afp = $cheques;
                        $cheques = ChecksPayment::with('beneficiary')->whereHas('beneficiary.type', function($q){
                            $q->where('name', 'like', '%salud%');
                        })->whereIn('planilla_haber_id', $planillahaberes->pluck('ID'))->where('deleted_at', NULL)->orderBy('id', 'DESC')->get();
                        $planillas[$index]->detalle_cheque_cc = $cheques;
                        $index++;
                    }
                    $da[$cont]->planillas = $planillas;
                    $cont++;
                }
                // dd($da);
                break;
                
            default:
                # code...
                break;
        }
        
        if($request->group_afp && ($request->tipo_planilla == 1 || $request->tipo_planilla == 2)){
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
                $planillahaberes = DB::connection('mysqlgobe')->table('planillahaberes')->where('Afp', $item->Afp)->where('idPlanillaprocesada', $item->idPlanillaprocesada)->get();
                $pago = PayrollPayment::whereIn('planilla_haber_id', $planillahaberes->pluck('ID'))->where('deleted_at', NULL)->get();
                $planillas[$cont]->detalle_pago = $pago;
                // Obtener detalle de cheques
                $cheque = ChecksPayment::with('beneficiary')->whereHas('beneficiary.type', function($q){
                    $q->where('name', 'like', '%salud%');
                })->whereIn('planilla_haber_id', $planillahaberes->pluck('ID'))->where('deleted_at', NULL)->get();
                $planillas[$cont]->detalle_cheque = $cheque;
                $cont++;
            }
        }
        
        // dd($planillas);
        if($request->type == 'print'){
            if($request->tipo_planilla == 1 || $request->tipo_planilla == 2){
                if($request->group_afp){
                    return view('reports.social_security.payments-group-list-print', compact('planillas'));
                }else{
                    return view('reports.social_security.payments-list-print', compact('planillas'));
                }
            }else{
                return view('reports.social_security.payments-details-list-print', compact('da'));
            }
        }elseif($request->type == 'excel'){
            return Excel::download(new PaymentsExport, 'users.xlsx');
        }else{
            if($request->tipo_planilla == 1 || $request->tipo_planilla == 2){
                if($request->group_afp){
                    return view('reports.social_security.payments-group-list', compact('planillas'));
                }else{
                    return view('reports.social_security.payments-list', compact('planillas'));
                }
            }else{
                return view('reports.social_security.payments-details-list', compact('da'));
            }
        }
    }
  
  public function social_security_spreadsheets_index(){
        return view('reports.social_security.spreadsheets-browse');
    }

    public function social_security_spreadsheets_list(Request $request){
        $start = '01-'.$request->start;
        $end = '01-'.$request->end;
        $planillas = DB::connection('mysqlgobe')->table('planillaprocesada as p')
                                        ->join('direccionadministrativa as d', 'd.ID', 'p.idDa')
                                        ->join('tplanilla as t', 't.ID', 'p.TipoP')
                                        ->where('p.Periodo', '>=', date('Ym', strtotime($start)))
                                        ->where('p.Periodo', '<=', date('Ym', strtotime($end)))
                                        ->select('p.*', 't.Nombre as tipo_planilla', 'd.NOMBRE as direccion_administrativa')
                                        ->orderBy('p.Mes', 'ASC')->get();
        return view('reports.social_security.spreadsheets-list', compact('planillas'));
    }
    public function social_security_payments_group_index(){
        $direcciones_administrativa = DB::connection('mysqlgobe')->table('direccionadministrativa')->get();
        return view('reports.social_security.payments-annual-browse', compact('direcciones_administrativa'));
    }

    public function social_security_payments_group_list(Request $request){
        switch ($request->type) {
            case '1':
                $pagos_manuales = PayrollPayment::where('manual', 1)->first();
                $pagos_afp = DB::connection('mysqlgobe')->table('pagoafp as p')
                                    ->join('certiplanilla as c', 'c.ID', 'p.IDCertiPlanilla')
                                    ->where("c.Gestion", $request->year)
                                    ->selectRaw('c.Num_planilla AS planilla, p.ID AS pago_id, p.Fecha_Pago AS fecha_pago, c.Formularios AS fpc')
                                    ->get();
                $data = Collect();
                foreach ($pagos_afp as $item) {
                    $array = explode(' ', $item->fpc);
                    foreach ($array as $value) {
                        if($value){
                            $fpc = explode(';', $value);
                            $pagos_cc = DB::connection('mysqlgobe')->table('pagocajasalud as p')
                                                ->join('certiplanilla as c', 'c.ID', 'p.IDcerti')
                                                ->where("c.Num_planilla", 'like', '%'.$item->planilla.'%')
                                                ->selectRaw('p.Fecha_deposito AS fecha_pago, p.N_Comprobante AS nro_deposito, p.Form_GTC11 AS gtc11, p.ID AS pago_id')
                                                ->first();
                            $data->push([
                                'planilla' => str_replace(' ', '', $item->planilla),
                                'pago_id' => $item->pago_id,
                                'fpc_number' => str_replace('-', '', $fpc[0]),
                                'afp' => count($fpc) > 1 ? $fpc[1] : '',
                                'fecha_pago' => $item->fecha_pago,
                                'pago_id_cc' => $pagos_cc ? $pagos_cc->pago_id : null,
                                'fecha_pago_cc' => $pagos_cc ? $pagos_cc->fecha_pago : null,
                                'nro_deposito_cc' => $pagos_cc ? $pagos_cc->nro_deposito : null,
                                'gtc11' => $pagos_cc ? $pagos_cc->gtc11 :  null,
                            ]);

                            if(!$pagos_manuales){
                                DB::beginTransaction();
                                try {
                                    $planillas_procesadas = explode(',', str_replace(' ', '', $item->planilla));
                                    foreach ($planillas_procesadas as $planilla) {
                                        $planillahaberes = DB::connection('mysqlgobe')->table('planillahaberes')->where('idPlanillaprocesada', $planilla)->where('Afp', count($fpc) > 1 ? ($fpc[1] == 'F' ? 1 : 2) : 0)->first();
                                        if($planillahaberes){
                                            PayrollPayment::create([
                                                'user_id' => Auth::user()->id,
                                                'planilla_haber_id' => $planillahaberes->ID,
                                                'date_payment_afp' => $item->fecha_pago,
                                                'fpc_number' => str_replace('-', '', $fpc[0]),
                                                'payment_id' => $item->pago_id,
                                                'date_payment_cc' => $pagos_cc ? $pagos_cc->fecha_pago : null,
                                                'gtc_number' => $pagos_cc ? $pagos_cc->gtc11 :  null,
                                                'deposit_number' => $pagos_cc ? $pagos_cc->nro_deposito : null,
                                                'check_id' => $pagos_cc ? $pagos_cc->pago_id : null,
                                                'manual' => 1
                                            ]);
                                        }
                                    }
                                    DB::commit();
                                } catch (\Throwable $th) {
                                    DB::rollback();
                                    // dd($th);
                                }
                            }
                        }
                    }
                }
                return view('reports.social_security.payments-annual-list', compact('data'));
                break;
            case '2':
                $year = $request->year;
                $data = DB::connection('mysqlgobe')->table('planillahaberes as ph')
                                    ->join('planillaprocesada as pp', 'pp.ID', 'ph.idPlanillaprocesada')
                                    ->join('tplanilla as tp', 'tp.ID', 'ph.Tplanilla')
                                    ->whereRaw('(tp.ID = 1 or tp.ID = 2)')
                                    ->whereRaw($request->id_da ? 'pp.idDa = '.$request->id_da : 1)
                                    ->select('ph.Mes as month', DB::raw('SUM(ph.Total_Ganado) as total_ganado'), DB::raw('SUM(((ph.Total_Ganado * 0.05) + ph.Riesgo_Comun) + Total_Aportes_Afp) as total_afp'), DB::raw('SUM(ph.Total_Ganado * 0.1) as total_cc'))
                                    ->groupBy('ph.Mes')
                                    ->where('ph.Anio', $year)->get();
                $payroll_payment = ChecksPayment::where('deleted_at', NULL)->select('planilla_haber_id')->get();
                $cont = 0;
                foreach ($data as $item) {
                    $planillas = DB::connection('mysqlgobe')->table('planillahaberes as ph')->where('Periodo', $year.$item->month)->whereIn('ID', $payroll_payment)->select('ID')->get();
                    $ids = [];
                    foreach ($planillas as $planilla) {
                        array_push($ids, $planilla->ID);
                    }
                    $data[$cont]->planillas = ChecksPayment::with(['beneficiary'])->where('deleted_at', NULL)->whereIn('planilla_haber_id', $ids)->get();
                    $cont++;
                }
                // dd($data);
                return view('reports.social_security.payments-annual-group-month-list', compact('data'));
                break;

            default:
                # code...
                break;
        }
    }

    public function social_security_contracts_index(){
        return view('reports.rr_hh.contracts-history-browse');
    }

    public function social_security_contracts_list (Request $request){
        $year = $request->year;
        $month = $request->month;
        $funcionarios_ingreso = DB::connection('mysqlgobe')->table('planillahaberes as p')
                            ->join('contratos as c', 'c.idContribuyente', 'p.CedulaIdentidad')
                            ->join('tplanilla as tp', 'tp.id', 'p.Tplanilla')
                            ->whereRaw($request->t_planilla ? 'p.Tplanilla = '.$request->t_planilla : 1)
                            ->whereYear('c.Fecha_Inicio', $request->year)
                            ->whereMonth('c.Fecha_Inicio', $request->month)
                            ->where('p.Anio', $request->year)
                            ->where('p.Mes', $request->month)
                            ->whereRaw('(p.idGda=1 or p.idGda=2)')
                            ->select('p.*', 'p.ITEM as item', 'tp.Nombre as tipo_planilla', 'c.Fecha_Inicio')
                            ->orderBy('p.Apaterno')
                            ->get();

        $funcionarios_egreso = DB::connection('mysqlgobe')->table('planillahaberes as p')
                            ->join('contratos as c', 'c.idContribuyente', 'p.CedulaIdentidad')
                            ->join('tplanilla as tp', 'tp.id', 'p.Tplanilla')
                            ->whereRaw($request->t_planilla ? 'p.Tplanilla = '.$request->t_planilla : 1)
                            ->whereYear('c.Fecha_Conclusion', $request->year)
                            ->whereMonth('c.Fecha_Conclusion', $request->month)
                            ->where('p.Anio', $request->year)
                            ->where('p.Mes', $request->month)
                            ->whereRaw('(p.idGda=1 or p.idGda=2)')
                            ->select('p.*', 'p.ITEM as item', 'tp.Nombre as tipo_planilla', 'c.Fecha_Conclusion')
                            ->orderBy('p.Apaterno')
                            ->get();
        
        if($request->print){
            return view('reports.rr_hh.contracts-history-print', compact('funcionarios_ingreso', 'funcionarios_egreso', 'year', 'month'));
        }else{
            return view('reports.rr_hh.contracts-history-list', compact('funcionarios_ingreso', 'funcionarios_egreso'));
        }

    }

    public function social_security_spreadsheets_payments_index(){
        $direcciones_administrativa = DB::connection('mysqlgobe')->table('direccionadministrativa')->get();
        return view('reports.social_security.spreadsheets_payments-browse', compact('direcciones_administrativa'));
    }

    public function social_security_spreadsheets_payments_list(Request $request){
        // dd($request->all());
        $direcciones_administrativa = DB::connection('mysqlgobe')->table('direccionadministrativa')->get();
        $periodo = $request->periodo;
        $query_range = 1;
        if(strpos($periodo, '-') != false){
            $periodos = explode('-', $periodo);
            $start = substr($periodos[0], 0, 4).substr($periodos[0], 4, 6);
            $end = substr($periodos[1], 0, 4).substr($periodos[1], 4, 6);
            $query_range = "CONCAT(year,month) >= '$start' AND CONCAT(year,month) <= '$end'";
        }else if($periodo){
            $year = substr($periodo, 0, 4);
            $month = substr($periodo, 4, 6);
            $query_range = 'year = "'.$year.'" and month = "'.$month.'"';
        }
        $payments = Spreadsheet::with(['payments', 'checks.beneficiary.type'])
                        ->whereRaw($request->t_planilla ? 'tipo_planilla_id = '.$request->t_planilla : 1)
                        ->whereRaw($request->afp ? 'afp_id = '.$request->afp : 1)
                        ->whereRaw($request->id_da ? 'direccion_administrativa_id = '.$request->id_da : 1)
                        ->whereRaw($query_range)
                        ->whereRaw($request->codigo_planilla ? 'codigo_planilla = '.$request->codigo_planilla : 1)
                        ->get();

        if($request->type == 'print'){
            return view('reports.social_security.spreadsheets_payments-print', compact('payments', 'direcciones_administrativa'));
        }else{
            return view('reports.social_security.spreadsheets_payments-list', compact('payments', 'direcciones_administrativa'));
        }
    }

    public function social_security_personal_payments_index(){
        $contribuyentes = DB::connection('mysqlgobe')->table('contribuyente')->get();
        // dd($contribuyentes);
        return view('reports.social_security.personal-payments-browse', compact('contribuyentes'));
    }

    public function social_security_personal_payments_list(Request $request){
        $start = '01-'.$request->start;
        $end = '01-'.$request->end;
        $planillas = DB::connection('mysqlgobe')->table('planillahaberes as p')
                        ->join('contribuyente as c', 'c.N_Carnet', 'p.CedulaIdentidad')
                        ->join('direccionadministrativa as da', 'da.ID', 'p.idDa')
                        ->where('c.ID', $request->contribuyente_id)
                        ->where('p.Periodo', '>=', date('Ym', strtotime($start)))
                        ->where('p.Periodo', '<=', date('Ym', strtotime($end)))
                        ->select(
                            'p.ID as id',
                            DB::raw('REPLACE(p.Nombre_Empleado, "  ", " ") as empleado'),
                            'da.NOMBRE as direccion_administrativa',
                            'p.CedulaIdentidad as ci',
                            'p.idPlanillaprocesada as planilla_procesada',
                            'p.Afp as afp',
                            'p.Num_Nua as nua_cua',
                            'p.Periodo as periodo',
                            'p.Total_Ganado as total',
                            'p.Total_Aportes_Afp as total_afp'
                        )->get();
        $cont = 0;
        foreach ($planillas as $item) {
            // Obtener detalle de pago
            $planillahaberes = DB::connection('mysqlgobe')->table('planillahaberes')->where('Afp', $item->afp)->where('idPlanillaprocesada', $item->planilla_procesada)->get();
            $payments = PayrollPayment::whereIn('planilla_haber_id', $planillahaberes->pluck('ID'))->where('deleted_at', NULL)->get();
            $planillas[$cont]->payments = $payments;
            $cont++;
        }
        // dd($request->all());
        if($request->type == 'print'){
            return view('reports.social_security.personal-payments-print', compact('planillas'));
        }else{
            return view('reports.social_security.personal-payments-list', compact('planillas'));
        }
    }

    public function social_security_personal_caratula_index(){
        return view('reports.social_security.caratula-browse');
    }

    public function social_security_personal_caratula_list(Request $request){
        // dd($request->all());
        $planilla_id = $request->planilla;
        $afp = $request->afp;
        $planilla = DB::connection('mysqlgobe')->table('planillahaberes as p')
                        ->join('tplanilla as tp', 'tp.id', 'p.Tplanilla')
                        ->where('p.idPlanillaprocesada', $planilla_id)
                        ->whereRaw($afp ? 'p.Afp = '.$afp : 1)
                        ->selectRaw('p.Direccion_Administrativa as direccion_administrativa, p.Afp  as afp, SUM(p.Total_Ganado) as total_ganado, COUNT(*) as n_personas, p.Periodo as periodo, tp.Nombre as tipo_planilla')
                        ->groupBy('p.Afp')->get();

        // Obtener detalle de pago
        $planillahaberes = DB::connection('mysqlgobe')->table('planillahaberes')->where('idPlanillaprocesada', $planilla_id)->whereRaw($afp ? 'Afp = '.$afp : 1)->get();
        
        $pagos = PayrollPayment::whereIn('planilla_haber_id', $planillahaberes->pluck('ID'))->where('deleted_at', NULL)->get();
        
        // Obtener detalle de cheques de afp
        $cheques_afp = ChecksPayment::with('beneficiary')->whereHas('beneficiary.type', function($q){
            $q->where('name', 'not like', '%salud%');
        })->whereIn('planilla_haber_id', $planillahaberes->pluck('ID'))->where('deleted_at', NULL)->get();

        // Obtener detalle de cheques de caja de salud
        $cheques_salud = ChecksPayment::with('beneficiary')->whereHas('beneficiary.type', function($q){
            $q->where('name', 'like', '%salud%');
        })->whereIn('planilla_haber_id', $planillahaberes->pluck('ID'))->where('deleted_at', NULL)->get();

        if($request->type == 'print'){
            return view('reports.social_security.caratula-list-print', compact('planilla_id', 'planilla', 'pagos', 'cheques_afp', 'cheques_salud', 'planillahaberes'));
        }else{
            return view('reports.social_security.caratula-list', compact('planilla', 'pagos', 'cheques_afp', 'cheques_salud', 'planillahaberes'));
        }
    }

    public function social_security_personal_checks_index(){
        $direcciones_administrativa = DireccionAdministrativa::all();
        return view('reports.social_security.checks-browse', compact('direcciones_administrativa'));
    }

    public function social_security_personal_checks_list(Request $request){
        $checks = ChecksPayment::with(['beneficiary.type'])
                    ->whereRaw($request->start ? 'DATE(date_print) >= "'.$request->start.'"' : 1)
                    ->whereRaw($request->finish ? 'DATE(date_print) <= "'.$request->finish.'"' : 1)
                    ->whereRaw($request->status ? 'status = '.$request->status : 1)
                    ->where('deleted_at', NULL)->get();
        $data = collect();
        $cont = 0;
        foreach ($checks as $value) {
            $planilla_haber = DB::connection('mysqlgobe')->table('planillahaberes')->where('ID', $value->planilla_haber_id)->first();
            if($planilla_haber){
                $planilla = DB::connection('mysqlgobe')->table('planillahaberes as ph')
                                    ->join('tplanilla as tp', 'tp.ID', 'ph.Tplanilla')
                                    ->join('planillaprocesada as pp', 'pp.ID', 'ph.idPlanillaprocesada')
                                    ->where('pp.ID', $planilla_haber->idPlanillaprocesada)
                                    ->where('ph.Afp', $planilla_haber->Afp)
                                    ->whereRaw($request->d_a ? 'ph.idDa = '.$request->d_a : 1)
                                    ->whereRaw($request->periodo ? 'ph.Periodo = '.$request->periodo : 1)
                                    ->whereRaw($request->planilla_id ? 'ph.idPlanillaprocesada = '.$request->planilla_id : 1)
                                    ->select('ph.*', DB::raw('COUNT(ph.ID) as NumPersonas'), DB::raw('SUM(ph.Total_Ganado) as Monto'), 'tp.Nombre as tipo_planilla')
                                    ->orderBy('ph.Direccion_Administrativa', 'ASC')->orderBy('ph.Periodo', 'ASC')->groupBy('ph.idPlanillaprocesada')->first();
                if($planilla){
                    $checks[$cont]->planilla = $planilla;
                    $data->push($checks[$cont]);
                }
            }
            $cont++;
        }
        // dd($data);
        if($request->type == 'print'){
            return view('reports.social_security.checks-list-print', compact('data'));
        }else{
            return view('reports.social_security.checks-list', compact('data'));
        }
        
    }

    //  ===== Cashiers =====

    public function cashier_cashiers_index(){
        return view('reports.cashiers.cashiers-browse');
    }

    public function cashier_cashiers_list(Request $request){
        $cashier = Cashier::with(['user', 'payments.deletes', 'movements', 'details'])
                        ->whereDate('created_at', '>=', date('Y-m-d', strtotime($request->start)))
                        ->whereDate('created_at', '<=', date('Y-m-d', strtotime($request->finish)))
                        ->whereRaw($request->user_id ? 'user_id = '.$request->user_id : 1)->get();
        // dd($cashier);
        if($request->print){
            return view('reports.cashiers.cashiers-print', compact('cashier'));
        }else{
            return view('reports.cashiers.cashiers-list', compact('cashier'));
        }
    }

    public function cashier_payments_index(){
        return view('reports.cashiers.payments-browse');
    }

    public function cashier_payments_list(Request $request){
        $user_id = $request->user_id;
        // dd($user_id);
        if($user_id){
            $payments = CashiersPayment::with(['deletes', 'cashier.user', 'planilla', 'aguinaldo', 'stipend', 'paymentschedulesdetail'])
                            ->whereHas('cashier.user', function($query) use ($user_id){
                                $query->where('id', $user_id);
                            })
                            ->whereDate('created_at', '>=', date('Y-m-d', strtotime($request->start)))
                            ->whereDate('created_at', '<=', date('Y-m-d', strtotime($request->finish)))->get();
        }else{
            $payments = CashiersPayment::with(['deletes', 'cashier.user', 'planilla', 'aguinaldo', 'stipend', 'paymentschedulesdetail'])
                            ->whereDate('created_at', '>=', date('Y-m-d', strtotime($request->start)))
                            ->whereDate('created_at', '<=', date('Y-m-d', strtotime($request->finish)))->get();
        }
        // dd($payments);
        if($request->print){
            return view('reports.cashiers.payments-print', compact('payments'));
        }else{
            return view('reports.cashiers.payments-list', compact('payments'));
        }
    }

    public function cashier_vaults_index(){
        return view('reports.cashiers.vaults-browse');
    }

    public function cashier_vaults_list(Request $request){
        $closure = VaultsClosure::with(['details', 'user'])
                        ->whereDate('created_at', '>=', date('Y-m-d', strtotime($request->start)))
                        ->whereDate('created_at', '<=', date('Y-m-d', strtotime($request->finish)))
                        ->whereRaw($request->user_id ? 'user_id = '.$request->user_id : 1)->get();
        // dd($closure);
        if($request->print){
            return view('reports.cashiers.vaults-print', compact('closure'));
        }else{
            return view('reports.cashiers.vaults-list', compact('closure'));
        }
    }
}
