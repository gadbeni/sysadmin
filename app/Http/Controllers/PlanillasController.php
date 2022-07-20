<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Carbon\Carbon;
use DataTables;

// Models
use App\Models\Cashier;
use App\Models\CashiersPayment;
use App\Models\CashiersPaymentsDelete;
use App\Models\PlanillasHistory;
use App\Models\Aguinaldo;
use App\Models\Stipend;
use App\Models\Paymentschedule;
use App\Models\PaymentschedulesDetail;
use App\Models\Period;
use App\Models\ProcedureType;
use App\Models\Person;

class PlanillasController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    public function planillas_pagos_index(){
        return view('planillas.procesadas-browse');
    }

    public function planillas_pagos_search(Request $request){
        $tipo_planilla = $request->tipo_planilla;
        $title = '';
        $aguinaldo = [];
        $stipend = [];
        $paymentschedule;
        switch ($tipo_planilla) {
            case '0':
                $planilla = DB::connection('mysqlgobe')->table('planillahaberes as p')
                                ->join('planillaprocesada as pp', 'pp.id', 'p.idPlanillaprocesada')
                                ->join('tplanilla as tp', 'tp.id', 'p.Tplanilla')
                                ->where('p.idPlanillaprocesada', $request->planilla_id ?? 0)
                                ->whereRaw($request->afp_no_centralizada ? 'p.Afp = '.$request->afp_no_centralizada : 1)
                                ->select('p.*', 'p.ITEM as item', 'tp.Nombre as tipo_planilla', 'pp.Estado as estado_planilla_procesada')
                                ->orderByRaw("FIELD(p.idDa, '9','16','10','15','8','13','37','41','42','50','55','61','64','6','62','69','5','17','48','53')")
                                ->get();

                // Nuevas planillas
                $afp = $request->afp_no_centralizada ?? NULL;
                $planilla_id = intval($request->planilla_id);
                $paymentschedule = PaymentschedulesDetail::with(['contract', 'paymentschedule'])
                                        ->whereHas('paymentschedule', function($q) use($planilla_id){
                                            $q->where('id', $planilla_id)->whereRaw('(status = "enviada" or status = "habilitada")')->where('deleted_at', NULL)->where('centralize', 0);
                                        })
                                        ->whereHas('contract.person', function($q) use($afp){
                                            $q->whereRaw($afp ? "afp = $afp" : 1);
                                        })
                                        ->where('deleted_at', NULL)->orderBy('item', 'ASC')->get();
                // dd($paymentschedule);

                $title = 'Planilla '.str_pad($planilla_id, 6, "0", STR_PAD_LEFT).($afp ? ($afp == 1 ? ' | Futuro' : ' | Previsión') : '');
                break;
            case '1':
                $planilla = DB::connection('mysqlgobe')->table('planillahaberes as p')
                                ->join('planillaprocesada as pp', 'pp.id', 'p.idPlanillaprocesada')
                                ->join('tplanilla as tp', 'tp.id', 'p.Tplanilla')
                                ->where('p.Estado', 1)
                                ->where('p.Tplanilla', $request->t_planilla ?? 0)
                                ->whereRaw('(p.idGda=1 or p.idGda=2)')
                                ->where('p.Periodo', $request->periodo ?? 0)
                                ->where('p.Centralizado', 'SI')
                                ->whereRaw($request->afp ? 'p.Afp = '.$request->afp : 1)
                                ->select('p.*', 'p.ITEM as item', 'tp.Nombre as tipo_planilla', 'pp.Estado as estado_planilla_procesada')
                                ->orderByRaw("FIELD (p.idDa,'9','16','10','15','8','13','37','41','42','50','55','61','64','6','62','69','5','17','48','53'), p.Nivel")
                                ->get();

                // Nuevas planillas
                $afp = $request->afp ?? NULL;
                $period = Period::where('name', $request->periodo)->where('deleted_at', NULL)->first();
                $period_id = $period ? $period->id : 0;
                $procedure_type = ProcedureType::where('planilla_id', $request->t_planilla)->where('deleted_at', NULL)->first();
                $procedure_type_id = $procedure_type ? $procedure_type->id : 0;

                $months = ['', 'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'];
                $period = $request->periodo;
                $year = Str::substr($period, 0, 4);
                $month = Str::substr($period, 5, 2);

                $paymentschedule = PaymentschedulesDetail::with(['contract', 'paymentschedule'])
                                        ->whereHas('paymentschedule', function($q){
                                            $q->whereRaw('(status = "enviada" or status = "habilitada")')->where('centralize', 1)->where('deleted_at', NULL);
                                        })
                                        ->whereHas('paymentschedule', function($q) use($period_id){
                                            $q->where('period_id', $period_id);
                                        })
                                        ->whereHas('paymentschedule', function($q) use($procedure_type_id){
                                            $q->where('procedure_type_id', $procedure_type_id);
                                        })
                                        ->whereHas('contract.person', function($q) use($afp){
                                            $q->whereRaw($afp ? "afp = $afp" : 1);
                                        })
                                        ->where('deleted_at', NULL)->orderBy('item', 'ASC')->get();
                $title = ucfirst($procedure_type->name).' | '.$months[intval($month)].' de '.$year.' '.($request->afp ? ($request->afp == 1 ? ' | Futuro' : ' | Previsión') : '');
                break;
            case '2':
                $planilla = DB::connection('mysqlgobe')->table('planillahaberes as p')
                                ->join('planillaprocesada as pp', 'pp.id', 'p.idPlanillaprocesada')
                                ->join('tplanilla as tp', 'tp.id', 'p.Tplanilla')
                                ->where('p.CedulaIdentidad', $request->people_id)
                                ->whereRaw($request->pagada ? 'p.pagada = '.$request->pagada : 1)
                                ->select('p.*', 'p.ITEM as item', 'tp.Nombre as tipo_planilla', 'pp.Estado as estado_planilla_procesada')
                                ->orderBy("p.Periodo", "DESC")
                                ->get();
                $aguinaldo = Aguinaldo::with('payment.cashier.user')->where('ci', 'like', '%'. $request->people_id.'%')->where('deleted_at', NULL)->get();
                $stipend = Stipend::with('payment.cashier.user')->where('ci', 'like', '%'. $request->people_id.'%')->where('deleted_at', NULL)->get();

                $people_id = $request->people_id;
                $paymentschedule = PaymentschedulesDetail::with(['contract', 'paymentschedule.period', 'payment'])
                                        ->whereHas('paymentschedule', function($q){
                                            $q->where('deleted_at', NULL);
                                        })
                                        ->whereHas('contract.person', function($q) use($people_id){
                                            $q->where('id', $people_id);
                                        })
                                        ->where('deleted_at', NULL)->orderBy('id', 'DESC')->get();                
                $title = '';
                break;
        }
        if($request->type_system == 1){
            return view('planillas.procesadas-search-alt', compact('paymentschedule', 'title', 'tipo_planilla'));
        }else{
            return view('planillas.procesadas-search', compact('planilla', 'tipo_planilla', 'title', 'aguinaldo', 'stipend', 'paymentschedule'));
        }
    }

    public function planillas_pagos_search_by_id(){
        $search = \Request::query('q');
        if($search){
            $data = DB::connection('mysqlgobe')->table('planillahaberes as ph')
                        ->join('planillaprocesada as pp', 'pp.ID', 'ph.idPlanillaprocesada')
                        ->join('planilla as p', 'p.ID', 'pp.idPlanilla')
                        ->join('tplanilla as tp', 'tp.ID', 'ph.Tplanilla')
                        ->whereRaw('ph.idPlanillaprocesada like "%'.$search.'%"')
                        ->whereRaw(Auth::user()->direccion_administrativa_id ? 'ph.idDa = '.Auth::user()->direccion_administrativa_id : 1)
                        ->groupBy('ph.Afp', 'ph.idPlanillaprocesada')
                        ->orderBy('ph.idPlanillaprocesada')
                        ->selectRaw('
                            ph.ID as id,
                            ph.idPlanillaprocesada,
                            ph.Periodo,
                            tp.Nombre as tipo_planilla,
                            sum(ph.Total_Aportes_Afp) as total_aportes_afp,
                            count(ph.Total_Aportes_Afp) as cantidad_personas,
                            ph.pagada as certificacion,
                            sum(ph.Total_Ganado) as total_ganado,
                            ph.Direccion_Administrativa,
                            ph.Afp,
                            SUM(ph.Riesgo_Comun) as total_riesgo_comun')
                        ->get();

            $paymentschedule = Paymentschedule::with(['details.contract.person', 'period'])
                                        ->whereHas('details', function($q){
                                            $q->where('deleted_at', NULL);
                                        })
                                        ->whereRaw($search ? "id = '".intval($search)."'" : 1)
                                        // ->where('centralize', 0)
                                        ->where('deleted_at', NULL)->get();

            foreach ($paymentschedule as $item) {
                foreach ($item->details->groupBy('contract.person.afp') as $key => $value) {
                    $data->push([
                        'id' => $item->id,
                        'Periodo' => $item->period->name,
                        'total_aportes_afp' => $value->sum('labor_total'),
                        'cantidad_personas' => count($value),
                        'total_ganado' => $value->sum('partial_salary') + $value->sum('seniority_bonus_amount'),
                        'Afp' => $key,
                        'total_riesgo_comun' => $value->sum('common_risk'),
                        'aditional' => $item->aditional
                    ]);
                }
            }

            return response()->json($data);
        }
    }

    // Habilitar (pagada=1) planillas haberes para pago
    public function planillas_pagos_details_open(Request $request){
        try {
            $id = $request->id;
            // Si el id no es un array lo convertimos
            if(!is_array($id)){
                try {
                    $id = explode(",", $id);
                } catch (\Throwable $th) {
                    $id = [];
                }
            }
            DB::connection('mysqlgobe')->table('planillahaberes')
            ->whereIn('id', $id)
            ->update(['pagada' => 1]);

            PlanillasHistory::create([
                'user_id' => Auth::user()->id,
                'type' => 'Habilitar para pago',
                'details' => json_encode($id)
            ]);

            return response()->json(['success' => 1]);
        } catch (\Throwable $th) {
            return response()->json(['error' => 1]);
        }
    }

    public function planillas_pagos_details_payment(Request $request){
        $cashier = Cashier::with(['movements' => function($q){
            $q->where('deleted_at', NULL);
        }, 'payments' => function($q){
            $q->where('deleted_at', NULL);
        }])->where('id', $request->cashier_id)->first();
        if(!$cashier){
            return response()->json(['error' => 1, 'message' => 'No tiene una caja aperturada.']);
        }
        $payments = $cashier->payments->where('deleted_at', NULL)->sum('amount');
        $movements = $cashier->movements->where('type', 'ingreso')->where('deleted_at', NULL)->sum('amount') - $cashier->movements->where('type', 'egreso')->where('deleted_at', NULL)->sum('amount');
        $total = $movements - $payments;
        if($total - $request->amount < 0){
            return response()->json(['error' => 1, 'message' => 'No tiene suficiente dinero en caja.']);
        }

        DB::beginTransaction();
        try {
            // Pago de sueldo
            if($request->id){
                $pago = DB::connection('mysqlgobe')->table('planillahaberes')->where('id', $request->id)->first();
                if($pago->pagada == 1){
                    DB::connection('mysqlgobe')->table('planillahaberes')
                        ->where('id', $request->id)
                        ->update(['pagada' => 2]);

                    $payment = CashiersPayment::create([
                        'cashier_id' => $request->cashier_id,
                        'planilla_haber_id' => $request->id,
                        'amount' => $request->amount,
                        'description' => 'Pago a '.$request->name.'.',
                        'observations' => $request->observations
                    ]);
                }else{
                    return response()->json(['error' => 1]);
                }
            }

            // Pago de sueldo con la nueva base de datos
            if($request->paymentschedules_detail_id){
                $paymentschedules_detail = PaymentschedulesDetail::find($request->paymentschedules_detail_id);
                if($paymentschedules_detail->status == 'habilitado'){
                    $paymentschedules_detail->status = 'pagado';
                    $paymentschedules_detail->update();

                    $payment = CashiersPayment::create([
                        'cashier_id' => $request->cashier_id,
                        'paymentschedules_detail_id' => $paymentschedules_detail->id,
                        'amount' => $request->amount,
                        'description' => 'Pago a '.$request->name.'.',
                        'observations' => $request->observations
                    ]);
                }else{
                    return response()->json(['error' => 1]);
                }
            }
            
            // Pago de aguinaldo
            if($request->aguinaldo_id){
                Aguinaldo::where('id', $request->aguinaldo_id)->update(['estado' => 'pagado']);
                $payment = CashiersPayment::where('aguinaldo_id', $request->aguinaldo_id)->where('deleted_at', NULL)->first();
                if(!$payment){
                    $name = explode('/', $request->name);
                    $payment = CashiersPayment::create([
                        'cashier_id' => $request->cashier_id,
                        'aguinaldo_id' => $request->aguinaldo_id,
                        'amount' => $request->amount,
                        'description' => 'Pago de aguinaldo a '.trim($name[0]).'.',
                        'observations' => $request->observations
                    ]);
                }
            }

            // Pago de estipendio
            if($request->stipend_id){
                Stipend::where('id', $request->stipend_id)->update(['estado' => 'pagado']);
                $payment = CashiersPayment::where('stipend_id', $request->stipend_id)->where('deleted_at', NULL)->first();
                if(!$payment){
                    $payment = CashiersPayment::create([
                        'cashier_id' => $request->cashier_id,
                        'stipend_id' => $request->stipend_id,
                        'amount' => $request->amount,
                        'description' => 'Pago de estipendio a '.$request->name.'.',
                        'observations' => $request->observations
                    ]);
                }
            }

            DB::commit();
            return response()->json(['success' => 1, 'payment_id' => $payment->id]);
        } catch (\Throwable $th) {
            DB::rollback();
            // dd($th);
            return response()->json(['error' => 1]);
        }
    }


    // Realizar pagos de planilla haberes múltiple
    public function planillas_pagos_details_payment_multiple(Request $request){
        // dd($request);
        DB::beginTransaction();
        try {
            foreach ($request->planilla_haber_id as $item) {
                DB::connection('mysqlgobe')->table('planillahaberes')->where('id', $item)->update(['pagada' => 2]);
            }

            PlanillasHistory::create([
                'user_id' => Auth::user()->id,
                'type' => 'Pago múltiple',
                'details' => json_encode($request->planilla_haber_id)
            ]);

            DB::commit();
            return response()->json(['success' => 1]);
        } catch (\Throwable $th) {
            DB::rollback();
            // dd($th);
            return response()->json(['error' => 1]);
        }
    }

    public function planillas_pagos_update_status(Request $request){
        $id = $request->id;
        // Si el id no es un array lo convertimos
        if(!is_array($id)){
            try {
                $id = explode(",", $id);
            } catch (\Throwable $th) {
                $id = [];
            }
        }

        try {
            DB::connection('mysqlgobe')->table('planillaprocesada')
            ->whereIn('id', $id)
            ->update([
                'Estado' => $request->status,
                'Fecha_Pago' => date('Y-m-d')
            ]);

            return response()->json(['success' => 1]);
        } catch (\Throwable $th) {
            return response()->json(['error' => 1]);
        }
    }

    public function planillas_pagos_delete(Request $request){
        DB::beginTransaction();
        try {
            if($request->planilla_haber_id){
                DB::connection('mysqlgobe')->table('planillahaberes')
                        ->where('id', $request->planilla_haber_id)->update(['pagada' => 1]);

                $payment = CashiersPayment::where('planilla_haber_id', $request->planilla_haber_id)->first();
                $payment->deleted_at = Carbon::now();
                $payment->update();
            }else{
                PaymentschedulesDetail::where('id', $request->paymentschedules_detail_id)->update(['status' => 'habilitado']);
                $payment = CashiersPayment::where('paymentschedules_detail_id', $request->paymentschedules_detail_id)->first();
                $payment->deleted_at = Carbon::now();
                $payment->update();
            }

            CashiersPaymentsDelete::create([
                'cashiers_payment_id' => $payment->id,
                'user_id' => Auth::user()->id,
                'observations' => $request->observations
            ]);

            DB::commit();
            return redirect()->route('voyager.cashiers.show', ['id' => $request->id])->with(['message' => 'Pago anulado correctamente.', 'alert-type' => 'success']);
        } catch (\Throwable $th) {
            DB::rollback();
            // dd($th);
            return redirect()->route('voyager.cashiers.show', ['id' => $request->id])->with(['message' => 'Ocurrió un error.', 'alert-type' => 'error']);
        }
    }

    public function planillas_pagos_print($id){
        $payment = CashiersPayment::with(['cashier.user', 'paymentschedulesdetail.contract.person', 'paymentschedulesdetail.paymentschedule.direccion_administrativa'])->where('id', $id)->first();
        $planilla = null;
        if($payment->planilla_haber_id){
            $planilla = DB::connection('mysqlgobe')->table('planillahaberes as p')
                        ->join('planillaprocesada as pp', 'pp.id', 'p.idPlanillaprocesada')
                        ->join('tplanilla as tp', 'tp.id', 'p.Tplanilla')
                        ->where('p.id', $payment->planilla_haber_id)
                        ->select('p.*', 'p.ITEM as item', 'tp.Nombre as tipo_planilla', 'pp.Estado as estado_planilla_procesada')
                        ->first();    
        }
        // dd($payment);
        
        return view('planillas.payment-recipe-alt', compact('payment', 'planilla'));
    }

    public function planillas_pagos_print_group($id){
        $paymentschedule_details = PaymentschedulesDetail::with(['paymentschedule', 'paymentschedule.procedure_type', 'contract.person', 'paymentschedule.period'])
                                ->where('paymentschedule_id', $id)
                                ->where('deleted_at', NULL)->orderBy('item', 'ASC')->get();
        return view('planillas.payment-recipe-group', compact('paymentschedule_details'));
    }

    public function planillas_pagos_aguinaldos_print($id){
        $payment = Aguinaldo::with(['payment.cashier.user',])->where('id', $id)->first();
        // dd($payment);
        return view('planillas.payment-aguinaldo-recipe', compact('payment'));
    }

    public function planillas_pagos_delete_print($id){
        $payment = CashiersPayment::with(['cashier.user', 'deletes.user'])->where('id', $id)->first();
        $planilla = null;
        if($payment->planilla_haber_id){
            $planilla = DB::connection('mysqlgobe')->table('planillahaberes as p')
                            ->where('p.id', $payment->planilla_haber_id)
                            ->select('p.ID', 'p.Liquido_Pagable')
                            ->first();
        }
        
        // dd($payment, $planilla);
        return view('planillas.payment-recipe-delete', compact('payment', 'planilla'));
    }

    // Funcionales

    public function planillas_pagos_centralizada_search(Request $request){
        $planilla = DB::connection('mysqlgobe')->table('planillahaberes as p')
                        ->where('p.Estado', 1)
                        ->where('p.Tplanilla', $request->t_planilla ?? 0)
                        ->whereRaw('(p.idGda=1 or p.idGda=2)')
                        ->where('p.Periodo', $request->periodo ?? 0)
                        ->where('p.Centralizado', 'SI')
                        ->whereRaw($request->afp ? 'p.Afp = '.$request->afp : 1)
                        // ->groupBy('p.Afp', 'p.idPlanillaprocesada')
                        ->select('p.*')
                        ->get();

        $period = Period::where('name', $request->periodo)->where('deleted_at', NULL)->first();
        $period_id = $period ? $period->id : NULL;
        $centralize_code = $request->centralize_code;
        
        $paymentschedule_details = PaymentschedulesDetail::with(['paymentschedule.period', 'contract.person'])
                                        ->whereHas('paymentschedule.procedure_type', function($q) use($request){
                                            $q->where('planilla_id', $request->t_planilla);
                                        })->whereHas('contract.person', function($q) use($request){
                                            $q->where('afp', $request->afp);
                                        })->whereHas('paymentschedule', function($q) use($period_id, $centralize_code){
                                            $q->where('period_id', $period_id)->where('centralize_code', '<>', NULL)
                                            ->whereRaw($centralize_code != '' ? ' centralize_code = "'.$centralize_code.'"' : 1)
                                            ->where('deleted_at', NULL);
                                        })->where('deleted_at', NULL)->get();

        return response()->json(['planilla' => $planilla, 'paymentschedule_details' => $paymentschedule_details, 'paymentschedule_details_group' => $paymentschedule_details->groupBy('paymentschedule.centralize_code'), 'centralize_code' => $centralize_code]);
    }

    public function planillas_pagos_people_search(){
        $search = \Request::query('q');
        $type = \Request::query('type');
        if($type == 0){
            $people = DB::connection('mysqlgobe')->table('contribuyente as p')
                        ->whereRaw($search ? '(PNombre like "%'.$search.'%" || SNombre like "%'.$search.'%" || APaterno like "%'.$search.'%" || AMaterno like "%'.$search.'%" || REPLACE(NombreCompleto, "  ", " ") like "%'.$search.'%" || N_Carnet like "%'.$search.'%")' : 1)
                        ->selectRaw('p.N_Carnet as id, CONCAT(p.PNombre, " ", p.SNombre) as first_name, CONCAT(p.APaterno, " ", p.AMaterno) as last_name, p.N_Carnet as ci, p.Profesion as profession')
                        ->groupBy('p.N_Carnet')
                        ->get();
            return response()->json($people);
        }else{
            $people = Person::whereRaw($search ? '(first_name like "%'.$search.'%" || last_name like "%'.$search.'%" || CONCAT(first_name, " ", last_name) like "%'.$search.'%" || ci like "%'.$search.'%")' : 1)
                            ->where('deleted_at', NULL)->get();
        }
        return response()->json($people);
    }
}
