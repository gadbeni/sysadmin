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
        $this->custom_authorize('browse_vaults');
        $vault = Vault::with(['details.cash' => function($q){
                    $q->where('deleted_at', NULL);
                }, 'details' => function($q){
                    $q->where('deleted_at', NULL);
                }])->where('deleted_at', NULL)->first();
        return view('vaults.browse', compact('vault'));
    }

    public function list($id){
        $data = VaultsDetail::withTrashed()->with(['cash', 'user'])->where('vault_id', $id)->get();

        return
            Datatables::of($data)
            ->addIndexColumn()
            ->addColumn('user', function($row){
                return $row->user->name;
            })
            ->addColumn('type', function($row){
                return $row->type.($row->deleted_at ? '<br><label class="label label-danger">Eliminado<label>' : '');
            })
            ->addColumn('description', function($row){
                return $row->description;
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
                $btn_print = '';
                if($row->type == 'ingreso'){
                    $btn_print =    '<a href="'.route('vaults.print.vault.details', ['id' => $row->id]).'" target="_blank" title="Imprimir" class="btn btn-sm btn-default view">
                                        <i class="glyphicon glyphicon-print"></i> <span class="hidden-xs hidden-sm">Imprimir</span>
                                    </a>';
                }elseif($row->type == 'egreso' && !$row->cashier_id){
                    $btn_print =    '<a href="'.route('vaults.print.vault.details', ['id' => $row->id]).'" target="_blank" title="Imprimir" class="btn btn-sm btn-default view">
                                        <i class="glyphicon glyphicon-print"></i> <span class="hidden-xs hidden-sm">Imprimir</span>
                                    </a>';
                }
                
                $actions = '
                    <div class="no-sort no-click bread-actions text-right">
                        '.$btn_print.'
                        <a href="'.route('view.details.show', ['id' => $row->id]).'" title="Ver" class="btn btn-sm btn-warning view">
                            <i class="voyager-eye"></i> <span class="hidden-xs hidden-sm">Ver</span>
                        </a>
                        <a href="#" data-toggle="modal" data-target="#modal-delete" onclick="deleteItem('.$row->id.')" title="Eliminar" class="btn btn-sm btn-danger view">
                            <i class="voyager-trash"></i> <span class="hidden-xs hidden-sm">Eliminar</span>
                        </a>
                    </div>
                ';
                return $actions;
            })
            ->rawColumns(['type', 'description', 'date', 'actions'])
            ->make(true);
    }

    public function view_details($id){
        // $this->custom_authorize('browse_vault');
        $details = VaultsDetail::with(['cash', 'user'])->where('id', $id)->withTrashed()->first();
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
            return redirect()->route('vaults.index')->with(['message' => 'Ocurrió un error', 'alert-type' => 'error']);
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
                'type' => $request->type,
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
            return redirect()->route('vaults.index')->with(['message' => 'Ocurrió un error', 'alert-type' => 'error']);
        }
    }

    public function details_destroy(Request $request){
        // dd($request->all());
        DB::beginTransaction();
        try {
            $detail = VaultsDetail::find($request->id);
            $detail->description = $detail->description.'<br><br><small><b>Motivo de eliminación:</b></small><br>'.$request->description;
            $detail->status = 'anulado';
            $detail->deleted_at = Carbon::now();
            $detail->update();

            DB::commit();
            return redirect()->route('vaults.index')->with(['message' => 'Detalle de bóveda anulado exitosamente.', 'alert-type' => 'success']);
        } catch (\Throwable $th) {
            DB::rollback();
            // dd($th);
            return redirect()->route('vaults.index')->with(['message' => 'Ocurrió un error', 'alert-type' => 'error']);
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
            return redirect()->route('vaults.index')->with(['message' => 'Ocurrió un error', 'alert-type' => 'error']);
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
            return redirect()->route('vaults.index')->with(['message' => 'Ocurrió un error', 'alert-type' => 'error']);
        }
    }

    public function print_status($id){
        $vault = Vault::with(['user', 'details.cash' => function($q){
            $q->where('deleted_at', NULL);
        }, 'details' => function($q){
            $q->where('deleted_at', NULL);
        }])->where('id', $id)->where('deleted_at', NULL)->first();
        return view('vaults.print.print-vaults', compact('vault'));
    }

    public function print_vault_details($id){
        $detail = VaultsDetail::with(['cash', 'user'])->where('id', $id)->withTrashed()->first();
        return view('vaults.print.print-vaults-details', compact('detail'));
    }

    public function print_closure($id){
        $closure = VaultsClosure::with(['details', 'user'])->where('id', $id)->where('deleted_at', NULL)->first();
        return view('vaults.print.print-closure', compact('closure'));
    }
}
