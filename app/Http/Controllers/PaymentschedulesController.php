<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Str;

use Maatwebsite\Excel\Facades\Excel;

// Imports
use App\Imports\PaymentschedulesFilesImport;

// Models
use App\Models\DireccionesTipo;
use App\Models\Contract;
use App\Models\PaymentschedulesFile;
use App\Models\PaymentschedulesFilesDetails;
use App\Models\Paymentschedule;
use App\Models\PaymentschedulesDetail;
use App\Models\Period;
use App\Models\PaymentschedulesHistory;
use App\Models\Program;


class PaymentschedulesController extends Controller
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
        return view('paymentschedules.browse');
    }

    public function list($search = null){
        $paginate = request('paginate') ?? 10;
        $data = Paymentschedule::with(['user', 'direccion_administrativa', 'period', 'procedure_type', 'details' => function($query){
                        $query->where('deleted_at', NULL);
                    }, 'details.contract.person'])
                    ->whereRaw(Auth::user()->direccion_administrativa_id ? 'direccion_administrativa_id = '.Auth::user()->direccion_administrativa_id : 1)
                    ->whereRaw(Auth::user()->role_id >= 6 && Auth::user()->role_id <= 8 ? '(status = "aprobada" or status = "habilitada" or status = "pagada")' : 1)
                    ->whereRaw(Auth::user()->role_id == 25 ? 'procedure_type_id = 2' : 1)
                    ->where('status', '!=', 'borrador')
                    ->where('status', '!=', 'anulada')
                    ->where('deleted_at', NULL)
                    ->where(function($query) use ($search){
                        if($search){
                            $query->OrwhereHas('period', function($query) use($search){
                                $query->whereRaw($search ? 'name like "%'.$search.'%"' : 1);
                            })
                            ->OrwhereHas('procedure_type', function($query) use($search){
                                $query->whereRaw($search ? 'name like "%'.$search.'%"' : 1);
                            })
                            ->OrWhereHas('user', function($query) use($search){
                                $query->whereRaw($search ? 'name like "%'.$search.'%"' : 1);
                            })
                            ->OrWhereHas('direccion_administrativa', function($query) use($search){
                                $query->whereRaw($search ? 'nombre like "%'.$search.'%"' : 1);
                            })
                            ->OrWhereRaw($search ? "id = '".intval($search)."'" : 1)
                            ->OrWhereRaw($search ? 'centralize_code like "%'.$search.'%"' : 1)
                            ->OrWhereRaw($search ? "status like '%".$search."%'" : 1);
                        }
                    })
                    ->orderBy('id', 'DESC')->paginate($paginate);
        // dd($data);
        return view('paymentschedules.list', compact('data', 'search'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $direccion_administrativa_id = Auth::user()->direccion_administrativa_id;
        $tipo_da = DireccionesTipo::with(['direcciones_administrativas' => function($q) use($direccion_administrativa_id){
                            $q->whereRaw($direccion_administrativa_id ? "id = $direccion_administrativa_id" : 1);
                        }])
                        ->whereHas('direcciones_administrativas', function($q) use($direccion_administrativa_id){
                            $q->whereRaw($direccion_administrativa_id ? "id = $direccion_administrativa_id" : 1);
                        })
                        ->where('estado', 1)->get();
        return view('paymentschedules.edit-add', compact('tipo_da'));
    }

    public function generate(Request $request){
        $direccion_administrativa_id =  $request->da_id;
        $procedure_type_id = $request->procedure_type_id;

        // Verificar si no existe una planilla generada para la DA, el periodo y tipo de planilla
        $paymentschedule = Paymentschedule::where([
            'direccion_administrativa_id' =>  $request->da_id,
            'period_id' => $request->period_id,
            'procedure_type_id' => $request->procedure_type_id,
            'deleted_at' => NULL,
        ])->first();

        $period = Period::findOrFail($request->period_id);
        $year = Str::substr($period->name, 0, 4);
        $month = Str::substr($period->name, 4, 2);
        $contracts = Contract::with(['user', 'person.seniority_bonus.type', 'person.seniority_bonus' => function($q){
                            $q->where('deleted_at', NULL)->where('status', 1);
                        }, 'program', 'cargo.nivel', 'job.direccion_administrativa', 'direccion_administrativa', 'type'])
                        ->where('direccion_administrativa_id', $direccion_administrativa_id)
                        ->where('procedure_type_id', $procedure_type_id)
                        ->whereRaw('CONCAT(YEAR(start), IF( LENGTH(MONTH(start)) > 1, MONTH(start), CONCAT("0", MONTH(start)) )) <= "'.$year.$month.'"')
                        ->whereRaw('(CONCAT(YEAR(finish), IF( LENGTH(MONTH(finish)) > 1, MONTH(finish), CONCAT("0", MONTH(finish)) )) >= "'.$year.$month.'" or finish is null)')
                        ->whereRaw("id not in (select pd.contract_id from paymentschedules as p, paymentschedules_details as pd
                                                    where p.id = pd.paymentschedule_id and p.period_id = ".$period->id." and p.deleted_at is null and pd.deleted_at is null)")
                        ->whereRaw('(status = "firmado" or status = "concluido")')
                        ->where('deleted_at', NULL)->get();

        $paymentschedules_file = PaymentschedulesFile::with(['details'])
                                    ->where('direccion_administrativa_id', $direccion_administrativa_id)
                                    ->where('period_id', $period->id)
                                    ->where('procedure_type_id', $procedure_type_id)
                                    ->where('status', 'cargado')->get();

        return view('paymentschedules.generate', compact('paymentschedule', 'contracts', 'direccion_administrativa_id', 'period', 'procedure_type_id', 'paymentschedules_file'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        DB::beginTransaction();
        try {

            // Buscar o registrar nueva planilla
            if($request->paymentschedule_id && !$request->aditional){
                $paymentschedule = Paymentschedule::findOrFail($request->paymentschedule_id);
            }else{
                $paymentschedule = Paymentschedule::create([
                    'direccion_administrativa_id' =>  $request->direccion_administrativa_id,
                    'period_id' => $request->period_id,
                    'procedure_type_id' => $request->procedure_type_id,
                    'centralize' => $request->centralize,
                    'aditional' => $request->aditional ? 1 : NULL,
                    'observations' => $request->observations,
                    'status' => 'procesada',
                    'user_id' => Auth::user()->id,
                ]);
            }
            // dd($paymentschedule);

            // En caso de ser centralizada asignarle el n??mero correspondiente
            if($request->centralize){
                $centralize_paymentschedule = $request->aditional ? 
                                                // Buscar una planilla adicional con los mismo datos
                                                Paymentschedule::where('period_id', $request->period_id)
                                                    ->where('procedure_type_id', $request->procedure_type_id)
                                                    ->where('centralize', 1)->where('centralize_code', '<>', NULL)->where('id', '<>', $paymentschedule->id)
                                                    ->where('aditional', 1)->where('status', 'procesada')->where('deleted_at', NULL)->orderBy('id', 'DESC')->first() :
                                                Paymentschedule::where('period_id', $request->period_id)
                                                    ->where('procedure_type_id', $request->procedure_type_id)
                                                    ->where('centralize', 1)->where('centralize_code', '<>', NULL)->where('id', '<>', $paymentschedule->id)
                                                    ->where('deleted_at', NULL)->orderBy('id', 'DESC')->first();
                // Si ya existen planillas centralizadas para ese periodo y tipo de planilla
                if($centralize_paymentschedule){
                    $paymentschedule->centralize_code = $centralize_paymentschedule->centralize_code;
                    $paymentschedule->save();
                }else{
                    $paymentschedule->centralize_code = $paymentschedule->id.'-C';
                    $paymentschedule->save();
                }
            }
        
            $contract_id = json_decode($request->contract_id);
            $worked_days = json_decode($request->worked_days);
            $salary = json_decode($request->salary);
            $partial_salary = json_decode($request->partial_salary);
            $job = json_decode($request->job);
            $job_level = json_decode($request->job_level);
            $seniority_bonus_percentage = json_decode($request->seniority_bonus_percentage);
            $seniority_bonus_amount = json_decode($request->seniority_bonus_amount);
            $solidary = json_decode($request->solidary);
            $common_risk = json_decode($request->common_risk);
            $afp_commission = json_decode($request->afp_commission);
            $retirement = json_decode($request->retirement);
            $solidary_national = json_decode($request->solidary_national);
            $labor_total = json_decode($request->labor_total);
            $solidary_employer = json_decode($request->solidary_employer);
            $housing_employer = json_decode($request->housing_employer);
            $health = json_decode($request->health);
            $rc_iva_amount = json_decode($request->rc_iva_amount);
            $faults_quantity = json_decode($request->faults_quantity);
            $faults_amount = json_decode($request->faults_amount);
            $liquid_payable = json_decode($request->liquid_payable);
            
            for ($i=0; $i < count($contract_id); $i++) {
                PaymentschedulesDetail::create([
                    'paymentschedule_id' => $paymentschedule->id,
                    'contract_id' => $contract_id[$i],
                    'afp' => Contract::find($contract_id[$i])->person->afp,
                    'worked_days' => $worked_days[$i],
                    'salary' => $salary[$i],
                    'partial_salary' => $partial_salary[$i],
                    'job' => $job[$i],
                    'job_level' => $job_level[$i],
                    'seniority_bonus_percentage' => $seniority_bonus_percentage[$i],
                    'seniority_bonus_amount' => $seniority_bonus_amount[$i],
                    'solidary' => $solidary[$i],
                    'common_risk' => $common_risk[$i],
                    'afp_commission' => $afp_commission[$i],
                    'retirement' => $retirement[$i],
                    'solidary_national' => $solidary_national[$i],
                    'labor_total' => $labor_total[$i],
                    'solidary_employer' => $solidary_employer[$i],
                    'housing_employer' => $housing_employer[$i],
                    'health' => $health[$i],
                    'rc_iva_amount' => $rc_iva_amount[$i],
                    'faults_quantity' => $faults_quantity[$i],
                    'faults_amount' => $faults_amount[$i],
                    'liquid_payable' => $liquid_payable[$i],
                ]);
            }
            
            DB::commit();

            return redirect()->route('paymentschedules.index')->with(['message' => 'Planilla generada correctamente', 'alert-type' => 'success']);
        } catch (\Throwable $th) {
            DB::rollback();
            // dd($th);
            return redirect()->route('paymentschedules.index')->with(['message' => 'Ocurri?? un error', 'alert-type' => 'error']);
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
        $afp = request('afp');
        $print = request('print');
        $centralize = request('centralize');
        $program = request('program');
        $group = request('group');
        $print_type = request('print_type');
        $excel = request('excel');
        $type_excel = request('type_excel');

        $data = Paymentschedule::with(['user', 'direccion_administrativa', 'period', 'procedure_type', 'details.contract' => function($q){
                        $q->where('deleted_at', NULL)->orderBy('id', 'DESC')->get();
                    }, 'details' => function($q){
                        $q->where('deleted_at', NULL);
                    }])
                    ->where('id', $id)->where('deleted_at', NULL)->first();
        
        if($centralize){
            $centralize_code = $data->centralize_code;
            $data->details = PaymentschedulesDetail::with(['contract.program', 'contract.type'])
                            ->whereHas('paymentschedule', function($q) use($centralize_code){
                                $q->where('centralize_code', $centralize_code)->where('deleted_at', NULL);
                            })
                            ->where('deleted_at', NULL)
                            ->get();
        }

        // Si se elije una AFP, se filtran los contratos que correspondan a esa AFP
        if($afp){
            $details = collect();
            foreach($data->details as $detail){
                if($detail->afp == $afp){
                    $details->push($detail);
                }
            }
            $data->details = $details;
        }

        // Si se elije un programa se debe filtrar todos los contratos que pertenecen a ese programa
        if($program){
            $details = collect();
            foreach($data->details as $detail){
                if($detail->contract->program_id == $program){
                    $details->push($detail);
                }
            }
            $data->details = $details;
            $program = Program::findOrFail($program);
        }

        if($print){
            return view('paymentschedules.print', compact('data', 'afp', 'centralize', 'program', 'group', 'print_type'));
        }

        return view('paymentschedules.read', compact('data', 'afp', 'centralize'));
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

    public function update_status(Request $request)
    {
        // dd($request->all());
        DB::beginTransaction();
        try {
            
            // Si se seleccion?? mas de una planilla, se debe actualizar el estado de todas las planillas seleccionadas
            if($request->centralize){
                if(!$request->id){
                    return response()->json(['error' => 'Debe seleccionar al menos 1 planilla']);
                }

                $paymentschedules = Paymentschedule::with(['details.contract.person', 'details' => function($query){
                    $query->where('deleted_at', NULL);
                }])
                ->whereIn('id', $request->id)
                ->where('deleted_at', NULL)->get();
                
                if($request->status == 'aprobada'){

                    // Capturar todos los items de pagos
                    $paymentschedules_joined = collect();
                    foreach ($paymentschedules as $paymentschedule) {
                        Paymentschedule::where('id', $paymentschedule->id)->update(['status' => $request->status]);
                        foreach ($paymentschedule->details as $item) {
                            $paymentschedules_joined->push($item);
                        }

                        PaymentschedulesHistory::create([
                            'paymentschedule_id' => $paymentschedule->id,
                            'user_id' => Auth::user()->id,
                            'type' => $request->status
                        ]);
                    }

                    // dd($paymentschedules_joined);
                    
                    // Recorrer los items agrupados por afp
                    $details = $paymentschedule->procedure_type_id == 1 ? $paymentschedules_joined->groupBy('afp')->sortBy('contract.job_id') : $paymentschedules_joined->groupBy('afp');
                    foreach ($details as $afp) {
                        $cont = 1;
                        foreach ($afp as $item) {
                            PaymentschedulesDetail::where('id', $item->id)->update(['item' => $cont]);
                            $cont++;
                        }
                    }
                }

            }else{
                $paymentschedule = Paymentschedule::findOrFail($request->id);
                // Actualizar el estado de la planilla
                $paymentschedule->update(['status' => $request->status]);

                // Si el estado es aprobada se le asiga el numero de item correspondiente
                if($request->status == 'aprobada'){

                    // Capturar todos los items de pagos
                    $paymentschedules_joined = collect();
                    foreach ($paymentschedule->details->where('deleted_at', NULL) as $item) {
                        $paymentschedules_joined->push($item);
                    }
                    
                    // Recorrer los items agrupados por afp
                    foreach ($paymentschedules_joined->groupBy('afp') as $afp) {
                        $cont = 1;
                        foreach ($afp as $item) {
                            PaymentschedulesDetail::where('id', $item->id)->update(['item' => $cont]);
                            $cont++;
                        }
                    }
                }
                
                if($request->status == 'habilitada'){
                    foreach ($paymentschedule->details->where('status', 'procesado') as $item) {
                        $detail = PaymentschedulesDetail::where('id', $item->id)->where('deleted_at', NULL)->first();
                        if(!$request->afp || $request->afp == $detail->afp){
                            $detail->update(['status' => 'habilitado']);
                        }
                    }
                }

                if($request->status == 'pagada' && ($request->pay_all || Auth::user()->direccion_administrativa_id)){
                    PaymentschedulesDetail::where('paymentschedule_id', $request->id)->where('deleted_at', NULL)->update([
                        'status' => 'pagado',
                    ]);
                }

                PaymentschedulesHistory::create([
                    'paymentschedule_id' => $request->id,
                    'user_id' => Auth::user()->id,
                    'type' => $request->status
                ]);
            }
            
            DB::commit();
            return response()->json(['message' => 'Cambio realizado exitosamente.']);
        } catch (\Throwable $th) {
            DB::rollback();
            return response()->json(['error' => 'Ocurri?? un error.']);
        }
    }

    public function update_centralize(Request $request){
        DB::beginTransaction();
        try {
            
            Paymentschedule::where('id', $request->id)->update(['centralize' => 0, 'centralize_code' => NULL]);
            
            DB::commit();
            return response()->json(['message' => 'Cambio realizado exitosamente.']);
        } catch (\Throwable $th) {
            DB::rollback();
            return response()->json(['error' => 'Ocurri?? un error.']);
        }
    }

    public function cancel(Request $request){
        DB::beginTransaction();
        try {
            if($request->observations == ''){
                return response()->json(['error' => 'Debe describir un motivo de anulaci??n']);
            }

            // Buscar que ningun item de planilla se haya pagado

            $paymentschedule = Paymentschedule::findOrFail($request->id);
            $paymentschedule->status = 'anulada';
            $paymentschedule->update();
            $paymentschedule->delete();
            PaymentschedulesDetail::where('paymentschedule_id', $paymentschedule->id)->where('deleted_at', NULL)
                ->update(['status' => 'anulado', 'deleted_at' => Carbon::now()]);

            if($paymentschedule->centralize_code){
                // Actualizar estado de todas las planillas que pertecencen al grupo centralizado
                Paymentschedule::where('centralize_code', $paymentschedule->centralize_code)
                                ->where('id', '<>', $paymentschedule->id)->where('deleted_at', NULL)->update(['status' => 'procesada']);
            }

            PaymentschedulesHistory::create([
                'paymentschedule_id' => $paymentschedule->id,
                'user_id' => Auth::user()->id,
                'type' => 'anulaci??n',
                'observations' => $request->observations
            ]);
            
            DB::commit();
            return response()->json(['message' => 'Planilla anulada exitosamente.']);
        } catch (\Throwable $th) {
            DB::rollback();
            return response()->json(['error' => 'Ocurri?? un error.']);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($centralize_code)
    {

    }

    // Archivos

    public function files_index()
    {
        return view('paymentschedules.files-browse');
    }

    public function files_list($search = null){
        $paginate = request('paginate') ?? 10;
        $data = PaymentschedulesFile::with(['user', 'details', 'direccion_administrativa', 'period', 'procedure_type'])
                    ->whereRaw(Auth::user()->direccion_administrativa_id ? 'user_id = '.Auth::user()->id : 1)
                    ->where(function($query) use ($search){
                        if($search){
                            $query->OrwhereHas('period', function($query) use($search){
                                $query->whereRaw($search ? 'name like "%'.$search.'%"' : 1);
                            })
                            ->OrWhereHas('user', function($query) use($search){
                                $query->whereRaw($search ? 'name like "%'.$search.'%"' : 1);
                            })
                            ->OrWhereHas('direccion_administrativa', function($query) use($search){
                                $query->whereRaw($search ? 'nombre like "%'.$search.'%"' : 1);
                            });
                        }
                    })
                    ->where('deleted_at', NULL)->orderBy('id', 'DESC')
                    ->paginate($paginate);
        // dd($data);
        return view('paymentschedules.files-list', compact('data', 'search'));
    }

    public function files_create(){
        $tipo_da = DireccionesTipo::with(['direcciones_administrativas'])->where('estado', 1)->get();
        return view('paymentschedules.files-edit-add', compact('tipo_da'));
    }

    public function files_generate(Request $request){
        DB::beginTransaction();
        try {
            $payment_schedules_file = PaymentschedulesFile::with(['details.person'])
                                        ->where('direccion_administrativa_id', $request->da_id)
                                        ->where('period_id', $request->period_id)
                                        ->where('procedure_type_id', $request->procedure_type_id)
                                        ->where('type', $request->file_type)->first();
            
            if($payment_schedules_file){
                $draft = true;
                return view('paymentschedules.files-generate', compact('payment_schedules_file', 'draft'));
            }
            
            $url = $request->file('file')->store(
                'paymentshedulesfiles', 'public'
            );

            $payment_schedules_file = PaymentschedulesFile::create([
                'user_id' => Auth::user()->id,
                'direccion_administrativa_id' => $request->da_id,
                'period_id' => $request->period_id,
                'procedure_type_id' => $request->procedure_type_id,
                'type' => $request->file_type,
                'observations' => $request->observations,
                'url' => $url
            ]);

            // dd($payment_schedules_file);

            Excel::import(new PaymentschedulesFilesImport($payment_schedules_file->id, $request->file_type), request()->file('file'));

            $payment_schedules_file = PaymentschedulesFile::find($payment_schedules_file->id);

            DB::commit();

            return view('paymentschedules.files-generate', compact('payment_schedules_file'));
        } catch (\Throwable $th) {
            DB::rollback();
            // dd($th);
            return response()->json(['error' => $th->getMessage()], 500);
        }
    }

    public function files_store(Request $request){
        DB::beginTransaction();
        try {

            PaymentschedulesFile::where('id', $request->id)->update([
                'status' => 'cargado'
            ]);
            DB::commit();

            return redirect()->route('paymentschedules.files.create')->with(['message' => 'Registros guardados correctamente.', 'alert-type' => 'success']);
        } catch (\Throwable $th) {
            DB::rollback();
            // dd($th);
            return redirect()->route('paymentschedules.files.create')->with(['message' => 'Ocurri?? un error.', 'alert-type' => 'error']);
        }
    }

    public function files_delete(Request $request){
        DB::beginTransaction();
        try {
            PaymentschedulesFile::where('id', $request->id)->delete();
            PaymentschedulesFilesDetails::where('paymentschedules_file_id', $request->id)->delete();
            DB::commit();

            if ($request->redirect) {
                return redirect()->route('paymentschedules.files.index')->with(['message' => 'Borrador anulado correctamente.', 'alert-type' => 'success']);
            }
            return redirect()->route('paymentschedules.files.create')->with(['message' => 'Borrador anulado correctamente.', 'alert-type' => 'success']);
        } catch (\Throwable $th) {
            DB::rollback();
            // dd($th);
            if ($request->redirect) {
                return redirect()->route('paymentschedules.files.index')->with(['message' => 'Ocurri?? un error.', 'alert-type' => 'error']);
            }
            return redirect()->route('paymentschedules.files.create')->with(['message' => 'Ocurri?? un error.', 'alert-type' => 'error']);
        }
    }
}
