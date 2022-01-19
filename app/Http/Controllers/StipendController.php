<?php

namespace App\Http\Controllers;

use App\Models\Stipend;
use Carbon\Carbon;
use Illuminate\Http\Request;

class StipendController extends Controller
{
    public function index()
    {
        $data = Stipend::where('deleted_at', NULL)->orderBy('id','DESC')->get();
        return view('additionalsteets.index', compact('data'));
    }

    public function store(Request $request)
    {

        try {
            Stipend::create($request->all());
            
            return redirect()->route('planillas_adicionales.index')->with(['message' => 'Pago Registrado Exitosamente.', 'alert-type' => 'success']);
        } catch (\Throwable $th) {
            return redirect()->route('planillas_adicionales.index')->with(['message' => 'Ocurrió un error.', 'alert-type' => 'error']);
        }
        // return $request;
    }

    public function update_planilla(Request $request)
    {
        try {
            Stipend::where('id',$request->id)->update([
                'ci'=> $request->ci,
                'funcionario' => $request->funcionario,
                'cargo' => $request->cargo,
                'sueldo' => $request->sueldo,
                'rciva' => $request->rciva,
                'total' => $request->total,
                'liqpagable' => $request->liqpagable,
                'montofactura' => $request->montofactura,
                'dia' => $request->dia
            ]);
            
            return redirect()->route('planillas_adicionales.index')->with(['message' => 'Registro Actualizado.', 'alert-type' => 'success']);
        } catch (\Throwable $th) {
            return redirect()->route('planillas_adicionales.index')->with(['message' => 'Ocurrió un error.', 'alert-type' => 'error']);
        }
        // return $request;
    }

    public function destroy(Request $request)
    {
        // return $request;
        // return Stipend::find($request->id);
        Stipend::where('id',$request->id)->update(['deleted_at' => Carbon::now()]);
        return redirect()->route('planillas_adicionales.index')->with(['message' => 'Eliminado Exitosamente.', 'alert-type' => 'success']);

    }
}
