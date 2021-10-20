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

class SocialSecurityController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function checks_index(){
        return view('social-security.checks-browse');
    }

    public function checks_list(){
        $data = ChecksPayment::with(['user', 'check_beneficiary.type'])->where('deleted_at', NULL)->get();
        // return $data[0]->check_beneficiary;
        return
            DataTables::of($data)
            ->addColumn('details', function($row){
                $planilla = DB::connection('mysqlgobe')->table('planillahaberes')->where('ID', $row->planilla_haber_id)->first();
                $status = '';
                switch ($row->status) {
                    case '0':
                        $status = '<label class="label label-danger">anulado</label>';
                        break;
                    case '1':
                        $status = '<label class="label label-warning">Pendiente</label>';
                        break;
                    case '2':
                        $status = '<label class="label label-success">Pagado</label>';
                        break;
                    
                    default:
                        # code...
                        break;
                }
                return '<p>
                            <b><small>N&deg;:</small></b> '.$row->number.' <br>
                            <b><small>Planilla:</small></b> '.$planilla->idPlanillaprocesada.' <br>
                            <b><small>Monto:</small></b> '.$row->amount.' <br>
                            '.$status.'
                        </p>';
            })
            ->addColumn('beneficiary', function($row){
                return $row->check_beneficiary->full_name.'<br><small>'.$row->check_beneficiary->type->name.'<small>';
            })
            ->addColumn('user', function($row){
                return $row->user->name;
            })
            ->addColumn('date_print', function($row){
                return date('d/m/Y', strtotime($row->date_print)).'<br><small>'.Carbon::parse($row->date_print)->diffForHumans().'</small>';
            })
            ->addColumn('date_expire', function($row){
                $date_expire = date('Y-m-d', strtotime($row->date_print.' +29 days'));
                return '<span style="'.($date_expire <= date('Y-m-d') && $row->status == 1 ? 'color: red' : '').'">'.date('d/m/Y', strtotime($date_expire)).'<br><small>'.Carbon::parse($date_expire)->diffForHumans().'</small></span>';
            })
            ->addColumn('created_at', function($row){
                return date('d/m/Y H:i', strtotime($row->created_at)).'<br><small>'.Carbon::parse($row->created_at)->diffForHumans().'</small>';
            })
            ->addColumn('actions', function($row){
                $actions = '
                    <div class="no-sort no-click bread-actions text-right">
                        <a href="'.route('checks.show', ['check' => $row->id]).'" title="Ver" class="btn btn-sm btn-warning view">
                            <i class="voyager-eye"></i> <span class="hidden-xs hidden-sm">Ver</span>
                        </a>
                        <a href="'.route('checks.edit', ['check' => $row->id]).'" title="Editar" class="btn btn-sm btn-info edit">
                            <i class="voyager-edit"></i> <span class="hidden-xs hidden-sm">Editar</span>
                        </a>
                        <button onclick="deleteItem('."'".route('checks.delete', ['check' => $row->id])."'".')" data-toggle="modal" data-target="#delete-modal" title="Eliminar" class="btn btn-sm btn-danger edit">
                            <i class="voyager-trash"></i> <span class="hidden-xs hidden-sm">Borrar</span>
                        </a>
                    </div>';
                return $actions;
            })
            ->rawColumns(['details', 'beneficiary', 'user', 'date_print', 'date_expire', 'created_at', 'actions'])
            ->make(true);
    }

    public function checks_show($id){
        $check = ChecksPayment::where('id', $id)->where('deleted_at', NULL)->first();
        return view('social-security.checks-read', compact('check'));        
    }

    public function checks_create(){
        $type = 'create';
        return view('social-security.checks-edit-add', compact('type'));
    }

    public function checks_store(Request $request){
        // dd($request);
        try {
            ChecksPayment::create([
                'user_id' => Auth::user()->id,
                'planilla_haber_id' => $request->planilla_haber_id,
                'number' => $request->number,
                'amount' => $request->amount,
                'checks_beneficiary_id' => $request->checks_beneficiary_id,
                'date_print' => $request->date_print,
                'observations' => $request->observations
            ]);
            // return redirect()->route($request->redirect ?? 'payments.index')->with(['message' => 'Pago agregado correctamente.', 'alert-type' => 'success']);
            return response()->json(['data' => 'success']);
        } catch (\Throwable $th) {
            env('APP_DEBUG') ? dd($th) : null;
            // return redirect()->route($request->redirect ?? 'payments.index')->with(['message' => 'Ocurrió un error.', 'alert-type' => 'error']);
            return response()->json(['data' => 'error']);
        }
    }

    public function checks_edit($id){
        $type = 'edit';
        $data = ChecksPayment::with('check_beneficiary.type')->where('id', $id)->where('deleted_at', NULL)->first();
        return view('social-security.checks-edit-add', compact('type', 'id', 'data'));
    }

    public function checks_update($id, Request $request){
        // dd($request);
        try {
            ChecksPayment::where('id', $id)
            ->update([
                'amount' => $request->amount,
                'beneficiary' => $request->beneficiary,
                'date_print' => $request->date_print,
                'observations' => $request->observations
            ]);
            return redirect()->route('checks.index')->with(['message' => 'Cheque editado correctamente.', 'alert-type' => 'success']);
        } catch (\Throwable $th) {
            env('APP_DEBUG') ? dd($th) : null;
            return redirect()->route('checks.index')->with(['message' => 'Ocurrió un error.', 'alert-type' => 'error']);
        }
    }

    public function checks_delete($id, Request $request){
        try {
            ChecksPayment::where('id', $id)
            ->update([
                'deleted_at' => Carbon::now()
            ]);
            return redirect()->route('checks.index')->with(['message' => 'Cheque eliminado correctamente.', 'alert-type' => 'success']);
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
        $data = PayrollPayment::where('deleted_at', NULL)->get();
        // return $data;

        return
            Datatables::of($data)
            ->addColumn('planilla_id', function($row){
                $planilla = DB::connection('mysqlgobe')->table('planillahaberes')->where('ID', $row->planilla_haber_id)->first();
                return $planilla->idPlanillaprocesada;
            })
            ->addColumn('fpc_number', function($row){
                if($row->fpc_number){
                    $date = $row->date_payment_afp ? date('d/m/Y H:i', strtotime($row->date_payment_afp)) : 'Pendiente';
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
                        <button onclick="deleteItem('."'".route('payments.delete', ['payment' => $row->id])."'".')" data-toggle="modal" data-target="#delete-modal" title="Eliminar" class="btn btn-sm btn-danger edit">
                            <i class="voyager-trash"></i> <span class="hidden-xs hidden-sm">Borrar</span>
                        </a>
                    </div>';
                return $actions;
            })
            ->rawColumns(['planilla_id', 'fpc_number', 'deposit_number', 'user', 'created_at', 'actions'])
            ->make(true);
    }

    public function payments_show($id){
        $payment = PayrollPayment::where('id', $id)->where('deleted_at', NULL)->first();
        return view('social-security.payments-read', compact('payment'));        
    }

    public function payments_create(){
        $type = 'create';
        return view('social-security.payments-edit-add', compact('type'));
    }

    public function payments_store(Request $request){
        try {
            PayrollPayment::create([
                'user_id' => Auth::user()->id,
                'planilla_haber_id' => $request->planilla_haber_id,
                'date_payment_afp' => $request->date_payment_afp,
                'payment_id' => $request->payment_id,
                'penalty_payment' => $request->penalty_payment,
                'fpc_number' => $request->fpc_number,
                'date_payment_cc' => $request->date_payment_cc,
                'gtc_number' => $request->gtc_number,
                'check_number' => $request->check_number,
                'recipe_number' => $request->recipe_number,
                'deposit_number' => $request->deposit_number,
                'check_id' => $request->check_id,
                'penalty_check' => $request->penalty_check
            ]);
            // return redirect()->route($request->redirect ?? 'payments.index')->with(['message' => 'Pago agregado correctamente.', 'alert-type' => 'success']);
            return response()->json(['data' => 'success']);
        } catch (\Throwable $th) {
            // env('APP_DEBUG') ? dd($th) : null;
            // return redirect()->route($request->redirect ?? 'payments.index')->with(['message' => 'Ocurrió un error.', 'alert-type' => 'error']);
            return response()->json(['data' => 'error']);
        }
    }

    public function payments_edit($id){
        $type = 'edit';
        $data = PayrollPayment::where('id', $id)->where('deleted_at', NULL)->first();
        return view('social-security.payments-edit-add', compact('type', 'id', 'data'));
    }

    public function payments_update($id, Request $request){
        // dd($request);
        try {
            PayrollPayment::where('id', $id)
            ->update([
                'planilla_haber_id' => $request->planilla_haber_id,
                'date_payment_afp' => $request->date_payment_afp,
                'fpc_number' => $request->fpc_number,
                'payment_id' => $request->payment_id,
                'penalty_payment' => $request->penalty_payment,
                'date_payment_cc' => $request->date_payment_cc,
                'gtc_number' => $request->gtc_number,
                'check_number' => $request->check_number,
                'recipe_number' => $request->recipe_number,
                'deposit_number' => $request->deposit_number,
                'check_id' => $request->check_id,
                'penalty_check' => $request->penalty_check
            ]);
            return redirect()->route('payments.index')->with(['message' => 'Pago agregado correctamente.', 'alert-type' => 'success']);
        } catch (\Throwable $th) {
            env('APP_DEBUG') ? dd($th) : null;
            return redirect()->route('payments.index')->with(['message' => 'Ocurrió un error.', 'alert-type' => 'error']);
        }
    }

    public function payments_delete($id, Request $request){
        try {
            PayrollPayment::where('id', $id)
            ->update([
                'deleted_at' => Carbon::now()
            ]);
            return redirect()->route('payments.index')->with(['message' => 'Pago eliminado correctamente.', 'alert-type' => 'success']);
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
                        ->where('idPlanillaprocesada', $request->nro_planilla)
                        ->whereRaw($request->afp ? "p.Afp = ".$request->afp : 1)
                        ->select('p.Total_Ganado', 'tp.Nombre as tipo_planilla', 'p.Anio as year', 'p.Mes as month')
                        ->get();
        return view('social-security.print.form-gtc', compact('dependence', 'planillas'));
    }
}
