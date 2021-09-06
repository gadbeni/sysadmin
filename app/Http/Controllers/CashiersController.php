<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

// Models
use App\Models\Cashier;
use App\Models\CashiersMovement;
use App\Models\VaultsDetail;
use App\Models\VaultsDetailsCash;
use App\Models\CashiersDetail;

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
        // dd($request);
        $cashier = Cashier::where('user_id', $request->user_id)->where('status', '!=', 'cerrada')->where('deleted_at', NULL)->first();

        if(!$cashier){
            DB::beginTransaction();
            try {
                $cashier = Cashier::create([
                    'user_id' => $request->user_id,
                    'title' => $request->title,
                    'observations' => $request->observations,
                    'status' => 'apertura pendiente'
                ]);

                if($request->amount){
                    CashiersMovement::create([
                        'user_id' => Auth::user()->id,
                        'cashier_id' => $cashier->id,
                        'amount' => $request->amount,
                        'description' => 'Monto de apertura de caja.',
                        'type' => 'ingreso'
                    ]);

                    // Registrar detalle de bóveda
                    $cashier = Cashier::with('user')->where('id', $cashier->id)->first();
                    $detail = VaultsDetail::create([
                        'user_id' => Auth::user()->id,
                        'vault_id' => $request->vault_id,
                        'cashier_id' => $cashier->id,
                        'description' => 'Traspaso a '.$cashier->title,
                        'type' => 'egreso',
                        'status' => 'aprobado'
                    ]);

                    for ($i=0; $i < count($request->cash_value); $i++) { 
                        // if($request->quantity[$i]){
                            VaultsDetailsCash::create([
                                'vaults_detail_id' => $detail->id,
                                'cash_value' => $request->cash_value[$i],
                                'quantity' => $request->quantity[$i],
                            ]);
                        // }
                    }
                }

                DB::commit();
    
                return redirect()->route('voyager.cashiers.index')->with(['message' => 'Registro guardado exitosamente.', 'alert-type' => 'success']);
            } catch (\Throwable $th) {
                DB::rollback();
                //throw $th;
                return redirect()->route('voyager.cashiers.index')->with(['message' => 'Ocurrió un error.', 'alert-type' => 'error']);
            }
        }else{
            return redirect()->route('voyager.cashiers.index')->with(['message' => 'El usuario seleccionado tiene una caja que no ha sido cerrada.', 'alert-type' => 'warning']);
        }
    }

    public function amount($id)
    {
        $cashier = Cashier::findOrFail($id);
        if($cashier->status == 'abierta'){
            return view('vendor.voyager.cashiers.add-amount', compact('id'));
        }else{
            return redirect()->route('voyager.cashiers.index')->with(['message' => 'La caja ya no está abierta.', 'alert-type' => 'warning']);
        }
    }

    public function amount_store(Request $request)
    {
        DB::beginTransaction();
        try {
            // Registrar traspaso a la caja
            $movement = CashiersMovement::create([
                'user_id' => Auth::user()->id,
                'cashier_id' => $request->cashier_id,
                'cashier_id_from' => $request->id ?? NULL,
                'amount' => $request->amount,
                'description' => $request->description,
                'type' => 'ingreso'
            ]);

            $id_transfer = $movement->id;

            // En caso de ser un trapaso entre cajas
            $cashier = Cashier::with('user')->where('id', $request->cashier_id)->first();
            if($request->id){
                CashiersMovement::create([
                    'user_id' => Auth::user()->id,
                    'cashier_id' => $request->id,
                    'cashier_id_to' => $request->cashier_id,
                    'amount' => $request->amount,
                    'description' => 'Traspaso a '.$cashier->title,
                    'type' => 'egreso'
                ]);
            }else{
                if($request->amount){
                    // Registrar detalle de bóveda
                    $detail = VaultsDetail::create([
                        'user_id' => Auth::user()->id,
                        'vault_id' => $request->vault_id,
                        'cashier_id' => $request->cashier_id,
                        'description' => 'Traspaso a '.$cashier->title,
                        'type' => 'egreso',
                        'status' => 'aprobado'
                    ]);
                    for ($i=0; $i < count($request->cash_value); $i++) { 
                        VaultsDetailsCash::create([
                            'vaults_detail_id' => $detail->id,
                            'cash_value' => $request->cash_value[$i],
                            'quantity' => $request->quantity[$i],
                        ]);
                    }
                }
            }

            DB::commit();
            return redirect()->route($request->redirect ?? 'voyager.cashiers.index')->with(['message' => 'Registro guardado exitosamente.', 'alert-type' => 'success', 'id_transfer' => $id_transfer]);
        } catch (\Throwable $th) {
            DB::rollback();
            // dd($th);
            return redirect()->route($request->redirect ?? 'voyager.cashiers.index')->with(['message' => 'Ocurrió un error.', 'alert-type' => 'error']);
        }
    }

    public function change_status($id, Request $request){
        // DB::beginTransaction();
        try {
            if($request->status == 'abierta'){
                $message = 'Caja aceptada exitosamente.';
                Cashier::where('id', $id)->update([
                    'status' => $request->status
                ]);
            }else{
                $cashier = Cashier::with(['vault_details.cash' => function($q){
                    $q->where('deleted_at', NULL);
                }])->where('id', $id)->first();

                $message = 'Caja rechazada exitosamente.';
                Cashier::where('id', $id)->update([
                    'status' => $request->status,
                    // 'deleted_at' => Carbon::now()
                ]);

                $vault_detail = VaultsDetail::create([
                    'user_id' => Auth::user()->id,
                    'vault_id' => $cashier->vault_details->vault_id,
                    'cashier_id' => $cashier->id,
                    'description' => 'Rechazo de apertura de caja de '.$cashier->title.'.',
                    'type' => 'ingreso',
                    'status' => 'aprobado'
                ]);
                foreach ($cashier->vault_details->cash as $item) {
                    if($item->quantity > 0){
                        VaultsDetailsCash::create([
                            'vaults_detail_id' => $vault_detail->id,
                            'cash_value' => $item->cash_value,
                            'quantity' => $item->quantity
                        ]);
                    }
                }
            }

            // DB::commit();
            return redirect()->route('voyager.dashboard')->with(['message' => $message, 'alert-type' => 'success']);
        } catch (\Throwable $th) {
            // DB::rollback();
            // dd($th);
            return redirect()->route('voyager.dashboard')->with(['message' => 'Ocurrió un error.', 'alert-type' => 'error']);
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
        // dd($id);
    }

    public function close($id){
        $cashier = Cashier::with(['movements' => function($q){
            $q->where('deleted_at', NULL);
        }, 'payments' => function($q){
            $q->where('deleted_at', NULL);
        }])
        ->where('id', $id)->where('deleted_at', NULL)->first();
        return view('vendor.voyager.cashiers.close', compact('cashier'));
    }

    public function close_store($id, Request $request){
        // dd($request);
        DB::beginTransaction();
        try {
            $cashier = Cashier::findOrFail($id);
            $cashier->closed_at = Carbon::now();
            $cashier->status = 'cierre pendiente';
            $cashier->save();

            for ($i=0; $i < count($request->cash_value); $i++) { 
                // if($request->quantity[$i]){
                    CashiersDetail::create([
                        'cashier_id' => $id,
                        'cash_value' => $request->cash_value[$i],
                        'quantity' => $request->quantity[$i],
                    ]);
                // }
            }

            DB::commit();
            return redirect()->route('voyager.dashboard')->with(['message' => 'Caja cerrada exitosamente.', 'alert-type' => 'success']);
        } catch (\Throwable $th) {
            DB::rollback();
            // dd($th);
            return redirect()->route('voyager.dashboard')->with(['message' => 'Ocurrió un error.', 'alert-type' => 'error']);
        }
    }

    public function close_revert($id, Request $request){
        DB::beginTransaction();
        try {
            $cashier = Cashier::findOrFail($id);
            if($cashier->status == 'cierre pendiente'){
                $cashier->closed_at = NULL;
                $cashier->status = 'abierta';
                $cashier->save();

                CashiersDetail::where('cashier_id', $id)->update([
                    'deleted_at' => Carbon::now()
                ]);

                DB::commit();
                return redirect()->route('voyager.dashboard')->with(['message' => 'Caja reabierta exitosamente.', 'alert-type' => 'success']);
            }

            return redirect()->route('voyager.dashboard')->with(['message' => 'Lo siento, su caja ya fué cerrada.', 'alert-type' => 'warning']);
        } catch (\Throwable $th) {
            DB::rollback();
            // dd($th);
            return redirect()->route('voyager.dashboard')->with(['message' => 'Ocurrió un error.', 'alert-type' => 'error']);
        }
    }

    public function confirm_close($id)
    {
        $cashier = Cashier::with(['details' => function($q){
            $q->where('deleted_at', NULL);
        }])->where('id', $id)->first();
        if($cashier->status == 'cierre pendiente'){
            return view('vendor.voyager.cashiers.confirm_close', compact('cashier'));
        }else{
            return redirect()->route('voyager.cashiers.index')->with(['message' => 'La caja ya no está abierta.', 'alert-type' => 'warning']);
        }
    }

    public function confirm_close_store($id, Request $request)
    {
        DB::beginTransaction();
        try {
            $cashier = Cashier::findOrFail($id);
            $cashier->closed_at = Carbon::now();
            $cashier->status = 'cerrada';
            $cashier->save();
            
            $detail = VaultsDetail::create([
                'user_id' => Auth::user()->id,
                'vault_id' => $request->vault_id,
                'description' => 'Devolución de la caja '.$cashier->title,
                'type' => 'ingreso',
                'status' => 'aprobado'
            ]);

            for ($i=0; $i < count($request->cash_value); $i++) { 
                // if($request->quantity[$i]){
                    VaultsDetailsCash::create([
                        'vaults_detail_id' => $detail->id,
                        'cashier_id' => $id,
                        'cash_value' => $request->cash_value[$i],
                        'quantity' => $request->quantity[$i],
                    ]);
                // }
            }

            DB::commit();
            return redirect()->route('voyager.cashiers.index')->with(['message' => 'Caja cerrada exitosamente.', 'alert-type' => 'success']);
        } catch (\Throwable $th) {
            DB::rollback();
            // dd($th);
            return redirect()->route('voyager.cashiers.index')->with(['message' => 'Ocurrió un error.', 'alert-type' => 'error']);
        }
    }

    public function print_open($id){
        $cashier = Cashier::with(['user', 'movements' => function($q){
            $q->where('deleted_at', NULL);
        }])->where('id', $id)->first();
        $view = view('vendor.voyager.cashiers.print-open', compact('cashier'));
        return $view;
        // $pdf = \App::make('dompdf.wrapper');
        // $pdf->loadHTML($view);
        // return $pdf->download();
    }

    public function print_transfer($id){
        $movement = CashiersMovement::with(['cashier', 'cashier_to', 'user'])->where('id', $id)->first();
        // dd($movement);
        return view('vendor.voyager.cashiers.print-transfer', compact('movement'));
    }

    public function print_close($id){
        $cashier = Cashier::with(['user',
        'movements' => function($q){
            $q->where('deleted_at', NULL);
        }, 'payments' => function($q){
            $q->where('deleted_at', NULL);
        }, 'transfers' => function($q){
            $q->where('deleted_at', NULL);
        }, 'details' => function($q){
            $q->where('deleted_at', NULL);
        }])->where('id', $id)->first();
        $view = view('vendor.voyager.cashiers.print-close', compact('cashier'));
        return $view;
        // $pdf = \App::make('dompdf.wrapper');
        // $pdf->loadHTML($view);
        // return $pdf->download();
    }
}
