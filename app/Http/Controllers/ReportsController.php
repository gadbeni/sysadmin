<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;

// Models
use App\Models\PayrollPayment;
use App\Models\ChecksPayment;
use App\Models\Cashier;
use App\Models\CashiersPayment;
use App\Models\VaultsClosure;
use App\Models\VaultsDetail;
use App\Models\Spreadsheet;
use App\Models\Direccion;
use App\Models\Contract;
use App\Models\Period;
use App\Models\Paymentschedule;
use App\Models\PaymentschedulesDetail;
use App\Models\ProcedureType;
use App\Models\Job;
use App\Models\Cargo;
use App\Models\Person;
use App\Models\Program;
use App\Models\BonusesDetail;
use App\Models\Afp;
use App\Models\Addendum;

// Exports
use App\Exports\MinisterioTrabajoExport;
use App\Exports\MinisterioEconomiaExport;
use App\Exports\AFPExport;
use App\Exports\ContractsExport;
use App\Exports\AddendumsExport;
use App\Exports\ProgramsExport;
use App\Exports\BonusExport;
use App\Exports\PaymentschedulesDetailsExport;
use App\Exports\PeopleExport;

class ReportsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    public function humans_resources_people_index(){
        $this->custom_authorize('browse_reportshumans-resourcespeople');
        return view('reports.rr_hh.people-browse');
    }

    public function humans_resources_people_list(Request $request){
        $afp_id = $request->afp_id;
        $afp_status = $request->afp_status;
        $retired = $request->retired;
        $gender = $request->gender;
        $people = Person::with(['contracts.direccion_administrativa', 'contracts' => function($q) use ($request){
                            $q->whereRaw($request->contract_active == 1 ? "status = 'firmado'" : "(status = 'elaborado' or status = 'enviado')");
                        }])
                        ->whereRaw($afp_id ? "afp = $afp_id" : 1)
                        ->whereRaw($retired != 'todos' ? "retired = $retired" : 1)
                        ->whereRaw($afp_status != 'todos' ? "afp_status = $afp_status" : 1)
                        ->whereRaw($gender != 'todos' ? "gender = '$gender'" : 1)
                        ->where(function($query) use ($request){
                            if($request->contract_active){
                                $query->whereHas('contracts', function($query) use ($request){
                                    $query->whereRaw($request->contract_active == 1 ? "status = 'firmado'" : "(status = 'elaborado' or status = 'enviado')")
                                        ->whereRaw($request->procedure_type_id ? "procedure_type_id = ".$request->procedure_type_id : 1);
                                });
                            }
                        })
                        ->where('deleted_at', NULL)->get();
        if($request->type == 'excel'){
            return Excel::download(new PeopleExport($people), 'personas.xlsx');
        }elseif($request->type == 'print'){
            return view('reports.rr_hh.people-print', compact('people'));
        }else{
            return view('reports.rr_hh.people-list', compact('people'));
        }
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

    public function management_contracts_index(){
        $this->custom_authorize('browse_reportsmanagementcontracts');
        return view('reports.management.contracts-browse');
    }

    public function management_contracts_list(Request $request){
        $contracts = Contract::with(['user', 'person', 'program', 'cargo.nivel', 'job.direccion_administrativa', 'direccion_administrativa', 'unidad_administrativa', 'type', 'addendums'])
                        ->whereRaw($request->procedure_type_id ? "procedure_type_id = ".$request->procedure_type_id : 1)
                        ->whereRaw($request->status ? "status in ".str_replace(array('[', ']'), array('(', ')'), json_encode($request->status)) : 1)
                        ->whereRaw($request->start ? "DATE_FORMAT(start, '%Y-%m') >= '".$request->start."'" : 1)
                        ->whereRaw($request->finish ? "DATE_FORMAT(start, '%Y-%m') <= '".$request->finish."'" : 1)
                        ->whereRaw($request->period ? "(YEAR(start) = ".$request->period." or YEAR(finish) = ".$request->period.")" : 1)
                        ->whereHas('direccion_administrativa', function($q) use($request){
                            $q->whereRaw($request->direcciones_tipo_id ? "direcciones_tipo_id = ".$request->direcciones_tipo_id : 1);
                        })
                        ->whereRaw($request->direccion_administrativa_id ? "direccion_administrativa_id in ".str_replace(array('[', ']'), array('(', ')'), json_encode($request->direccion_administrativa_id)) : 1)
                        ->where(function($query) use ($request){
                            if($request->addendums){
                                $query->OrwhereHas('addendums', function($query){
                                    $query->whereRaw("1");
                                });
                            }
                        })
                        ->where('deleted_at', NULL)->get();
        if($request->type == 'print'){
            $type_render = 2;
            return view('reports.management.contracts-print', compact('contracts', 'type_render'));
        }else if($request->type == 'excel'){
            // return view('reports.management.contracts-excel', compact('contracts'));
            return Excel::download(new ContractsExport($contracts), 'contratos.xlsx');
        }else{
            return view('reports.management.contracts-list', compact('contracts'));
        }
    }

    public function management_addendums_index(){
        $this->custom_authorize('browse_reportsmanagementaddendums');
        return view('reports.management.addendums-browse');
    }

    public function management_addendums_list(Request $request) {
        $addendums = Addendum::with(['contract'])
                        ->whereHas('contract.direccion_administrativa', function($q) use($request){
                            $q->whereRaw($request->direcciones_tipo_id ? "direcciones_tipo_id = ".$request->direcciones_tipo_id : 1);
                        })
                        ->whereHas('contract', function($q) use($request){
                            if ($request->procedure_type_id) {
                                $q->whereRaw($request->procedure_type_id ? "procedure_type_id = ".$request->procedure_type_id : 1);
                            }
                            if ($request->direccion_administrativa_id) {
                                $q->whereRaw($request->direccion_administrativa_id ? "direccion_administrativa_id in ".str_replace(array('[', ']'), array('(', ')'), json_encode($request->direccion_administrativa_id)) : 1);
                            }
                        })
                        ->whereRaw($request->year ? "YEAR(start) = '".$request->year."'" : 1)
                        ->whereRaw($request->status ? "status = '".$request->status."'" : 1)
                        ->where('deleted_at', NULL)->get();
        if($request->type == 'print'){
            $type_render = 2;
            return view('reports.management.addendums-print', compact('addendums', 'type_render'));
        }else if($request->type == 'excel'){
            return Excel::download(new AddendumsExport($addendums), 'adendas.xlsx');
        }else{
            return view('reports.management.addendums-list', compact('addendums'));
        }
    }

    public function paymentschedules_details_status_index(){
        return view('reports.paymentschedules.paymentschedules_details_status-browse');
    }

    public function paymentschedules_details_status_list(Request $request){
        $period = Period::where('id', $request->period_id)->first();
        $grouped = $request->grouped;
        $payments = PaymentschedulesDetail::with(['paymentschedule.direccion_administrativa', 'contract.person', 'contract.job', 'contract.cargo', 'contract.type', 'contract.program', 'contract.direccion_administrativa', 'contract.unidad_administrativa', 'payment'])
                        ->whereHas('paymentschedule', function($q) use ($request){
                            $q->where('period_id', $request->period_id)->where('deleted_at', NULL);
                        })
                        ->whereHas('paymentschedule.direccion_administrativa', function($q) use ($request){
                            $q->whereRaw($request->direcciones_tipo_id ? "direcciones_tipo_id = ".$request->direcciones_tipo_id : 1);
                        })
                        ->whereHas('paymentschedule', function($q) use ($request){
                            $q->whereRaw($request->direccion_administrativa_id ? "direccion_administrativa_id in ".str_replace(array('[', ']'), array('(', ')'), json_encode($request->direccion_administrativa_id)) : 1);
                        })
                        ->whereHas('contract', function($q) use ($request){
                            $q->whereRaw($request->procedure_type_id ? 'procedure_type_id = '.$request->procedure_type_id : 1);
                        })
                        ->where('status', '<>', 'anulado')->where('deleted_at', NULL)->get();
        if($request->type == 'pdf'){
            return view('reports.paymentschedules.paymentschedules_details_status-print', compact('payments', 'period', 'grouped'));
        }elseif($request->type == 'excel')
            return Excel::download(new PaymentschedulesDetailsExport($payments, $grouped), 'Pagos '.$period->name.'.xlsx');
        else{
            return view('reports.paymentschedules.paymentschedules_details_status-list', compact('payments', 'grouped'));
        }
    }

    public function humans_resources_aniversarios_index(){
        return view('reports.rr_hh.aniversarios-browse');
    }

    public function humans_resources_aniversarios_list(Request $request){
        $month = $request->month;
        $people = Person::whereMonth('birthday', $month)
                    ->whereHas('contracts', function($q) use($request){
                        $q->where('procedure_type_id', $request->procedure_type_id);
                    })
                    ->whereRaw(Auth::user()->direccion_administrativa_id ? "user_id = ".Auth::user()->id : 1)
                    ->where('deleted_at', NULL)->orderByRaw('DAY(birthday)', 'ASC')
                    ->get();
        // dd($people);
        if($request->print){
            return view('reports.rr_hh.aniversarios-print', compact('people', 'month'));
        }else{
            return view('reports.rr_hh.aniversarios-list', compact('people'));
        }
    }

    public function humans_resources_jobs_index(){
        return view('reports.rr_hh.jobs-browse');
    }

    public function humans_resources_jobs_list(Request $request){
        $procedure_type_id = $request->procedure_type_id;
        $procedure_type = ProcedureType::find($procedure_type_id);
        if($request->procedure_type_id == 1){
            $jobs = Job::with(['direccion_administrativa', 'contract' => function($q) use($procedure_type_id){
                        $q->where('status', '<>', 'anulado')->where('status', '<>', 'concluido')
                        ->where('procedure_type_id', $procedure_type_id)->where('deleted_at', NULL);
                    }, 'contract.person'])
                    ->whereRaw($request->direccion_administrativa_id ? "direccion_administrativa_id = ".$request->direccion_administrativa_id : 1)
                    ->where('status', 1)->where('deleted_at', NULL)->get();
        }else{
            // $jobs = Contract::with(['direccion_administrativa', 'person', 'cargo'])
            //             ->whereRaw($request->direccion_administrativa_id ? "direccion_administrativa_id = ".$request->direccion_administrativa_id : 1)
            //             ->where('procedure_type_id', $procedure_type_id)->where('status', '<>', 'anulado')->where('status', '<>', 'concluido')
            //             ->where('cargo_id', '<>', NULL)->where('deleted_at', NULL)->get();
            $jobs = collect([]);
        }

        if($request->print){
            return view('reports.rr_hh.jobs-print', compact('jobs', 'procedure_type'));
        }else{
            return view('reports.rr_hh.jobs-list', compact('jobs', 'procedure_type'));
        }
    }

    public function humans_resources_relationships_index(){
        return view('reports.rr_hh.relationships-browse');
    }

    public function humans_resources_relationships_list(Request $request){
        // dd($request->all());
        try {
            $people = Person::with(['city', 'irremovabilities', 'contracts.direccion_administrativa', 'contracts.job', 'contracts.cargo', 'contracts.program', 'contracts.type'])
                        ->whereHas('contracts', function($q) use($request){
                            $q->where('status', 'firmado')
                            // ->where('procedure_type_id', $request->procedure_type_id)
                            ->where('deleted_at', NULL);
                        })
                        ->where('deleted_at', NULL)->orderBy('last_name')->get();
            $relationships = collect([]);
            foreach ($people->groupBy('last_name') as $person) {
                if(count($person) > 1){
                    $relationships->push($person); 
                }
            }
            // dd($relationships);
            if($request->print){
                return view('reports.rr_hh.relationships-list-print', compact('relationships'));
            }else{
                return view('reports.rr_hh.relationships-list', compact('relationships'));
            }
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    public function contracts_projects_details_index(){
        return view('reports.rr_hh.projects_details-browse');
    }

    public function contracts_projects_details_list(Request $request){
        $type_render =$request->type;
        $program = Program::with(['direccion_administrativa', 'procedure_type', 'contracts' => function($q){
                            $q->where('deleted_at', NULL);
                        }, 'contracts.paymentschedules_details' => function($q){
                            $q->where('deleted_at', NULL);
                        }, 'contracts.paymentschedules_details.paymentschedule'])
                        ->whereRaw($request->direccion_administrativa_id ? 'direccion_administrativa_id = '.$request->direccion_administrativa_id : 1)
                        ->whereRaw($request->procedure_type_id ? 'procedure_type_id = '.$request->procedure_type_id : 1)
                        ->whereRaw('YEAR(start) = '.$request->year)
                        ->where('deleted_at', NULL)
                        ->get();
        if($request->type == 'pdf'){
            $pdf = PDF::loadView('reports.rr_hh.projects_details-list-print', compact('program', 'type_render'));
            return $pdf->setPaper('letter', 'landscape')->stream();
            // return view('reports.rr_hh.projects_details-list-print', compact('program', 'type_render'));
        }elseif($request->type == 'excel'){
            return Excel::download(new ProgramsExport($program, $type_render), 'programas.xlsx');
        }else{
            return view('reports.rr_hh.projects_details-list', compact('program', 'type_render'));
        }
    }

    

    public function social_security_payments_index(){
        $direcciones_administrativa = Direccion::where('estado', 1)->where('deleted_at', null)->get();
        return view('reports.social_security.payments-browse', compact('direcciones_administrativa'));
    }

    public function social_security_payments_list(Request $request){
        $planillas_alt = collect([]);
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
                                        ->orderBy('ph.Periodo')
                                        ->orderBy('ph.idPlanillaprocesada')
                                        ->selectRaw('ph.idPlanillaprocesada, ph.Periodo, tp.Nombre as tipo_planilla, ROUND(SUM(ph.Total_Aportes_Afp), 2) as Total_Aportes_Afp, ROUND(SUM(ph.Riesgo_Comun), 2) as riesgo_comun, count(ph.Total_Aportes_Afp) as cantidad_personas, sum(ph.Total_Ganado) as total_ganado, ph.Direccion_Administrativa, ph.Afp')
                                        ->get();

                    // Nueva planilla
                    $afp = $request->afp ?? NULL;
                    $period = Period::where('name', $request->periodo)->where('deleted_at', NULL)->first();
                    if($request->periodo){
                        $period_id = $period ? $period->id : 'none';
                    }else{
                        $period_id = NULL;
                    }
                    $procedure_type = ProcedureType::where('planilla_id', $request->t_planilla ?? 0)->where('deleted_at', NULL)->first();
                    $procedure_type_id = $procedure_type ? $procedure_type->id : NULL;
                    $direccion_administrativa_id = $request->id_da;
                    $id_planilla = $request->id_planilla;

                    $planillas_alt = PaymentschedulesDetail::with(['paymentschedule.period', 'paymentschedule.details.contract.program', 'paymentschedule.direccion_administrativa', 'paymentschedule.procedure_type', 'contract.person', 'paymentschedule.check_payments' => function($q){
                                            $q->where('deleted_at', NULL);
                                        }, 'paymentschedule.payroll_payments' => function($q){
                                            $q->where('deleted_at', NULL);
                                        }])
                                        ->whereHas('paymentschedule', function($q) use($period_id, $procedure_type_id, $id_planilla){
                                            $q->whereRaw($period_id ? "period_id = '$period_id'" : 1)
                                                ->whereRaw($procedure_type_id ? "procedure_type_id = $procedure_type_id" : "(procedure_type_id = 1 or procedure_type_id = 5)")
                                                ->whereRaw($id_planilla ? 'id = "'.intval($id_planilla).'"' : 1)
                                                ->where('centralize', 0)->where('deleted_at', NULL);
                                        })
                                        ->whereHas('contract.person', function($q) use($afp){
                                            $q->whereRaw($afp ? "afp = $afp" : 1);
                                        })
                                        ->whereHas('paymentschedule', function($q) use($direccion_administrativa_id){
                                            $q->whereRaw($direccion_administrativa_id ? "direccion_administrativa_id = $direccion_administrativa_id" : 1);
                                        })
                                        ->where('deleted_at', NULL)
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
                    
                    // **** Falta la parte no agrupada
                    // $planillas_alt = [];
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
                                        
                    $afp = $request->afp_alt ?? NULL;
                    $period = Period::where('name', $request->periodo_alt)->where('deleted_at', NULL)->first();
                    if($request->periodo_alt){
                        $period_id = $period ? $period->id : 'none';
                    }else{
                        $period_id = NULL;
                    }
                    $procedure_type = ProcedureType::where('planilla_id', $request->t_planilla_alt ?? 0)->where('deleted_at', NULL)->first();
                    $procedure_type_id = $procedure_type ? $procedure_type->id : NULL;

                    $planillas_alt = PaymentschedulesDetail::with(['paymentschedule.period', 'paymentschedule.details.contract.program', 'paymentschedule.direccion_administrativa', 'paymentschedule.procedure_type', 'contract.person', 'paymentschedule.check_payments' => function($q){
                                            $q->where('deleted_at', NULL);
                                        }, 'paymentschedule.payroll_payments' => function($q){
                                            $q->where('deleted_at', NULL);
                                        }])
                                        ->whereHas('paymentschedule', function($q) use($period_id, $procedure_type_id){
                                            $q->whereRaw($period_id ? "period_id = '$period_id'" : 1)
                                                ->whereRaw($procedure_type_id ? "procedure_type_id = $procedure_type_id" : "(procedure_type_id = 1 or procedure_type_id = 5)")
                                                ->where('centralize', 1)->where('deleted_at', NULL);
                                        })
                                        ->whereHas('contract.person', function($q) use($afp){
                                            $q->whereRaw($afp ? "afp = $afp" : 1);
                                        })
                                        ->where('deleted_at', NULL)
                                        ->get();
                    // dd($planillas_alt->all());
                    
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
                    return view('reports.social_security.payments-group-list-print', compact('planillas', 'planillas_alt'));
                }else{
                    return view('reports.social_security.payments-list-print', compact('planillas'));
                }
            }else{
                return view('reports.social_security.payments-details-list-print', compact('da'));
            }
        }else{
            if($request->tipo_planilla == 1 || $request->tipo_planilla == 2){
                if($request->group_afp){
                    return view('reports.social_security.payments-group-list', compact('planillas', 'planillas_alt'));
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
        $direcciones_administrativa = Direccion::where('estado', 1)->where('deleted_at', null)->get();
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
        // $funcionarios_ingreso = DB::connection('mysqlgobe')->table('planillahaberes as p')
        //                     ->join('contratos as c', 'c.idContribuyente', 'p.CedulaIdentidad')
        //                     ->join('tplanilla as tp', 'tp.id', 'p.Tplanilla')
        //                     ->whereRaw($request->t_planilla ? 'p.Tplanilla = '.$request->t_planilla : 1)
        //                     ->whereYear('c.Fecha_Inicio', $request->year)
        //                     ->whereMonth('c.Fecha_Inicio', $request->month)
        //                     ->where('p.Anio', $request->year)
        //                     ->where('p.Mes', $request->month)
        //                     ->whereRaw('(p.idGda=1 or p.idGda=2)')
        //                     ->select('p.*', 'p.ITEM as item', 'tp.Nombre as tipo_planilla', 'c.Fecha_Inicio')
        //                     ->orderBy('p.Apaterno')
        //                     ->get();

        // $funcionarios_egreso = DB::connection('mysqlgobe')->table('planillahaberes as p')
        //                     ->join('contratos as c', 'c.idContribuyente', 'p.CedulaIdentidad')
        //                     ->join('tplanilla as tp', 'tp.id', 'p.Tplanilla')
        //                     ->whereRaw($request->t_planilla ? 'p.Tplanilla = '.$request->t_planilla : 1)
        //                     ->whereYear('c.Fecha_Conclusion', $request->year)
        //                     ->whereMonth('c.Fecha_Conclusion', $request->month)
        //                     ->where('p.Anio', $request->year)
        //                     ->where('p.Mes', $request->month)
        //                     ->whereRaw('(p.idGda=1 or p.idGda=2)')
        //                     ->select('p.*', 'p.ITEM as item', 'tp.Nombre as tipo_planilla', 'c.Fecha_Conclusion')
        //                     ->orderBy('p.Apaterno')
        //                     ->get();

        // dd($request->year.str_pad($request->month, 2, "0", STR_PAD_LEFT));
        $funcionarios_ingreso = Contract::with(['paymentschedules_details' => function($q){
                                        $q->where('deleted_at', NULL);
                                    }])
                                    ->whereHas('paymentschedules_details.paymentschedule.period', function($q) use($request){
                                        $q->whereRaw("name = '".$request->year.str_pad($request->month, 2, "0", STR_PAD_LEFT)."'");
                                    })
                                    ->whereRaw("CONCAT(YEAR(start),MONTH(start)) = '".$request->year.$request->month."'")
                                    ->where('procedure_type_id', $request->procedure_type_id)->where('deleted_at', NULL)->get();
        $funcionarios_egreso = Contract::with(['paymentschedules_details' => function($q){
                                        $q->where('deleted_at', NULL);
                                    }])
                                    ->whereHas('paymentschedules_details.paymentschedule.period', function($q) use($request){
                                        $q->whereRaw("name = '".$request->year.str_pad($request->month, 2, "0", STR_PAD_LEFT)."'");
                                    })->whereRaw("CONCAT(YEAR(finish),MONTH(finish)) = '".$request->year.$request->month."'")
                                    ->where('procedure_type_id', $request->procedure_type_id)->where('deleted_at', NULL)->get();
        if($request->print){
            return view('reports.rr_hh.contracts-history-print', compact('funcionarios_ingreso', 'funcionarios_egreso', 'year', 'month'));
        }else{
            return view('reports.rr_hh.contracts-history-list', compact('funcionarios_ingreso', 'funcionarios_egreso'));
        }

    }

    public function social_security_spreadsheets_payments_index(){
        $direcciones_administrativa = Direccion::where('estado', 1)->where('deleted_at', null)->get();
        return view('reports.social_security.spreadsheets_payments-browse', compact('direcciones_administrativa'));
    }

    public function social_security_spreadsheets_payments_list(Request $request){
        // dd($request->all());
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
        $payments = Spreadsheet::with(['payments', 'checks.beneficiary.type', 'direccion_administrativa'])
                        ->whereRaw($request->t_planilla ? 'tipo_planilla_id = '.$request->t_planilla : 1)
                        ->whereRaw($request->afp ? 'afp_id = '.$request->afp : 1)
                        ->whereRaw($request->id_da ? 'direccion_administrativa_id = '.$request->id_da : 1)
                        ->whereRaw($query_range)
                        ->whereRaw($request->codigo_planilla ? 'codigo_planilla = '.$request->codigo_planilla : 1)
                        ->get();

        if($request->type == 'print'){
            return view('reports.social_security.spreadsheets_payments-print', compact('payments'));
        }else{
            return view('reports.social_security.spreadsheets_payments-list', compact('payments'));
        }
    }

    public function social_security_personal_payments_index(){
        $contribuyentes = DB::connection('mysqlgobe')->table('contribuyente')->get();
        // dd($contribuyentes);
        return view('reports.social_security.personal-payments-browse', compact('contribuyentes'));
    }

    public function social_security_personal_payments_list(Request $request){
        $type_data = $request->type_data;
        $planillas = [];
        $paymentschedules_details = [];
        
        // Si es 1 entonces traemos datos de la base de datos de MAMORE
        if ($type_data) {
            $paymentschedules_details = PaymentschedulesDetail::with(['paymentschedule.payroll_payments' => function($q){
                                            $q->where('fpc_number', '<>', NULL)->groupBy('fpc_number');
                                        }, 'paymentschedule.period', 'contract.person', 'contract.direccion_administrativa'])
                                        ->whereHas('contract', function($q)use($request){
                                            $q->where('person_id', $request->person_id);
                                        })
                                        ->whereHas('paymentschedule.period', function($q)use($request){
                                            $q->where('name',  '>=', date('Ym', strtotime($request->start)))
                                                ->where('name',  '<=', date('Ym', strtotime($request->finish)));
                                        })
                                        ->where('deleted_at', null)->get();
        }else{
            $start = $request->start.'-01';
            $finish = $request->finish.'-01';
            $planillas = DB::connection('mysqlgobe')->table('planillahaberes as p')
                            ->join('contribuyente as c', 'c.N_Carnet', 'p.CedulaIdentidad')
                            ->join('direccionadministrativa as da', 'da.ID', 'p.idDa')
                            ->where('c.ID', $request->contribuyente_id)
                            ->where('p.Periodo', '>=', date('Ym', strtotime($start)))
                            ->where('p.Periodo', '<=', date('Ym', strtotime($finish)))
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
                            )
                            ->orderBy('p.Periodo', 'ASC')->get();
            $cont = 0;
            foreach ($planillas as $item) {
                // Obtener detalle de pago
                $planillahaberes = DB::connection('mysqlgobe')->table('planillahaberes')->where('Afp', $item->afp)->where('idPlanillaprocesada', $item->planilla_procesada)->get();
                $payments = PayrollPayment::whereIn('planilla_haber_id', $planillahaberes->pluck('ID'))->where('deleted_at', NULL)->get();
                $planillas[$cont]->payments = $payments;
                $cont++;
            }
        }
        
        if($request->type == 'print'){
            return view('reports.social_security.personal-payments-print', compact('planillas', 'type_data', 'paymentschedules_details'));
        }else{
            return view('reports.social_security.personal-payments-list', compact('planillas', 'type_data', 'paymentschedules_details'));
        }
    }

    public function social_security_personal_caratula_index(){
        return view('reports.social_security.caratula-browse');
    }

    public function social_security_personal_caratula_list(Request $request){
        // dd($request->all());
        $planilla_id = $request->planilla;
        $afp = $request->afp;
        $centralize = str_contains(strtolower($planilla_id), '-c') ? true : false;
        $planilla = DB::connection('mysqlgobe')->table('planillahaberes as p')
                        ->join('tplanilla as tp', 'tp.id', 'p.Tplanilla')
                        ->where('p.idPlanillaprocesada', $planilla_id)
                        ->whereRaw($afp ? 'p.Afp = '.$afp : 1)
                        ->selectRaw('p.Direccion_Administrativa as direccion_administrativa, p.Afp  as afp, SUM(p.Total_Ganado) as total_ganado, COUNT(*) as n_personas, p.Periodo as periodo, tp.Nombre as tipo_planilla')
                        ->groupBy('p.Afp')->get();

                    
        $paymentschedules = Paymentschedule::with(['details.contract.person' => function($query) use ($afp){
                                $query->whereRaw($afp ? "afp = ".$afp : 1);
                            }, 'check_payments' => function($q){
                                $q->where('status', 2)->where('deleted_at', NULL);
                            }, 'payroll_payments.afp_details', 'payroll_payments' => function($q){
                                $q->where('deleted_at', NULL);
                            }, 'procedure_type'])->whereHas('details.contract.person', function($query) use ($afp){
                                $query->whereRaw($afp ? "afp = ".$afp : 1);
                            })
                            ->whereRaw($centralize ? 'centralize_code = "'.str_replace('-a', '', strtolower($planilla_id)).'"' : 'id = '.intval($planilla_id))
                            ->where('deleted_at', NULL)->get();
        // dd($paymentschedules);

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
            // return view('reports.social_security.caratula-list-print', compact('planilla_id', 'planilla', 'pagos', 'cheques_afp', 'cheques_salud', 'planillahaberes', 'paymentschedules', 'afp'));
            $pdf = PDF::loadView('reports.social_security.caratula-list-print', compact('planilla_id', 'planilla', 'pagos', 'cheques_afp', 'cheques_salud', 'planillahaberes', 'paymentschedules', 'afp'));
            return $pdf->stream();
        }else{
            return view('reports.social_security.caratula-list', compact('planilla_id', 'planilla', 'pagos', 'cheques_afp', 'cheques_salud', 'planillahaberes', 'paymentschedules', 'afp'));
        }
    }

    public function social_security_personal_checks_index(){
        $direcciones_administrativa = Direccion::where('estado', 1)->where('deleted_at', null)->get();
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

    public function social_security_exports_index(){
        return view('reports.social_security.exports-browse');
    }

    public function social_security_exports_list(Request $request){
        // dd($request->all());
        $paymentschedule_id = $request->paymentschedule_id;
        $procedure_type_id = $request->procedure_type_id;
        $type_report = $request->type_report;
        $type_export = $request->type_export;
        $tipo_ministerio = $request->tipo_ministerio;
        $afp = $request->afp;
        $group_by = $request->group_by;
        $title = '';
        if($type_report == '#form-ministerio'){
            $data = PaymentschedulesDetail::with(['contract.person.irremovabilities' => function($q){
                $q->where('start', '<=', date('Y-m-d'))->whereRaw("(finish is NULL or finish >= '".date('Y-m-d')."')");
            }])
            ->whereHas('paymentschedule', function($q) use($request){
                $q->where('procedure_type_id', $request->procedure_type_id)->where('period_id', $request->period_id)->where('deleted_at', NULL);
            })->where('deleted_at', NULL)->get();
            $title = 'ministerio '.date('d-m-Y H:i:s');
        }elseif($type_report == '#form-afp'){
            $data = Paymentschedule::with(['user', 'direccion_administrativa', 'period', 'procedure_type', 'details.contract' => function($q){
                        $q->where('deleted_at', NULL)->orderBy('id', 'DESC');
                    }, 'details' => function($q){
                        $q->where('deleted_at', NULL);
                    }])
                    ->whereRaw('(id = "'.intval($paymentschedule_id).'" or centralize_code like "'.intval(explode('-', $paymentschedule_id)[0]).'-c")')
                    ->where('deleted_at', NULL)->first();
            if(str_contains(strtolower($paymentschedule_id), '-c')){
                $centralize_code = $data->centralize_code;
                $data->details = PaymentschedulesDetail::with(['contract.program', 'contract.type'])
                                    ->whereHas('paymentschedule', function($q) use($centralize_code){
                                        $q->where('centralize_code', $centralize_code)->where('deleted_at', NULL);
                                    })
                                    ->where('deleted_at', NULL)->get();
            }

            // Si se elije una AFP, se filtran los contratos que correspondan a esa AFP
            if($afp){
                // NOTA: En caso de que sea el periodo 202304 no se hace filtro de AFP (Orden de RRHH y Bienestar Laboral)
                if($data){
                    $se_filtra = $data->period_id == 16 ? false : true;
                }

                $details = collect();
                foreach($data->details as $detail){
                    if($detail->afp == $afp && $se_filtra){
                        $details->push($detail);
                    }elseif(!$se_filtra){
                        $details->push($detail);
                    }
                }
                $data->details = $details;
            }
        }
        if($type_export == 'excel'){
            if($type_report == '#form-ministerio'){
                if($tipo_ministerio == 1){
                    return Excel::download(new MinisterioTrabajoExport($data), 'Ministerios de trabajo '.date('d-m-Y H:i:s').".xlsx");
                }else{
                    return Excel::download(new MinisterioEconomiaExport($data), 'Ministerios de economía '.date('d-m-Y H:i:s').".xlsx");
                }
            }elseif($type_report == '#form-afp'){
                $title = Afp::find($afp)->name.' '.date('d-m-Y H:i:s');
                return Excel::download(new AfpExport($data, $afp, $group_by), "$title .xlsx");
            }
        }
        return view('reports.social_security.exports-list', compact('data', 'type_report', 'tipo_ministerio', 'afp', 'group_by'));
    }

    public function social_security_payrollpayments_index(){
        return view('reports.social_security.payroll_payments-browse');
    }

    public function social_security_payrollpayments_list(Request $request){
        $payroll_payment = PayrollPayment::whereHas('paymentschedule', function($q) use($request){
                                $q->whereRaw($request->direccion_administrativa_id ? 'direccion_administrativa_id = '.$request->direccion_administrativa_id : 1);
                            })
                            ->whereRaw($request->user ? 'user_id = '.Auth::user()->id : 1)
                            ->whereDate('created_at', '>=', $request->start)
                            ->whereDate('created_at', '<=', $request->finish)
                            ->where('deleted_at', NULL)->get();

        if($request->type == 'print'){
            return view('reports.social_security.payroll_payments-print', compact('payroll_payment'));
        }else{
            return view('reports.social_security.payroll_payments-list', compact('payroll_payment'));
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
        $procedure_type = ProcedureType::find($request->procedure_type_id);

        $payments = CashiersPayment::with(['deletes', 'cashier.user', 'planilla', 'aguinaldo', 'stipend', 'paymentschedulesdetail'])
                        ->whereHas('cashier.user', function($query) use ($user_id){
                            $query->whereRaw($user_id ? 'id = '.$user_id : 1);
                        })
                        ->whereDate('created_at', '>=', date('Y-m-d', strtotime($request->start)))
                        ->whereDate('created_at', '<=', date('Y-m-d', strtotime($request->finish)))->get();
        
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
        $details = VaultsDetail::with(['cash', 'user'])
                        ->whereDate('created_at', '<=', date('Y-m-d', strtotime($request->date)))
                        ->where('status', 'aprobado')->where('deleted_at', NULL)
                        ->get();
        // dd($details);
        $date = $request->date;
        if($request->print){
            return view('reports.cashiers.vaults-print', compact('details', 'date'));
        }else{
            return view('reports.cashiers.vaults-list', compact('details'));
        }
    }

    public function contracts_people_index(){
        $contarcts = Person::with(['contracts' => function($q){
                        $q->where('deleted_at', NULL);
                    }, 'contracts.job', 'contracts.cargo', 'contracts.direccion_administrativa'])->where('deleted_at', NULL)
                    ->orderBy('last_name')->get();
        return view('templates.browse', compact('contarcts'));
    }

    public function check_list(Request $request){
        $cat ='';
        $tip ='';
        if($request->categoria_id == 0)
        {
            $cat ='!=';
        }
        else
        {
            $cat ='=';
        }

        if($request->tipo == "0")
        {
            $tip ='!=';
        }
        else
        {
            $tip ='=';
        }

        if($request->tipo == 'registrado')
        {
            
            $detalle = DB::table('checks as c')
                ->join('checks_categories as cc', 'cc.id', 'c.checkcategoria_id')
                ->select('cc.name', 'c.resumen')
                ->where('c.status','!=', '0')
                ->where('c.checkcategoria_id',$cat, $request->categoria_id)
                ->where('c.deleted_at', null)
                ->get();
                
        }
        else
        {
            $detalle = DB::table('checks as c')
                ->join('checks_categories as cc', 'cc.id', 'c.checkcategoria_id')
                ->select('cc.name', 'c.resumen')
                // ->where('c.status', $tip, $request->tipo)
                ->where('c.status', $request->tipo)
                ->where('c.checkcategoria_id',$cat, $request->categoria_id)
                ->where('c.deleted_at',$tip, null)
                ->get();
        }
  
        $inicio = $request->start;
        $fin = $request->finish;
        // dd($inicio);
    
        if($request->print){
            return view('reports.check.check-print', compact('detalle', 'inicio', 'fin'));

        }else{
            return view('reports.check.check-list', compact('detalle', 'inicio', 'fin'));

        }
    }

    public function bonus_index(){
        $this->custom_authorize('browse_reportshumans-resourcesbonus');
        return view('reports.paymentschedules.bonus-browse');
    }

    public function bonus_list(Request $request){
        $year = $request->year;
        $bonuses = BonusesDetail::whereHas('bonus', function($q) use($year){
                        $q->where('year', $year)->where('deleted_at', NULL);
                    })
                    ->whereHas('contract', function($q) use($request){
                        $q->whereRaw($request->procedure_type_id ? 'procedure_type_id = '.$request->procedure_type_id : 1);
                    })
                    ->whereHas('contract.direccion_administrativa', function($q) use($request){
                        $q->whereRaw($request->direcciones_tipo_id ? "direcciones_tipo_id = ".$request->direcciones_tipo_id : 1);
                    })
                    ->whereHas('contract', function($q) use($request){
                        $q->whereRaw($request->direccion_administrativa_id ? "direccion_administrativa_id in ".str_replace(array('[', ']'), array('(', ')'), json_encode($request->direccion_administrativa_id)) : 1);
                    })
                    ->where('deleted_at', NULL)->get();
        if($request->type == 'excel'){
            // return view('reports.paymentschedules.bonus-excel', compact('bonuses', 'year'));
            return Excel::download(new BonusExport($bonuses, $year), 'aguinaldos-'.$year.'.xlsx');
        }elseif($request->type == 'print'){
            $type_render = 1;
            $procedure_type_id = $request->procedure_type_id;
            $direcciones_tipo_id = $request->direcciones_tipo_id;
            $direccion_administrativa_id = $request->direccion_administrativa_id;
            return view('reports.paymentschedules.bonus-print', compact('bonuses', 'year', 'type_render', 'procedure_type_id'));
        }else{
            return view('reports.paymentschedules.bonus-list', compact('bonuses', 'year'));
        }
    }

    public function attendances_index(){
        $this->custom_authorize('browse_reportshumans-resourcesattendances');
        return view('reports.rr_hh.attendances-browse');
    }
}