<?php

namespace App\Http\Controllers;

use App\Models\Stipend;
use Illuminate\Http\Request;

class StipendController extends Controller
{
    public function index()
    {
        $data = Stipend::all();
        return view('additionalsteets.index', compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
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

    public function destroy(Request $request)
    {
        // return $request;
        Stipend::find($request->id)->delete();
        return redirect()->route('planillas_adicionales.index')->with(['message' => 'Eliminado Exitosamente.', 'alert-type' => 'success']);

    }
}
