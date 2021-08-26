<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use DataTables;
use Carbon\Carbon;

// Models
use App\Models\Vault;
use App\Models\VaultsDetail;
use App\Models\VaultsDetailsCash;
use App\Models\Cashier;
use App\Models\VaultsClosure;
use App\Models\VaultsClosuresDetail;

class VaultsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $vault = Vault::with(['details.cash'])->where('deleted_at', NULL)->first();
        return view('vaults.browse', compact('vault'));
    }

    public function list($id){
        $data = VaultsDetail::with(['cash', 'user'])->where('vault_id', $id)->where('deleted_at', NULL)->get();
        // return $data;

        return
            Datatables::of($data)
            ->addIndexColumn()
            ->addColumn('user', function($row){
                return $row->user->name;
            })
            ->addColumn('amount', function($row){
                $total = 0;
                foreach ($row->cash as $value) {
                    $total += $value->quantity * $value->cash_value;
                }
                return number_format($total, 2, ',', '.');
            })
            ->addColumn('date', function($row){
                return date('d-m-Y H:i:s', strtotime($row->created_at)).'<br><small>'.\Carbon\Carbon::parse($row->created_at)->diffForHumans().'</small>';
            })
            ->addColumn('actions', function($row){
                $actions = '
                    <div class="no-sort no-click bread-actions text-right">
                        <a href="'.route('view.details.show', ['id' => $row->id]).'" title="Ver" class="btn btn-sm btn-warning view">
                            <i class="voyager-eye"></i> <span class="hidden-xs hidden-sm">Ver</span>
                        </a>
                    </div>
                ';
                return $actions;
            })
            ->rawColumns(['date', 'actions'])
            ->make(true);
    }

    public function view_details($id){
        $details = VaultsDetail::with(['cash', 'user'])->where('id', $id)->where('deleted_at', NULL)->first();
        // dd($details);
        return view('vaults.details-read', compact('details'));
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
            Vault::create([
                'user_id' => Auth::user()->id,
                'name' => $request->name,
                'description' => $request->description,
                'status' => 'activa'
            ]);
            return redirect()->route('vaults.index')->with(['message' => 'Bóveda guardada exitosamente.', 'alert-type' => 'success']);
        } catch (\Throwable $th) {
            //throw $th;
            return redirect()->route('vaults.index')->with(['message' => 'Ocurrió un error.', 'alert-type' => 'error']);
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

    public function details_store($id, Request $request){
        // dd($request);
        DB::beginTransaction();
        try {
            $detail = VaultsDetail::create([
                'user_id' => Auth::user()->id,
                'vault_id' => $id,
                'bill_number' => $request->bill_number,
                'name_sender' => $request->name_sender,
                'description' => $request->description,
                'type' => 'ingreso',
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
            DB::commit();
            return redirect()->route('vaults.index')->with(['message' => 'Detalle de bóveda guardado exitosamente.', 'alert-type' => 'success']);
        } catch (\Throwable $th) {
            DB::rollback();
            // dd($th);
            return redirect()->route('vaults.index')->with(['message' => 'Ocurrió un error.', 'alert-type' => 'error']);
        }
    }

    public function open($id, Request $request){
        DB::beginTransaction();
        try {

            Vault::where('id', $id)->update([
                'status' => 'activa',
                // 'closed_at' => Carbon::now()
            ]);
            DB::commit();
            return redirect()->route('vaults.index')->with(['message' => 'Bóveda abierta exitosamente.', 'alert-type' => 'success']);
        } catch (\Throwable $th) {
            DB::rollback();
            // dd($th);
            return redirect()->route('vaults.index')->with(['message' => 'Ocurrió un error.', 'alert-type' => 'error']);
        }
    }

    public function close($id){
        $vault_closure = VaultsClosure::with('details')->where('vault_id', $id)->orderBy('id', 'DESC')->first();
        $date = $vault_closure ? $vault_closure->created_at : NULL;
        $vault = Vault::with(['details' => function($q) use($date){
                        if($date){
                            $q->where('created_at', '>', $date);
                        }
                    }, 'details.cash', 'details.cashier.user'])
                    ->where('status', 'activa')->where('id', $id)->where('deleted_at', NULL)->first();
        // dd($vault);
        return view('vaults.close', compact('vault', 'vault_closure'));
    }

    public function close_store($id, Request $request){
        $cashier_open = Cashier::where('status', 'abierta')->where('deleted_at', NULL)->count();
        if($cashier_open){
            return redirect()->route('vaults.index')->with(['message' => 'No puedes cerrar bóveda porque existe una caja abierta.', 'alert-type' => 'error']);
        }

        DB::beginTransaction();
        try {

            Vault::where('id', $id)->update([
                'status' => 'cerrada',
                'closed_at' => Carbon::now()
            ]);

            $vault_closure = VaultsClosure::create([
                'vault_id' => $id,
                'user_id' => Auth::user()->id,
                'observations' => $request->observations
            ]);

            for ($i=0; $i < count($request->cash_value); $i++) { 
                VaultsClosuresDetail::create([
                    'vaults_closure_id' => $vault_closure->id,
                    'cash_value' => $request->cash_value[$i],
                    'quantity' => $request->quantity[$i],
                ]);
            }
            DB::commit();
            return redirect()->route('vaults.index')->with(['message' => 'Bóveda cerrada exitosamente.', 'alert-type' => 'success']);
        } catch (\Throwable $th) {
            DB::rollback();
            // dd($th);
            return redirect()->route('vaults.index')->with(['message' => 'Ocurrió un error.', 'alert-type' => 'error']);
        }
    }
}
