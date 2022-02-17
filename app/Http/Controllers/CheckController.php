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
        DB::beginTransaction();
        try {
            // return $request;
           
            $check = Check::create(['user_id' => Auth::user()->id, 'checkcategoria_id'=> $request->checkcategoria_id, 'resumen'=> json_encode($request->all())]);
            ChecksHistory::create(['check_id' => $check->id, 'office_id' => 1,'user_id' => Auth::user()->id]);


            DB::commit();
            return redirect()->route('checks.index')->with(['message' => 'Cheque Registrado Correctamente.', 'alert-type' => 'success']);
        } catch (\Throwable $th) {
            DB::rollBack();
            return redirect()->route('checks.index')->with(['message' => 'Ocurrió un error.', 'alert-type' => 'error']);

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
            return redirect()->route('checks.index')->with(['message' => 'Ocurrió un error.', 'alert-type' => 'error']);

        }
    }

    public function devolver_checks(Request $request)
    {
        Check::where('id',$request->id)->update(['status' => 1]);
        return redirect()->route('checks.index')->with(['message' => 'Cheque Devolvido Exitosamente.', 'alert-type' => 'success']);
    }   

    public function entregar_checks(Request $request)
    {

        Check::where('id',$request->id)->update(['status' => 2]);
        return redirect()->route('checks.index')->with(['message' => 'Cheque Entregado Exitosamente.', 'alert-type' => 'success']);
    }

    public function destroy(Request $request)
    {
        Check::where('id',$request->id)->update(['deleted_at' => Carbon::now()]);
        return redirect()->route('checks.index')->with(['message' => 'Cheque Eliminado Exitosamente.', 'alert-type' => 'success']);
    }
}