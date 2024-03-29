<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\ChecksCategory;
use App\Models\Check;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Carbon;
use App\Models\ChecksHistory;

class CheckController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
   
    public function index()
    {
        $tipos = ChecksCategory::all();
        $check = DB::table('checks as c')
                ->join('checks_categories as cc', 'cc.id', 'c.checkcategoria_id')
                ->select('cc.name', 'c.resumen', 'c.id', 'c.status')
                ->where('c.deleted_at', NULL)->get();

        return view('check.browse', compact('tipos', 'check'));
    }


    public function store(Request $request)
    {
        // return $request;
        DB::beginTransaction();
        try {
            // return count($request->nrocheque);
            $i=0;

            // $arr = array('checkcategoria_id' => $request->checkcategoria_id, 'nrocheque' => $request->nrocheque[$i],
            //                 'resumen' => $request->resumen[$i], 'nromemo' => $request->nromemo, 'nroprev' => $request->nroprev, 'nrodev' => $request->nrodev,
            //                 'deposito' => $request->deposito, 'fechacheque' => $request->fechacheque, 'monto' => $request->monto[$i], 'observacion' => $request->observacion);

            //                 // return json_encode($arr);

            // $check = Check::create(['user_id' => Auth::user()->id, 'checkcategoria_id'=> $request->checkcategoria_id, 
            //                     'resumen' => json_encode( ['checkcategoria_id' => $request->checkcategoria_id, 'nrocheque' => $request->nrocheque[$i],
            //                     'resumen' => $request->resumen[$i], 'nromemo' => $request->nromemo, 'nroprev' => $request->nroprev, 'nrodev' => $request->nrodev,
            //                     'deposito' => $request->deposito, 'fechacheque' => $request->fechacheque, 'monto' => $request->monto[$i], 'observacion' => $request->observacion])]);
            // return $check;
            

            while($i < count($request->nrocheque))   
            {     
                $check = Check::create(['user_id' => Auth::user()->id, 'checkcategoria_id'=> $request->checkcategoria_id, 
                                'resumen' => json_encode( ['checkcategoria_id' => $request->checkcategoria_id, 'nrocheque' => $request->nrocheque[$i],
                                'resumen' => $request->resumen[$i], 'nromemo' => $request->nromemo, 'nroprev' => $request->nroprev, 'nrodev' => $request->nrodev,
                                'deposito' => $request->deposito, 'fechacheque' => $request->fechacheque, 'monto' => $request->monto[$i], 'observacion' => $request->observacion])]);
                

                ChecksHistory::create(['check_id' => $check->id, 'office_id' => 1,'user_id' => Auth::user()->id]);
                $i++;
            }
            // return $check;

            DB::commit();
            return redirect()->route('checks.index')->with(['message' => 'Cheque Registrado Correctamente.', 'alert-type' => 'success']);
        } catch (\Throwable $th) {
            DB::rollBack();
            return redirect()->route('checks.index')->with(['message' => 'Ocurrió un error', 'alert-type' => 'error']);

        }
    }

    public function update_checks(Request $request)
    {
        DB::beginTransaction();
        try {            
           
            Check::where('id', $request->id)->update(['checkcategoria_id'=> $request->checkcategoria_id, 'resumen'=> json_encode($request->all())]);

            


            DB::commit();
            return redirect()->route('checks.index')->with(['message' => 'Cheque Actualizado Correctamente.', 'alert-type' => 'success']);
        } catch (\Throwable $th) {
            DB::rollBack();
            return redirect()->route('checks.index')->with(['message' => 'Ocurrió un error', 'alert-type' => 'error']);

        }
    }

    public function devolver_checks(Request $request)
    {
        Check::where('id',$request->id)->update(['status' => 'observado']);
        ChecksHistory::create(['check_id' => $request->id, 'office_id' => 1, 'status' => 'observado', 'observacion' => $request->observacion,'user_id' => Auth::user()->id]);

        return redirect()->route('checks.index')->with(['message' => 'Cheque Devolvido Exitosamente.', 'alert-type' => 'success']);
    }   

    public function aprobar_checks(Request $request)
    {

        Check::where('id',$request->id)->update(['status' => 'aprobado']);

        ChecksHistory::create(['check_id' => $request->id, 'status' => 'aprobado', 'observacion' => $request->observacion,'user_id' => Auth::user()->id]);

        return redirect()->route('checks.index')->with(['message' => 'Cheque Aprobado Exitosamente.', 'alert-type' => 'success']);
    }

    public function entregar_checks(Request $request)
    {

        Check::where('id',$request->id)->update(['status' => 'entregado', 'fentregar'=>$request->fentregar]);

        ChecksHistory::create(['check_id' => $request->id, 'status' => 'entregado','fentregar'=>$request->fentregar, 'observacion' => $request->observacion,'user_id' => Auth::user()->id]);

        return redirect()->route('checks.index')->with(['message' => 'Cheque Entregado Exitosamente.', 'alert-type' => 'success']);
    }
    public function habilitar_checks(Request $request)
    {

        Check::where('id',$request->id)->update(['status' => 'habilitado']);

        ChecksHistory::create(['check_id' => $request->id, 'status' => 'habilitado', 'observacion' => $request->observacion,'user_id' => Auth::user()->id]);

        return redirect()->route('checks.index')->with(['message' => 'Cheque Habilitado Exitosamente.', 'alert-type' => 'success']);
    }

    public function destroy(Request $request)
    {
        // return $request;
        Check::where('id',$request->id)->update(['deleted_at' => Carbon::now(),'status' => 0 ]);
        ChecksHistory::create(['check_id' => $request->id, 'status' => 0, 'office_id' => 1,'observacion' => $request->observacion,'user_id' => Auth::user()->id]);

        return redirect()->route('checks.index')->with(['message' => 'Cheque Eliminado Exitosamente.', 'alert-type' => 'success']);
    }

    protected function report_view()
    {
        return view('reports.check.check-browse');
    }
}