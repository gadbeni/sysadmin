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

class ContractsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $contracts = Contract::with(['user', 'person', 'program'])->where('deleted_at', NULL)->get();
        $cont = 0;
        foreach ($contracts as $item) {
            $direccion_administrativa = DB::connection('mysqlgobe')->table('direccionadministrativa')->where('ID', $item->unidad_adminstrativa_id)->first();
            $cargo = DB::connection('mysqlgobe')->table('cargo')->where('ID', $item->cargo_id)->first();
            $contracts[$cont]->direccion_administrativa = $direccion_administrativa;
            $contracts[$cont]->cargo = $cargo;
            $cont++;
        }
        return view('management.contracts.browse', compact('contracts'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $people = Person::where('deleted_at', NULL)->get();
        $direccion_administrativas = DB::connection('mysqlgobe')->table('direccionadministrativa')->get();
        $programs = Program::where('deleted_at', NULL)->get();
        $cargos = DB::connection('mysqlgobe')->table('cargo')->where('idPlanilla', 3)->get();
        return view('management.contracts.edit-add', compact('people', 'direccion_administrativas', 'programs', 'cargos'));
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
            $contract = Contract::create([
                'person_id' => $request->person_id,
                'program_id' => $request->program_id,
                'cargo_id' => $request->cargo_id,
                'unidad_adminstrativa_id' => $request->unidad_adminstrativa_id,
                'user_id' => Auth::user()->id,
                'salary' => $request->salary,
                'start' => $request->start,
                'finish' => $request->finish,
                'date_invitation' => $request->date_invitation,
                'date_limit_invitation' => $request->date_limit_invitation,
                'date_response' => $request->date_response,
                'date_statement' => $request->date_statement,
                'date_memo' => $request->date_memo,
                'workers_memo' => $request->workers_memo,
                'date_memo_res' => $request->date_memo_res,
                'date_note' => $request->date_note,
                'date_report' => $request->date_report,
                'table_report' => $request->table_report,
                'details_report' => $request->details_report,
                'date_autorization' => $request->date_autorization,
                'certification_poa' => $request->certification_poa,
                'certification_pac' => $request->certification_pac,
            ]);

            return redirect()->route('contracts.index')->with('success', 'Contrato creado correctamente');
        } catch (\Throwable $th) {
            //throw $th;
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
