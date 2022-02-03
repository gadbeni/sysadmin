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
use App\Models\Cargo;

class ContractsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $contracts = Contract::with(['user', 'person', 'program', 'cargo.nivel', 'direccion_administrativa'])->where('deleted_at', NULL)->get();
        return view('management.contracts.browse', compact('contracts'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $people = Person::with(['contracts' => function($q){
            $q->where('status', 1)->where('deleted_at', NULL);
        }])->where('deleted_at', NULL)->get();
        $direccion_administrativas = DireccionAdministrativa::get();
        $unidad_administrativas = UnidadAdministrativa::get();
        $funcionarios = DB::connection('mysqlgobe')->table('contribuyente')->where('Estado', 1)->get();
        $programs = Program::where('deleted_at', NULL)->get();
        $cargos = Cargo::with(['nivel'])->where('idPlanilla', 2)->OrWhere('idPlanilla', 3)
                    ->whereHas('nivel', function($q){
                        $q->orderBy('NumNivel', 'ASC');
                    })->get();
        return view('management.contracts.edit-add', compact('people', 'direccion_administrativas', 'unidad_administrativas', 'funcionarios', 'programs', 'cargos'));
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
            $code = Contract::whereYear('start', date('Y', strtotime($request->start)))
                        ->where('procedure_type_id', $request->procedure_type_id,)->count() + 1;

            $contract = Contract::create([
                'person_id' => $request->person_id,
                'program_id' => $request->program_id,
                'cargo_id' => $request->cargo_id,
                'direccion_adminstrativa_id' => $request->direccion_adminstrativa_id,
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
        $people = Person::where('deleted_at', NULL)->get();
        $direccion_administrativas = DB::connection('mysqlgobe')->table('direccionadministrativa')->get();
        $funcionarios = DB::connection('mysqlgobe')->table('contribuyente')->where('Estado', 1)->get();
        $programs = Program::where('deleted_at', NULL)->get();
        $cargos = DB::connection('mysqlgobe')->table('cargo')->where('idPlanilla', 3)->get();
        return view('management.contracts.edit-add', compact('contract', 'people', 'direccion_administrativas', 'funcionarios', 'programs', 'cargos'));
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
                'cargo_id' => $request->cargo_id,
                'procedure_type_id' => $request->procedure_type_id,
                'unidad_administrativa_id' => $request->unidad_administrativa_id,
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

    // ================================
    
    public function print($id, $document){
        $contract = Contract::with(['user', 'person', 'program', 'cargo.nivel', 'direccion_administrativa', 'unidad_administrativa'])->where('id', $id)->first();
        $contract->workers = $contract->workers_memo != "null" ? DB::connection('mysqlgobe')->table('contribuyente')->whereIn('ID', json_decode($contract->workers_memo))->get() : [];
        return view('management.docs.'.$document, compact('contract'));
    }
}
