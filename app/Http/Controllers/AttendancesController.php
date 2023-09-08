<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Carbon\Carbon;

// Models
use App\Models\DireccionesTipo;
use App\Models\Contract;

class AttendancesController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->custom_authorize('browse_attendances');
        return view('biometrics.attendances.browse');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->custom_authorize('add_paymentschedules');
        $direccion_administrativa_id = Auth::user()->direccion_administrativa_id;
        $tipo_da = DireccionesTipo::with(['direcciones_administrativas' => function($q) use($direccion_administrativa_id){
                            $q->whereRaw($direccion_administrativa_id ? "id = $direccion_administrativa_id" : 1)->where('estado', 1);
                        }])
                        ->whereHas('direcciones_administrativas', function($q) use($direccion_administrativa_id){
                            $q->whereRaw($direccion_administrativa_id ? "id = $direccion_administrativa_id" : 1);
                        })
                        ->where('estado', 1)->get();
        return view('biometrics.attendances.edit-add', compact('tipo_da'));
    }

    public function generate(Request $request){
        $contracts = Contract::whereHas('direccion_administrativa', function($q){
                            // $q->
                        })->where('start', '<=', $request->start)->where('finish', '<=', $request->finish)->get();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
