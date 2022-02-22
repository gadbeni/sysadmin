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
        $contracts = Contract::with(['user', 'person', 'program', 'cargo.nivel' => function($q){
                            $q->where('Estado', 1);
                        }, 'job.direccion_administrativa', 'direccion_administrativa', 'type'])
                        // ->whereHas('job', function($query) {
                        //     $query->whereRaw(Auth::user()->direccion_administrativa_id ? "(direccion_administrativa_id is not NULL or direccion_administrativa_id = ".Auth::user()->direccion_administrativa_id.")" : 1);
                        // })
                        ->whereRaw(Auth::user()->direccion_administrativa_id ? "direccion_administrativa_id = ".Auth::user()->direccion_administrativa_id : 1)
                        ->where('deleted_at', NULL)->get();
        return view('management.contracts.browse', compact('contracts'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $role_id = Auth::user()->role_id;
        $ids = '';

        // Recursos humanos
        if($role_id >= 9 && $role_id <= 12) $ids .= "1,";
        // Administrativo
        if($role_id >= 13 && $role_id <= 15) $ids .= "2,";
        // Contrataciones
        if(($role_id >= 9 && $role_id <= 12) || ($role_id >= 16 && $role_id <= 18)) $ids .= "5,";

        $ids = substr($ids, 0, -1);
        $procedure_type = ProcedureType::where('deleted_at', NULL)->whereRaw($ids ? "id in ($ids)" : 1)->get();

        $people = Person::whereRaw("id not in (select person_id from contracts where status <> 'finalizado' and deleted_at is null)")->where('deleted_at', NULL)->get();
        $direccion_administrativas = DireccionAdministrativa::whereRaw(Auth::user()->direccion_administrativa_id ? "ID = ".Auth::user()->direccion_administrativa_id : 1)->get();
        $unidad_administrativas = UnidadAdministrativa::get();
        $funcionarios = DB::connection('mysqlgobe')->table('contribuyente')->where('Estado', 1)->get();
        $programs = Program::where('deleted_at', NULL)->get();
        $cargos = Cargo::with(['nivel' => function($q){
            $q->where('Estado', 1);
        }])->where('estado', 1)->get();
        // dd($cargos);
        $jobs = Job::with('direccion_administrativa')
                    ->whereRaw("id not in (select job_id from contracts where job_id is not NULL and status <> 'finalizado' and deleted_at is null)")
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
        try {

            $contract_person = Contract::where('person_id', $request->person_id)->where('status', '<>', 'finalizado')->where('deleted_at', NULL)->first();
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
        $ids = '';

        // Recursos humanos
        if($role_id >= 9 && $role_id <= 12) $ids .= "1,";
        // Administrativo
        if($role_id >= 13 && $role_id <= 15) $ids .= "2,";
        // Contrataciones
        if(($role_id >= 9 && $role_id <= 12) || ($role_id >= 16 && $role_id <= 18)) $ids .= "5,";

        $ids = substr($ids, 0, -1);
        $procedure_type = ProcedureType::where('deleted_at', NULL)->whereRaw($ids ? "id in ($ids)" : 1)->get();

        $people = Person::where('id', $contract->person->id)->where('deleted_at', NULL)->get();
        $direccion_administrativas = DireccionAdministrativa::whereRaw(Auth::user()->direccion_administrativa_id ? "ID = ".Auth::user()->direccion_administrativa_id : 1)->get();
        $unidad_administrativas = UnidadAdministrativa::get();
        $funcionarios = DB::connection('mysqlgobe')->table('contribuyente')->where('Estado', 1)->get();
        $programs = Program::where('deleted_at', NULL)->get();
        $cargos = Cargo::with(['nivel' => function($q){
                        $q->where('Estado', 1);
                    }])->where('estado', 1)->get();
        
        $jobs = Job::with('direccion_administrativa')
                    ->whereRaw("id not in (select job_id from contracts where job_id is not NULL and status <> 'finalizado' and deleted_at is null) or id = ".($contract->job_id ?? 0))
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
        try {
            $contract = Contract::where('id', $id)->update([
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
}
