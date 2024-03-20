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
use App\Models\Holiday;
use App\Models\ContractSchedule;
use App\Models\ContractAbsence;

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
        // $this->custom_authorize('add_paymentschedules');
        // $direccion_administrativa_id = Auth::user()->direccion_administrativa_id;
        // $tipo_da = DireccionesTipo::with(['direcciones_administrativas' => function($q) use($direccion_administrativa_id){
        //                     $q->whereRaw($direccion_administrativa_id ? "id = $direccion_administrativa_id" : 1)->where('estado', 1);
        //                 }])
        //                 ->whereHas('direcciones_administrativas', function($q) use($direccion_administrativa_id){
        //                     $q->whereRaw($direccion_administrativa_id ? "id = $direccion_administrativa_id" : 1);
        //                 })
        //                 ->where('estado', 1)->get();
        // return view('biometrics.attendances.edit-add', compact('tipo_da'));
    }

    public function generate(Request $request){
        $direccion_administrativa_id = $request->direccion_administrativa_id;
        $start = $request->start;
        $finish = $request->finish;

        // Obtener los feriados del periodo de tiempo
        $holidays = Holiday::where('status', 1)
                        ->whereRaw('CONCAT(LPAD(month,2,0),LPAD(day,2,0)) >= "'.date('md', strtotime($start)).'"' )
                        ->whereRaw('CONCAT(LPAD(month,2,0),LPAD(day,2,0)) <= "'.date('md', strtotime($finish)).'"' )
                        ->get();
        $holidays_array = array();
        foreach ($holidays as $item) {
            array_push($holidays_array, ($item->year ?? date('Y')).'-'.str_pad($item->month, 2, "0", STR_PAD_LEFT).'-'.str_pad($item->day, 2, "0", STR_PAD_LEFT));
        }

        if ($request->type == 'personal') {
            $contract = Contract::find($request->person_id);
            $ci = str_replace(' ', '-', $contract->person->ci); // Reemplazar los espacios en blancos con -
            $ci = explode('-', $ci)[0]; // Obtener solo el valor numérico de CI
            $attendances = DB::connection('sia')
                            ->table('Asistencia')
                            ->where('IdPersona', $ci)
                            ->whereDate('Fecha', '>=', $request->start)
                            ->whereDate('Fecha', '<=', $request->finish)
                            // ->select(DB::raw('IdPersona as ci'), DB::raw('Fecha as fecha'), DB::raw('Hora as hora'))
                            ->select(DB::raw('IdPersona as ci'), DB::raw('CONVERT(varchar,Fecha,23) as fecha'), DB::raw('Hora as hora'))
                            ->get();
            $contracts_schedules = ContractSchedule::with(['schedule.details'])->where('contract_id', $contract->id)
                                    ->whereRaw('DATE_FORMAT(start, "%Y%m") <= "'.date('Ym', strtotime($start)).'" and (DATE_FORMAT(finish, "%Y%m") >= "'.date('Ym', strtotime($finish)).'" or finish is null)')
                                    ->get();
            return view('biometrics.attendances.list-personal', compact('attendances', 'contracts_schedules', 'contract', 'holidays_array', 'start', 'finish'));
        }else{
            $contracts = Contract::with(['person', 'absences.period'])
                            ->whereRaw("((start <= '$start' and (finish >= '$start' or finish is NULL)) or (start <= '$finish' and (finish >= '$finish' or finish is NULL)) or (start <= '$start' and (finish >= '$finish' or finish is NULL)))")
                            ->whereRaw("(status = 'firmado' or status = 'concluido')")
                            ->whereRaw($direccion_administrativa_id ? "direccion_administrativa_id = $direccion_administrativa_id" : 1)->get();
            return view('biometrics.attendances.list-group', compact('contracts', 'holidays_array', 'start', 'finish'));
        }
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

    public function absences_store(Request $request){
        DB::beginTransaction();
        try {
            for ($i=0; $i < count($request->contract_id); $i++) { 
                $absence = ContractAbsence::firstOrNew([
                    'contract_id' => $request->contract_id[$i],
                    'period_id' => $request->period_id,
                    'date_register' => $request->date_register
                ]);

                if (!$absence->exists) {
                    $absence->fill(['user_id' => Auth::user()->id, 'quantity' => $request->quantity[$i]])->save();
                }
            }
            DB::commit();
            return redirect()->route('attendances.index')->with(['message' => 'Faltas registradas', 'alert-type' => 'success']);
        } catch (\Throwable $th) {
            DB::rollback();
            return redirect()->route('attendances.index')->with(['message' => 'Ocurrió un error', 'alert-type' => 'error']);
        }
    }
}
