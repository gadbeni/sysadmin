<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

// Models
use App\Models\Cashier;
use App\Models\CashiersMovement;

class CashiersController extends Controller
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
        //
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
        $cashier = Cashier::where('user_id', $request->user_id)->where('closed_at', NULL)->where('deleted_at', NULL)->first();

        if(!$cashier){
            try {
                $cashier = Cashier::create([
                    'user_id' => $request->user_id,
                    'title' => $request->title,
                    'observations' => $request->observations,
                    'status' => 'abierta'
                ]);

                if($request->amount){
                    CashiersMovement::create([
                        'user_id' => Auth::user()->id,
                        'cashier_id' => $cashier->id,
                        'amount' => $request->amount,
                        'description' => 'Monto de apertura de caja.',
                        'type' => 'ingreso'
                    ]);
                }
    
                return redirect()->route('voyager.cashiers.index')->with(['message' => 'Registro guardado exitosamente.', 'alert-type' => 'success']);
            } catch (\Throwable $th) {
                //throw $th;
                return redirect()->route('voyager.cashiers.index')->with(['message' => 'Ocurrió un error.', 'alert-type' => 'error']);
            }
        }else{
            return redirect()->route('voyager.cashiers.index')->with(['message' => 'El usuario seleccionado tiene una caja abierta.', 'alert-type' => 'warning']);
        }
    }

    public function add_amount($id)
    {
        return view('vendor.voyager.cashiers.add-amount', compact('id'));
    }

    public function amount_store(Request $request)
    {
        try {
            CashiersMovement::create([
                'user_id' => Auth::user()->id,
                'cashier_id' => $request->cashier_id,
                'amount' => $request->amount,
                'description' => $request->description,
                'type' => $request->type
            ]);

            if($request->id){
                $cashier = Cashier::with('user')->where('id', $request->cashier_id)->first();
                CashiersMovement::create([
                    'user_id' => Auth::user()->id,
                    'cashier_id' => $request->id,
                    'amount' => $request->amount,
                    'description' => 'Traspaso a '.$cashier->title.' de '.$cashier->user->name,
                    'type' => 'egreso'
                ]);
            }

            return redirect()->route($request->redirect ?? 'voyager.cashiers.index')->with(['message' => 'Registro guardado exitosamente.', 'alert-type' => 'success']);
        } catch (\Throwable $th) {
            throw $th;
            return redirect()->route($request->redirect ?? 'voyager.cashiers.index')->with(['message' => 'Ocurrió un error.', 'alert-type' => 'error']);
        }
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
