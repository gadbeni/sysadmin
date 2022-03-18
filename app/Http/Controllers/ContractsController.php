<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

// Models
use App\Models\Person;
use App\Models\Program;
use App\Models\Contract;
use App\Models\DireccionAdministrativa;
use App\Models\UnidadAdministrativa;
use App\Models\ProcedureType;
use App\Models\Cargo;
use App\Models\Job;
use App\Models\Signature;
use App\Models\ContractsHistory;
use App\Models\PaymentschedulesDetail;

class ContractsController extends Controller
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
        $date = date('Y-m-d');
        Contract::where('finish', '<', $date)->update(['status' => 'concluido']);

        return view('management.contracts.browse');
    }

    public function list($search = null){
        $paginate = request('paginate') ?? 10;
        $data = Contract::with(['user', 'person', 'program', 'cargo.nivel' => function($q){
                        $q->where('Estado', 1);
                    }, 'job.direccion_administrativa', 'direccion_administrativa', 'type'])
                    ->whereRaw(Auth::user()->direccion_administrativa_id ? "direccion_administrativa_id = ".Auth::user()->direccion_administrativa_id : 1)
                    ->where(function($query) use ($search){
                        if($search){
                            $query->OrwhereHas('job', function($query) use($search){
                                $query->whereRaw("name like '%$search%'");
                            })
                            ->OrwhereHas('type', function($query) use($search){
                                $query->whereRaw("name like '%$search%'");
                            })
                            ->OrwhereHas('person', function($query) use($search){
                                $query->whereRaw("(first_name like '%$search%' or last_name like '%$search%' or ci like '%$search%' or phone like '%$search%')");
                            })
                            ->OrWhereHas('user', function($query) use($search){
                                $query->whereRaw("name like '%$search%'");
                            })
                            ->OrWhereRaw($search ? "status like '%$search%'" : 1);
                        }
                    })
                    ->where('deleted_at', NULL)->orderBy('id', 'DESC')->paginate($paginate);
        // dd($data);
        return view('management.contracts.list', compact('data', 'search'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $role_id = Auth::user()->role_id;
        $direccion_administrativa_id = Auth::user()->direccion_administrativa_id;
        $ids = '';

        // Recursos humanos
        if(($role_id >= 9 && $role_id <= 12) || $direccion_administrativa_id) $ids .= "1,";
        // Administrativo o direcciones desconcentradas
        if(($role_id >= 13 && $role_id <= 18) || $direccion_administrativa_id) $ids .= "2,";
        // Contrataciones
        if(($role_id >= 9 && $role_id <= 12) || ($role_id >= 16 && $role_id <= 18) || $direccion_administrativa_id) $ids .= "5,";

        $ids = substr($ids, 0, -1);
        $procedure_type = ProcedureType::where('deleted_at', NULL)->whereRaw($ids ? "id in ($ids)" : 1)->get();

        $people = Person::where('deleted_at', NULL)
                    ->whereRaw("id not in (select person_id from contracts where status <> 'concluido' and deleted_at is null)")
                    ->get();
        $direccion_administrativas = DireccionAdministrativa::whereRaw($direccion_administrativa_id ? "ID = $direccion_administrativa_id" : 1)->get();
        $unidad_administrativas = UnidadAdministrativa::get();
        $funcionarios = DB::connection('mysqlgobe')->table('contribuyente')->where('Estado', 1)->get();
        $programs = Program::where('deleted_at', NULL)->get();
        $cargos = Cargo::with(['nivel' => function($q){
            $q->where('Estado', 1);
        }])->where('estado', 1)->get();
        // dd($cargos);
        $jobs = Job::with('direccion_administrativa')
                    ->whereRaw("id not in (select job_id from contracts where job_id is not NULL and status <> 'concluido' and deleted_at is null)")
                    ->whereRaw(Auth::user()->direccion_administrativa_id ? 'direccion_administrativa_id = '.Auth::user()->direccion_administrativa_id : 1)
                    ->where('deleted_at', NULL)->get();
        return view('management.contracts.edit-add', compact('procedure_type', 'people', 'direccion_administrativas', 'unidad_administrativas', 'funcionarios', 'programs', 'cargos', 'jobs'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // dd($request->all());
        try {

            $contract_person = Contract::where('person_id', $request->person_id)->where('status', '<>', 'concluido')->where('deleted_at', NULL)->first();
            if($contract_person){
                return redirect()->route('contracts.index')->with(['message' => 'La persona seleccionada ya tiene un contrato activo o en proceso.', 'alert-type' => 'warning']);
            }

            $older_contract = Contract::whereYear('start', date('Y', strtotime($request->start)))
                        ->where('procedure_type_id', $request->procedure_type_id)
                        ->whereHas('user', function($q){
                            $q->where('direccion_administrativa_id', Auth::user()->direccion_administrativa_id);
                        })->count();
            $d_a = DireccionAdministrativa::find(Auth::user()->direccion_administrativa_id);
            $code = ($d_a ? $d_a->Sigla.'-' : '').str_pad($older_contract +1, 2, "0", STR_PAD_LEFT).'/'.date('Y', strtotime($request->start));

            $contract = Contract::create([
                'person_id' => $request->person_id,
                'program_id' => $request->program_id,
                // Si es un contrato de consultor o inversion
                'cargo_id' => $request->procedure_type_id != 1 ? $request->cargo_id : NULL,
                // Si es un contrato de permanente
                'job_id' => $request->procedure_type_id == 1 ? $request->cargo_id : NULL,
                // Si es un contrato permanente se el id_da se obtiene de la tabal jobs, sino se obtiene del request
                'direccion_administrativa_id' => $request->procedure_type_id == 1 ? Job::find($request->cargo_id)->direccion_administrativa_id : $request->direccion_administrativa_id,
                'unidad_administrativa_id' => $request->unidad_administrativa_id,
                'procedure_type_id' => $request->procedure_type_id,
                'user_id' => Auth::user()->id,
                'code' => $code,
                'details_work' => $request->details_work,
                'start' => $request->start,
                'finish' => $request->finish,
                'date_invitation' => $request->date_invitation,
                'date_limit_invitation' => $request->date_limit_invitation,
                'date_response' => $request->date_response,
                'date_statement' => $request->date_statement,
                'date_memo' => $request->date_memo,
                'workers_memo' => json_encode($request->workers_memo),
                'date_memo_res' => $request->date_memo_res,
                'date_note' => $request->date_note,
                'date_report' => $request->date_report,
                'table_report' => $request->table_report,
                'details_report' => $request->details_report,
                'date_autorization' => $request->date_autorization,
                'certification_poa' => $request->certification_poa,
                'certification_pac' => $request->certification_pac,
                'date_presentation' => $request->date_presentation,
                'status' => 'elaborado',
            ]);

            return redirect()->route('contracts.index')->with(['message' => 'Contrato guardado exitosamente.', 'alert-type' => 'success']);
        } catch (\Throwable $th) {
            //throw $th;
            return redirect()->route('contracts.index')->with(['message' => 'Ocurrió un error.', 'alert-type' => 'error']);
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
        $contract = Contract::with(['user', 'person', 'program'])->where('id', $id)->first();
        $role_id = Auth::user()->role_id;
        $direccion_administrativa_id = Auth::user()->direccion_administrativa_id;
        $procedure_type = ProcedureType::where('deleted_at', NULL)->where('id', $contract->procedure_type_id)->get();

        $people = Person::where('id', $contract->person->id)->where('deleted_at', NULL)->get();
        $direccion_administrativas = DireccionAdministrativa::whereRaw(Auth::user()->direccion_administrativa_id ? "ID = ".Auth::user()->direccion_administrativa_id : 1)->get();
        $unidad_administrativas = UnidadAdministrativa::get();
        $funcionarios = DB::connection('mysqlgobe')->table('contribuyente')->where('Estado', 1)->get();
        $programs = Program::where('deleted_at', NULL)->get();
        $cargos = Cargo::with(['nivel' => function($q){
                        $q->where('Estado', 1);
                    }])->where('estado', 1)->get();
        
        $jobs = Job::with('direccion_administrativa')
                    ->whereRaw("id not in (select job_id from contracts where job_id is not NULL and status <> 'concluido' and deleted_at is null) or id = ".($contract->job_id ?? 0))
                    ->whereRaw(Auth::user()->direccion_administrativa_id ? 'direccion_administrativa_id = '.Auth::user()->direccion_administrativa_id : 1)
                    ->where('deleted_at', NULL)->get();
        return view('management.contracts.edit-add', compact('contract', 'procedure_type', 'people', 'direccion_administrativas', 'unidad_administrativas', 'funcionarios', 'programs', 'cargos', 'jobs'));
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
        // dd($request->all());
        try {
            $contract = Contract::find($id);
            // Si se cambia el año de inicio o la dirección administrativa se debe actualizar el código
            if(date('Y', strtotime($contract->start)) != date('Y', strtotime($request->start)) || $contract->unidad_administrativa_id != $request->unidad_administrativa_id){
                $older_contract = Contract::whereYear('start', date('Y', strtotime($request->start)))
                            ->where('procedure_type_id', $request->procedure_type_id)
                            ->whereHas('user', function($q){
                                $q->where('direccion_administrativa_id', Auth::user()->direccion_administrativa_id);
                            })->count();
                $d_a = DireccionAdministrativa::find(Auth::user()->direccion_administrativa_id);
                $code = ($d_a ? $d_a->Sigla.'-' : '').str_pad($older_contract +1, 2, "0", STR_PAD_LEFT).'/'.date('Y', strtotime($request->start));
            }else{
                $code = $contract->code;
            }

            Contract::where('id', $id)->update([
                'person_id' => $request->person_id,
                'program_id' => $request->program_id,
                // Si es un contrato de consultor o inversion
                'cargo_id' => $request->procedure_type_id != 1 ? $request->cargo_id : NULL,
                // Si es un contrato de permanente
                'job_id' => $request->procedure_type_id == 1 ? $request->cargo_id : NULL,
                // Si es un contrato permanente se el id_da se obtiene de la tabal jobs, sino se obtiene del request
                'direccion_administrativa_id' => $request->procedure_type_id == 1 ? Job::find($request->cargo_id)->direccion_administrativa_id : $request->direccion_administrativa_id,
                'unidad_administrativa_id' => $request->unidad_administrativa_id,
                'code' => $code,
                'details_work' => $request->details_work,
                'start' => $request->start,
                'finish' => $request->finish,
                'date_invitation' => $request->date_invitation,
                'date_limit_invitation' => $request->date_limit_invitation,
                'date_response' => $request->date_response,
                'date_statement' => $request->date_statement,
                'date_memo' => $request->date_memo,
                'workers_memo' => json_encode($request->workers_memo),
                'date_memo_res' => $request->date_memo_res,
                'date_note' => $request->date_note,
                'date_report' => $request->date_report,
                'table_report' => $request->table_report,
                'details_report' => $request->details_report,
                'date_autorization' => $request->date_autorization,
                'certification_poa' => $request->certification_poa,
                'certification_pac' => $request->certification_pac,
                'date_presentation' => $request->date_presentation,
            ]);

            return redirect()->route('contracts.index')->with(['message' => 'Contrato editado exitosamente.', 'alert-type' => 'success']);
        } catch (\Throwable $th) {
            //throw $th;
            return redirect()->route('contracts.index')->with(['message' => 'Ocurrió un error.', 'alert-type' => 'error']);
        }
    }

    public function contracts_status(Request $request){
        // dd($request->all());
        DB::beginTransaction();
        try {

            // Verificar si el contrato tenga pagos realizados
            if(!$this->enabled_to_delete($request->id) && !$request->finish){
                return response()->json(['error' => 'El contrato pertenece a una planilla en proceso de pago.']);
            }

            $contract = Contract::findOrFail($request->id);
            $contract->status = $request->status;
            if ($request->finish) {
                $contract->finish = $request->finish;
            }
            $contract->update();

            if($request->observations){
                ContractsHistory::create([
                    'contract_id' => $request->id,
                    'user_id' => Auth::user()->id,
                    'status' => $request->status,
                    'observations' => $request->observations,
                ]);
            }
            DB::commit();
            return response()->json(['message' => 'Cambio realizo exitosamente.']);
        } catch (\Throwable $th) {
            DB::rollback();
            return response()->json(['error' => 'Ocurrió un error.']);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            if($this->enabled_to_delete($id)){
                Contract::where('id', $id)->update([
                    'status' => 'anulado',
                    'deleted_at' => Carbon::now()
                ]);
                return response()->json(['message' => 'Anulado exitosamente.']);
            }else{
                return response()->json(['error' => 'El contrato pertenece a una planilla en proceso de pago.']);
            }
        } catch (\Throwable $th) {
            //throw $th;
            return response()->json(['error' => 'Ocurrió un error.']);
        }
    }

    // ===============================

    public function contracts_direccion_administrativa($id){
        $tipo_planilla = ProcedureType::with(['contracts.user', 'contracts.person', 'contracts.program', 'contracts' => function($q) use ($id){
                                $q->where('direccion_administrativa_id', $id);
                            }])
                            ->whereHas('contracts', function($q) use ($id){
                                $q->where('direccion_administrativa_id', $id);
                            })
                            ->where('deleted_at', NULL)->get();
        return response()->json($tipo_planilla);
    }

    // ================================
    
    public function print($id, $document){
        $contract = Contract::with(['user', 'person', 'program', 'cargo.nivel' => function($q){
            $q->where('Estado', 1);
        }, 'direccion_administrativa', 'job.direccion_administrativa', 'unidad_administrativa'])->where('id', $id)->first();
        $contract->workers = $contract->workers_memo != "null" ? DB::connection('mysqlgobe')->table('contribuyente')->whereIn('ID', json_decode($contract->workers_memo))->get() : [];
        $signature = Signature::where('direccion_administrativa_id', $contract->user->direccion_administrativa_id)->where('status', 1)->where('deleted_at', NULL)->first();
        return view('management.docs.'.$document, compact('contract', 'signature'));
    }

    // **** Métodos funcionales ****
    public function enabled_to_delete($id){
        $contracts = PaymentschedulesDetail::where('contract_id', $id)
                        ->whereRaw("(status = 'procesada' OR status = 'enviada' OR status = 'aprobada' OR status = 'habilitada')")
                        ->where('deleted_at', NULL)->first();
        return $contracts ? false : true;
    }
}
