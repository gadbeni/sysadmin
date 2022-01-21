<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use DataTables;
use Carbon\Carbon;

// Models
use App\Models\PayrollPayment;
use App\Models\ChecksPayment;
use App\Models\Dependence;
use App\Models\ChecksBeneficiary;
use App\Models\Spreadsheet;
use App\Models\Planillahaber;

class SocialSecurityController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function checks_index(){
        return view('social-security.checks-browse');
    }

    public function checks_list($search = null){
        $paginate = request('paginate') ?? 10;
        $data = ChecksPayment::with(['user', 'check_beneficiary.type', 'planilla_haber.tipo', 'spreadsheet'])
                    ->whereRaw($search ? '(number like "'.$search.'%" or REPLACE(amount, ".", ",") like "'.$search.'%")' : 1)
                    ->OrWhereHas('user', function($query) use($search){
                        $query->whereRaw($search ? 'name like "%'.$search.'%"' : 1);
                    })
                    ->OrWhereHas('check_beneficiary', function($query) use($search){
                        $query->whereRaw($search ? 'full_name like "%'.$search.'%"' : 1);
                    })
                    ->where('deleted_at', NULL)->orderBy('id', 'DESC')->paginate($paginate);
        return view('social-security.checks-list', compact('data', 'search'));
    }

    public function checks_show($id){
        $check = ChecksPayment::with(['user', 'check_beneficiary.type'])->where('id', $id)->where('deleted_at', NULL)->first();
        return view('social-security.checks-read', compact('check'));        
    }

    public function checks_create(){
        $type = 'create';
        return view('social-security.checks-edit-add', compact('type'));
    }

    public function checks_store(Request $request){
        DB::beginTransaction();
        try {
            if($request->planilla_haber_id || $request->spreadsheet_id){
                ChecksPayment::create([
                    'user_id' => Auth::user()->id,
                    'planilla_haber_id' => $request->planilla_haber_id,
                    'spreadsheet_id' => $request->spreadsheet_id,
                    'number' => $request->number,
                    'amount' => $request->amount,
                    'checks_beneficiary_id' => $request->checks_beneficiary_id,
                    'date_print' => $request->date_print,
                    'observations' => $request->observations,
                    'status' => $request->status
                ]);
            }else{
                $beneficiary = ChecksBeneficiary::findOrFail($request->checks_beneficiary_id);
                $planillas = DB::connection('mysqlgobe')->table('planillahaberes as p')
                                ->where('p.Estado', 1)
                                ->where('p.Tplanilla', $request->t_planilla ?? 0)
                                ->whereRaw('(p.idGda=1 or p.idGda=2)')
                                ->where('p.Periodo', $request->periodo ?? 0)
                                ->where('p.Centralizado', 'SI')
                                ->whereRaw($request->afp ? 'p.Afp = '.$request->afp : 1)
                                ->groupBy('p.Afp', 'p.idPlanillaprocesada')
                                ->selectRaw('p.ID, SUM(p.Total_Ganado) as total_ganado, SUM(p.Total_Aportes_Afp) as total_aportes_afp, SUM(p.Riesgo_Comun) as riesgo_comun')
                                ->get();
                
                foreach ($planillas as $item) {
                    $amount = 0;
                    if($beneficiary->type->id == 4 || $beneficiary->type->id == 5 || $beneficiary->type->id == 6){
                        $aporte_patronal = ($item->total_ganado * 0.05) + $item->riesgo_comun;
                        $sip = $item->total_aportes_afp + $aporte_patronal - ($item->total_ganado * (5.5 / 100));
                        $aporte_solidario = $item->total_ganado * 0.035;
                        $aporte_vivienda = $item->total_ganado * 0.02;

                        switch ($beneficiary->type->id) {
                            case '4':
                                $amount = $sip + $aporte_solidario;
                                break;
                            case '5':
                                $amount = $sip;
                                break;
                            case '6':
                                $amount = $sip + $aporte_solidario + $aporte_vivienda;
                                break;
                            default:
                                $amount = 0;
                                break;
                        }
                    }else{
                        $amount = $item->total_ganado * ($beneficiary->type->percentage / 100);
                    }
                    
                    ChecksPayment::create([
                        'user_id' => Auth::user()->id,
                        'planilla_haber_id' => $item->ID,
                        'number' => $request->number,
                        'amount' => $amount,
                        'checks_beneficiary_id' => $request->checks_beneficiary_id,
                        'date_print' => $request->date_print,
                        'observations' => $request->observations,
                        'status' => $request->status
                    ]);
                }
            }

            DB::commit();
            // return redirect()->route($request->redirect ?? 'payments.index')->with(['message' => 'Pago agregado correctamente.', 'alert-type' => 'success']);
            return response()->json(['data' => 'success']);
        } catch (\Throwable $th) {
            DB::rollback();
            env('APP_DEBUG') ? dd($th) : null;
            // return redirect()->route($request->redirect ?? 'payments.index')->with(['message' => 'Ocurrió un error.', 'alert-type' => 'error']);
            return response()->json(['data' => 'error']);
        }
    }

    public function checks_edit($id){
        $type = 'edit';
        $data = ChecksPayment::with('spreadsheet')->where('id', $id)->where('deleted_at', NULL)->first();
        $planilla = DB::connection('mysqlgobe')->table('planillahaberes')->where('ID', $data->planilla_haber_id)->first();
        return view('social-security.checks-edit-add', compact('type', 'id', 'data', 'planilla'));
    }

    public function checks_update($id, Request $request){
        // dd($request);
        try {
            ChecksPayment::where('id', $id)->update([
                'number' => $request->number,
                'amount' => $request->amount,
                'beneficiary' => $request->beneficiary,
                'date_print' => $request->date_print,
                'observations' => $request->observations,
                'status' => $request->status
            ]);
            return redirect()->route('checks.index')->with(['message' => 'Cheque editado correctamente.', 'alert-type' => 'success']);
        } catch (\Throwable $th) {
            env('APP_DEBUG') ? dd($th) : null;
            return redirect()->route('checks.index')->with(['message' => 'Ocurrió un error.', 'alert-type' => 'error']);
        }
    }

    public function checks_delete($id, Request $request){
        try {
            ChecksPayment::where('id', $id)->update([
                'deleted_at' => Carbon::now()
            ]);
            return redirect()->route('checks.index')->with(['message' => 'Cheque eliminado correctamente.', 'alert-type' => 'success']);
        } catch (\Throwable $th) {
            env('APP_DEBUG') ? dd($th) : null;
            return redirect()->route('checks.index')->with(['message' => 'Ocurrió un error.', 'alert-type' => 'error']);
        }
    }

    public function checks_delete_multiple(Request $request){
        try {
            foreach ($request->id as $id) {
                ChecksPayment::where('id', $id)->update([
                    'deleted_at' => Carbon::now()
                ]);
            }
            return redirect()->route('checks.index')->with(['message' => 'Cheques eliminados correctamente.', 'alert-type' => 'success']);
        } catch (\Throwable $th) {
            env('APP_DEBUG') ? dd($th) : null;
            return redirect()->route('checks.index')->with(['message' => 'Ocurrió un error.', 'alert-type' => 'error']);
        }
    }

    // ===========================================================================
    
    public function payments_index(){
        return view('social-security.payments-browse');
    }

    public function payments_list(){
        $data = PayrollPayment::with(['planilla_haber', 'planilla_haber.tipo', 'planilla_haber.planilla_procesada', 'spreadsheet'])->where('deleted_at', NULL)->get();
        // return $data;

        return
            Datatables::of($data)
            ->addColumn('checkbox', function($row){
                return '<div><input type="checkbox" name="id[]" onclick="checkId()" value="'.$row->id.'" '.($row->spreadsheet_id ? 'disabled' : '').' /></div>';
            })
            ->addColumn('planilla_id', function($row){                    
                if($row->planilla_haber){
                    return  '<b>'.($row->planilla_haber->tipo ? $row->planilla_haber->tipo->Nombre : 'No definido').' - '.$row->planilla_haber->Periodo.'</b> <br><small>Planilla: </small>'.$row->planilla_haber->idPlanillaprocesada.' - '.($row->planilla_haber->Afp == 1 ? 'Futuro' : 'Previsión').'<br><small>Total ganado: </small>'.number_format($row->planilla_haber->planilla_procesada ? $row->planilla_haber->planilla_procesada->Monto : 0, 2, ',', '.');
                }elseif($row->spreadsheet){
                    return '<label class="label label-danger">Planilla manual</label> <br> <b>'.($row->spreadsheet->tipo_planilla_id == 1 ? 'Funcionamiento' : 'Inversión').' - '.$row->spreadsheet->year.str_pad($row->spreadsheet->month, 2, "0", STR_PAD_LEFT).'</b> <br> '.$row->spreadsheet->codigo_planilla.' - '.($row->spreadsheet->afp_id == 1 ? 'Futuro' : 'Previsión').'<br><small>Total ganado: </small>'.number_format($row->spreadsheet->total, 2, ',', '.');
                }
                return '';
            })
            ->addColumn('fpc_number', function($row){
                if($row->fpc_number){
                    $date = $row->date_payment_afp ? date('d/m/Y', strtotime($row->date_payment_afp)) : 'Pendiente';
                    return $row->fpc_number.'<br>'.$date;
                }else{
                    return null;
                }
            })
            ->addColumn('deposit_number', function($row){
                if($row->deposit_number){
                    $date = $row->date_payment_cc ? date('d/m/Y', strtotime($row->date_payment_cc)) : 'Pendiente';
                    return $row->deposit_number.'<br>'.$date;
                }else{
                    return null;
                }
            })
            ->addColumn('user', function($row){
                return $row->user->name;
            })
            ->addColumn('created_at', function($row){
                return date('d/m/Y H:i', strtotime($row->created_at)).'<br><small>'.Carbon::parse($row->created_at)->diffForHumans().'</small>';
            })
            ->addColumn('actions', function($row){
                $actions = '
                    <div class="no-sort no-click bread-actions text-right">
                        <a href="'.route('payments.show', ['payment' => $row->id]).'" title="Ver" class="btn btn-sm btn-warning view">
                            <i class="voyager-eye"></i> <span class="hidden-xs hidden-sm">Ver</span>
                        </a>
                        <a href="'.route('payments.edit', ['payment' => $row->id]).'" title="Editar" class="btn btn-sm btn-info edit">
                            <i class="voyager-edit"></i> <span class="hidden-xs hidden-sm">Editar</span>
                        </a>
                        <button type="button" onclick="deleteItem('."'".route('payments.delete', ['payment' => $row->id])."'".')" data-toggle="modal" data-target="#delete-modal" title="Eliminar" class="btn btn-sm btn-danger edit">
                            <i class="voyager-trash"></i> <span class="hidden-xs hidden-sm">Borrar</span>
                        </button>
                    </div>';
                return $actions;
            })
            ->rawColumns(['checkbox', 'planilla_id', 'fpc_number', 'deposit_number', 'user', 'created_at', 'actions'])
            ->make(true);
    }

    public function payments_show($id){
        $payment = PayrollPayment::where('id', $id)->where('deleted_at', NULL)->first();
        if(request('ajax')){
            return response()->json(['payment' => $payment]);
        }

        return view('social-security.payments-read', compact('payment'));        
    }

    public function payments_create(){
        $type = 'create';
        return view('social-security.payments-edit-add', compact('type'));
    }

    public function payments_store(Request $request){
        DB::beginTransaction();
        try {
            if($request->planilla_haber_id || $request->spreadsheet_id){
                PayrollPayment::create([
                    'user_id' => Auth::user()->id,
                    'planilla_haber_id' => $request->planilla_haber_id,
                    'spreadsheet_id' => $request->spreadsheet_id,
                    'date_payment_afp' => $request->date_payment_afp,
                    'payment_id' => $request->payment_id,
                    'penalty_payment' => $request->penalty_payment,
                    'fpc_number' => $request->fpc_number,
                    'date_payment_cc' => $request->date_payment_cc,
                    'gtc_number' => $request->gtc_number,
                    'recipe_number' => $request->recipe_number,
                    'deposit_number' => $request->deposit_number,
                    'check_id' => $request->check_id,
                    'penalty_check' => $request->penalty_check
                ]);

                // Actualizar estados de cheques de afp
                if($request->date_payment_afp){
                    ChecksPayment::whereHas('check_beneficiary.type', function($q){
                        $q->where('name', 'not like', '%salud%');
                    })->where('planilla_haber_id', $request->planilla_haber_id)->where('deleted_at', NULL)->update(['status' => 2]);
                }

                // Actualizar estados de cheques de caja de salud
                if($request->date_payment_cc){
                    ChecksPayment::whereHas('check_beneficiary.type', function($q){
                        $q->where('name', 'like', '%salud%');
                    })->where('planilla_haber_id', $request->planilla_haber_id)->where('deleted_at', NULL)->update(['status' => 2]);
                }

            }else{
                $planillas = DB::connection('mysqlgobe')->table('planillahaberes as p')
                                    ->join('planillaprocesada as pp', 'pp.ID', 'p.idPlanillaprocesada')
                                    ->where('p.Estado', 1)
                                    ->where('p.Tplanilla', $request->t_planilla ?? 0)
                                    ->whereRaw('(p.idGda=1 or p.idGda=2)')
                                    ->where('p.Periodo', $request->periodo ?? 0)
                                    ->where('p.Centralizado', 'SI')
                                    ->whereRaw($request->afp ? 'p.Afp = '.$request->afp : 1)
                                    ->groupBy('p.Afp', 'p.idPlanillaprocesada')
                                    ->selectRaw('p.ID, pp.Monto as monto')
                                    ->get();
                $total_pago = $planillas->sum('monto');
                // dd($planillas, $total_pago);
                foreach ($planillas as $item) {
                    $porcentaje = ($item->monto * 100) / $total_pago;
                    PayrollPayment::create([
                        'user_id' => Auth::user()->id,
                        'planilla_haber_id' => $item->ID,
                        'date_payment_afp' => $request->date_payment_afp,
                        'payment_id' => $request->payment_id,
                        'penalty_payment' => $request->penalty_payment * ($porcentaje/100),
                        'fpc_number' => $request->fpc_number,
                        'date_payment_cc' => $request->date_payment_cc,
                        'gtc_number' => $request->gtc_number,
                        'recipe_number' => $request->recipe_number,
                        'deposit_number' => $request->deposit_number,
                        'check_id' => $request->check_id,
                        'penalty_check' => $request->penalty_check * ($porcentaje/100)
                    ]);

                    // Actualizar estados de cheques de afp
                    if($request->date_payment_afp){
                        ChecksPayment::whereHas('check_beneficiary.type', function($q){
                            $q->where('name', 'not like', '%salud%');
                        })->where('planilla_haber_id', $item->ID)->where('deleted_at', NULL)->update(['status' => 2]);
                    }

                    // Actualizar estados de cheques de caja de salud
                    if($request->date_payment_cc){
                        ChecksPayment::whereHas('check_beneficiary.type', function($q){
                            $q->where('name', 'like', '%salud%');
                        })->where('planilla_haber_id', $item->ID)->where('deleted_at', NULL)->update(['status' => 2]);
                    }
                }
            }

            DB::commit();

            // return redirect()->route($request->redirect ?? 'payments.index')->with(['message' => 'Pago agregado correctamente.', 'alert-type' => 'success']);
            return response()->json(['data' => 'success']);
        } catch (\Throwable $th) {
            DB::rollback();
            // env('APP_DEBUG') ? dd($th) : null;
            // return redirect()->route($request->redirect ?? 'payments.index')->with(['message' => 'Ocurrió un error.', 'alert-type' => 'error']);
            return response()->json(['data' => 'error']);
        }
    }

    public function payments_edit($id){
        $type = 'edit';
        $data = PayrollPayment::with('spreadsheet')->where('id', $id)->where('deleted_at', NULL)->first();
        $planilla = DB::connection('mysqlgobe')->table('planillahaberes')->where('ID', $data->planilla_haber_id)->first();
        return view('social-security.payments-edit-add', compact('type', 'id', 'data', 'planilla'));
    }

    public function payments_update($id, Request $request){
        // dd($request->all());
        try {
            PayrollPayment::where('id', $id)->update([
                'date_payment_afp' => $request->date_payment_afp,
                'fpc_number' => $request->fpc_number,
                'payment_id' => $request->payment_id,
                'penalty_payment' => $request->penalty_payment,
                'date_payment_cc' => $request->date_payment_cc,
                'gtc_number' => $request->gtc_number,
                'recipe_number' => $request->recipe_number,
                'deposit_number' => $request->deposit_number,
                'check_id' => $request->check_id,
                'penalty_check' => $request->penalty_check
            ]);

            $planilla = DB::connection('mysqlgobe')->table('planillahaberes')->where('idPlanillaprocesada', $request->planilla_haber_id)->where('Afp', $request->afp_edit)->first();
            // Actualizar estados de cheques de afp
            if($request->date_payment_afp){
                ChecksPayment::whereHas('check_beneficiary.type', function($q){
                    $q->where('name', 'not like', '%salud%');
                })->where('planilla_haber_id', $planilla->ID)->where('deleted_at', NULL)->update(['status' => 2]);
            }

            // Actualizar estados de cheques de caja de salud
            if($request->date_payment_cc){
                ChecksPayment::whereHas('check_beneficiary.type', function($q){
                    $q->where('name', 'like', '%salud%');
                })->where('planilla_haber_id', $planilla->ID)->where('deleted_at', NULL)->update(['status' => 2]);
            }

            return redirect()->route('payments.index')->with(['message' => 'Pago editado correctamente.', 'alert-type' => 'success']);
        } catch (\Throwable $th) {
            env('APP_DEBUG') ? dd($th) : null;
            return redirect()->route('payments.index')->with(['message' => 'Ocurrió un error.', 'alert-type' => 'error']);
        }
    }

    public function payments_delete($id, Request $request){
        try {
            PayrollPayment::where('id', $id)->update([
                'deleted_at' => Carbon::now()
            ]);
            return redirect()->route('payments.index')->with(['message' => 'Pago eliminado correctamente.', 'alert-type' => 'success']);
        } catch (\Throwable $th) {
            env('APP_DEBUG') ? dd($th) : null;
            return redirect()->route('payments.index')->with(['message' => 'Ocurrió un error.', 'alert-type' => 'error']);
        }
    }

    public function payments_update_multiple(Request $request){
        $payroll_payments = PayrollPayment::whereIn('id', $request->id)->get();
        $planillas_ids = $payroll_payments->pluck('planilla_haber_id')->toArray();
        $planillas = DB::connection('mysqlgobe')->table('planillahaberes as ph')
                        ->join('planillaprocesada as pp', 'pp.ID', 'ph.idPlanillaprocesada')
                        ->whereIn('ph.ID', $planillas_ids)
                        ->select('pp.Monto', 'ph.id as id_planilla_haber')->orderBy('ph.ID', 'DESC')->get();
        $total_pago = $planillas->sum('Monto');
        
        DB::beginTransaction();

        try {
            $cont = 0;
            foreach ($request->id as $id) {
                $porcentaje = ($planillas[$cont]->Monto * 100) / $total_pago;
                $payroll_payment = PayrollPayment::findOrFail($id);
                $payroll_payment->date_payment_afp = $request->date_payment_afp;
                $payroll_payment->fpc_number = $request->fpc_number;
                $payroll_payment->payment_id = $request->payment_id;
                $payroll_payment->penalty_payment = $request->rate_penalty_payment ? $request->penalty_payment * ($porcentaje/100) : $payroll_payment->penalty_payment;
                $payroll_payment->date_payment_cc = $request->date_payment_cc;
                $payroll_payment->gtc_number = $request->gtc_number;
                $payroll_payment->recipe_number = $request->recipe_number;
                $payroll_payment->deposit_number = $request->deposit_number;
                $payroll_payment->check_id = $request->check_id;
                $payroll_payment->penalty_check = $request->rate_penalty_check ? $request->penalty_check * ($porcentaje/100) : $payroll_payment->penalty_check;
                $payroll_payment->save();
                $cont++;
            }
            DB::commit();
            return redirect()->route('payments.index')->with(['message' => 'Pagos editados correctamente.', 'alert-type' => 'success']);
        } catch (\Throwable $th) {
            DB::rollback();
            env('APP_DEBUG') ? dd($th) : null;
            return redirect()->route('payments.index')->with(['message' => 'Ocurrió un error.', 'alert-type' => 'error']);
        }
    }

    public function payments_delete_multiple(Request $request){
        try {
            foreach ($request->id as $id) {
                PayrollPayment::where('id', $id)->update([
                    'deleted_at' => Carbon::now()
                ]);
            }
            return redirect()->route('payments.index')->with(['message' => 'Pagos eliminados correctamente.', 'alert-type' => 'success']);
        } catch (\Throwable $th) {
            env('APP_DEBUG') ? dd($th) : null;
            return redirect()->route('payments.index')->with(['message' => 'Ocurrió un error.', 'alert-type' => 'error']);
        }
    }

    // ======================================================================

    public function print_index(){
        $dependences = Dependence::where('deleted_at', NULL)->get();
        return view('social-security.print.browse', compact('dependences'));
    }

    public function print_form(Request $request){
        $dependence = Dependence::findOrFail($request->dependence_id);
        $planillas = DB::connection('mysqlgobe')->table('planillahaberes as p')
                        ->join('tplanilla as tp', 'tp.id', 'p.Tplanilla')
                        ->whereRaw("p.idPlanillaprocesada IN (".$request->nro_planilla.")")
                        ->whereRaw($request->afp ? "p.Afp = ".$request->afp : 1)
                        ->select('p.Total_Ganado', 'tp.Nombre as tipo_planilla', 'p.Anio as year', 'p.Mes as month')
                        ->get();
        return view('social-security.print.form-gtc', compact('dependence', 'planillas'));
    }
}