<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use DataTables;

// Models
use App\Models\Cashier;
use App\Models\CashiersPayment;

class PlanillasController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    public function planilla_index(){
        return view('planillas.procesadas-browse');
    }

    public function planilla_search(Request $request){
        $centralizada = $request->centralizada;
        if($centralizada){
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
                            ->orderByRaw("FIELD(p.idDa, '9','16','10','15','8','13','37','41','42','50','55','61','64','6','62','69','5','17','48','53')")
                            ->get();
        }else{
            $planilla = DB::connection('mysqlgobe')->table('planillahaberes as p')
                            ->join('planillaprocesada as pp', 'pp.id', 'p.idPlanillaprocesada')
                            ->join('tplanilla as tp', 'tp.id', 'p.Tplanilla')
                            ->where('p.idPlanillaprocesada', $request->planilla_id ?? 0)
                            ->whereRaw($request->afp ? 'p.Afp = '.$request->afp : 1)
                            ->select('p.*', 'p.ITEM as item', 'tp.Nombre as tipo_planilla', 'pp.Estado as estado_planilla_procesada')
                            ->orderByRaw("FIELD(p.idDa, '9','16','10','15','8','13','37','41','42','50','55','61','64','6','62','69','5','17','48','53')")
                            ->get();
        }
        
        return view('planillas.procesadas-search', compact('planilla'));
    }

    public function planilla_details_update_status(Request $request){
        $cashier = Cashier::with(['movements', 'payments'])->where('id', $request->cashier_id)->first();
        $payments = $cashier->payments->where('deleted_at', NULL)->sum('amount');
        $movements = $cashier->movements->where('type', 'ingreso')->where('deleted_at', NULL)->sum('amount') - $cashier->movements->where('type', 'egreso')->where('deleted_at', NULL)->sum('amount');
        $total = $movements - $payments;
        // dd($total);
        if($total - $request->amount < 0){
            return response()->json(['error' => 1, 'message' => 'No tiene suficiente dinero en caja.']);
        }
        try {
            DB::connection('mysqlgobe')->table('planillahaberes')
            ->where('id', $request->id)
            ->update([
                'pagada' => $request->status
            ]);

            $payment = CashiersPayment::create([
                'cashier_id' => $request->cashier_id,
                'planilla_haber_id' => $request->id,
                'amount' => $request->amount,
                'description' => 'Pago de haberes mensuales a '.$request->name.'.'
            ]);

            return response()->json(['success' => 1, 'payment_id' => $payment->id]);
        } catch (\Throwable $th) {
            return response()->json(['error' => 1]);
        }
    }

    public function planilla_update_status(Request $request){
        $id = $request->id;
        // Si el id no es un array lo convertimos
        if(!is_array($id)){
            try {
                $id = explode(",", $id);
            } catch (\Throwable $th) {
                $id = [];
            }
        }
        // dd($id);
        try {
            DB::connection('mysqlgobe')->table('planillaprocesada')
            ->whereIn('id', $id)
            ->update([
                'Estado' => $request->status,
                // 'Fecha_Pago' => date('Y-m-d')
            ]);

            return response()->json(['success' => 1]);
        } catch (\Throwable $th) {
            return response()->json(['error' => 1]);
        }
    }

    public function planillas_pago_pdf($id){
        $payment = CashiersPayment::findOrFail($id);
        $planilla = DB::connection('mysqlgobe')->table('planillahaberes')
            ->where('id', $payment->planilla_haber_id)->first();
        // dd($payment, $planilla);
    }
}
