<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Str;

use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;

// Imports
use App\Imports\PaymentschedulesFilesImport;
use App\Exports\PaymentsExport;

// Models
use App\Models\DireccionesTipo;
use App\Models\Direccion;
use App\Models\Contract;
use App\Models\PaymentschedulesFile;
use App\Models\PaymentschedulesFilesDetails;
use App\Models\Paymentschedule;
use App\Models\PaymentschedulesDetail;
use App\Models\Period;
use App\Models\PaymentschedulesHistory;
use App\Models\Program;
use App\Models\Person;
use App\Models\Bonus;
use App\Models\BonusesDetail;
use App\Models\ProcedureType;


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
        $this->custom_authorize('browse_paymentschedules');
        return view('paymentschedules.browse');
    }

    public function list($search = null){
        $paginate = request('paginate') ?? 10;
        $data = Paymentschedule::with(['user', 'direccion_administrativa', 'period', 'procedure_type', 'details' => function($query){
                        $query->where('deleted_at', NULL);
                    }, 'details.contract.person'])
                    ->whereRaw(Auth::user()->direccion_administrativa_id ? 'direccion_administrativa_id = '.Auth::user()->direccion_administrativa_id : 1)
                    ->whereRaw(Auth::user()->role_id >= 6 && Auth::user()->role_id <= 8 ? '(status = "aprobada" or status = "habilitada" or status = "pagada")' : 1)
                    ->whereRaw(Auth::user()->role_id == 25 ? '(procedure_type_id = 2 or (procedure_type_id = 5 and status != "procesada"))' : 1)
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
                            ->OrWhereRaw($search ? 'centralize_code like "%'.ltrim($search, "0").'%"' : 1)
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
        $this->custom_authorize('add_paymentschedules');
        $direccion_administrativa_id = Auth::user()->direccion_administrativa_id;
        $tipo_da = DireccionesTipo::with(['direcciones_administrativas' => function($q) use($direccion_administrativa_id){
                            $q->whereRaw($direccion_administrativa_id ? "id = $direccion_administrativa_id" : 1)->where('estado', 1);
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

            // En caso de ser centralizada asignarle el número correspondiente
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
            return redirect()->route('paymentschedules.index')->with(['message' => 'Ocurrió un error', 'alert-type' => 'error']);
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
        $this->custom_authorize('read_paymentschedules');
        $afp = request('afp');
        $cc = request('cc');
        $print = request('print');
        $centralize = request('centralize');
        $program = request('program');
        $group = request('group');
        $type_generate = request('type_generate');
        $excel = request('excel');
        $type_render = request('type_render');

        $data = Paymentschedule::with(['user', 'direccion_administrativa', 'period', 'procedure_type', 'details.contract' => function($q){
                        $q->where('deleted_at', NULL)->orderBy('id', 'DESC')->get();
                    }, 'details' => function($q) use($cc){
                        $q->whereRaw($cc ? "cc = $cc" : 1)->where('deleted_at', NULL);
                    }])
                    ->where('id', $id)->where('deleted_at', NULL)->first();
        
        if($centralize){
            $centralize_code = $data->centralize_code;
            $data->details = PaymentschedulesDetail::with(['contract.program', 'contract.type'])
                            ->whereHas('paymentschedule', function($q) use($centralize_code){
                                $q->where('centralize_code', $centralize_code)->where('deleted_at', NULL);
                            })
                            ->whereRaw($cc ? "cc = $cc" : 1)
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
            if($type_render == 1){
                $pdf = PDF::loadView('paymentschedules.print', compact('data', 'afp', 'cc', 'centralize', 'program', 'group', 'type_generate', 'type_render'));
                return $pdf->setPaper('legal', 'landscape')->stream();
            }elseif($type_render == 2){
                return view('paymentschedules.print', compact('data', 'afp', 'cc', 'centralize', 'program', 'group', 'type_generate', 'type_render'));
            }elseif($type_render == 3){
                // return view('paymentschedules.print', compact('data', 'afp', 'cc', 'centralize', 'program', 'group', 'type_generate', 'type_render'));
                return Excel::download(new PaymentsExport($data, $afp, $cc, $centralize, $program, $group, $type_generate, $type_render), 'Planilla '.str_pad($centralize ? $data->centralize_code : $data->id, 6, "0", STR_PAD_LEFT).($data->aditional ? '-A' : '').'.xlsx');
            }
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
            
            // Si se seleccionó mas de una planilla, se debe actualizar el estado de todas las planillas seleccionadas
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
                    PaymentschedulesDetail::where('paymentschedule_id', $request->id)
                    ->where('status', 'habilitado')->where('deleted_at', NULL)->update([
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
            return response()->json(['error' => 'Ocurrió un error.']);
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
            return response()->json(['error' => 'Ocurrió un error.']);
        }
    }

    public function cancel(Request $request){
        DB::beginTransaction();
        try {
            if($request->observations == ''){
                return response()->json(['error' => 'Debe describir un motivo de anulación']);
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
                'type' => 'anulación',
                'observations' => $request->observations
            ]);
            
            DB::commit();
            return response()->json(['message' => 'Planilla anulada exitosamente.']);
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
        $tipo_da = DireccionesTipo::with(['direcciones_administrativas' => function($q){
                        $q->where('estado', 1);            
                    }])->where('estado', 1)->get();
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

            return redirect()->route('paymentschedules-files.create')->with(['message' => 'Registros guardados correctamente.', 'alert-type' => 'success']);
        } catch (\Throwable $th) {
            DB::rollback();
            // dd($th);
            return redirect()->route('paymentschedules-files.create')->with(['message' => 'Ocurrió un error.', 'alert-type' => 'error']);
        }
    }

    public function files_delete(Request $request){
        DB::beginTransaction();
        try {
            PaymentschedulesFile::where('id', $request->id)->delete();
            PaymentschedulesFilesDetails::where('paymentschedules_file_id', $request->id)->delete();
            DB::commit();

            if ($request->redirect) {
                return redirect()->route('paymentschedules-files.index')->with(['message' => 'Borrador anulado correctamente.', 'alert-type' => 'success']);
            }
            return redirect()->route('paymentschedules-files.create')->with(['message' => 'Borrador anulado correctamente.', 'alert-type' => 'success']);
        } catch (\Throwable $th) {
            DB::rollback();
            // dd($th);
            if ($request->redirect) {
                return redirect()->route('paymentschedules-files.index')->with(['message' => 'Ocurrió un error.', 'alert-type' => 'error']);
            }
            return redirect()->route('paymentschedules-files.create')->with(['message' => 'Ocurrió un error.', 'alert-type' => 'error']);
        }
    }

    // Aguinaldo

    public function bonuses_index(){
        $this->custom_authorize('browse_bonuses');
        $bonus = Bonus::where('deleted_at', NULL)
                    ->whereRaw(Auth::user()->direccion_administrativa_id ? "direccion_id = ".Auth::user()->direccion_administrativa_id : 1)
                    ->orderBy('id', 'DESC')->get();
        return view('paymentschedules.bonuses-browse', compact('bonus'));
    }

    public function bonuses_create(){
        $this->custom_authorize('add_bonuses');
        $direcciones = Direccion::where('deleted_at', NULL)->where('estado', 1)
                        ->whereRaw(Auth::user()->direccion_administrativa_id ? "id = ".Auth::user()->direccion_administrativa_id : 1)->get();
        return view('paymentschedules.bonuses-edit-add', compact('direcciones'));
    }

    public function bonuses_generate(Request $request){
        $direccion_id = $request->direccion_id;
        $year = $request->year;

        $bonus = Bonus::where('deleted_at', null)
                    ->where('direccion_id', $direccion_id)->where('year', $year)->first();
        if($bonus){
            return response()->json(['error' => 'Ya se generó la planilla de aguinaldos.']);
        }

        $people = Person::where('deleted_at', NULL)
                    ->whereHas('contracts', function($q) use($direccion_id){
                        $q->where('direccion_administrativa_id', $direccion_id)
                        ->whereRaw('(procedure_type_id = 1 or procedure_type_id = 5)')->where('deleted_at', NULL);
                    })
                    ->orderBy('last_name')
                    // ->limit(20)
                    ->get();
        // dd($people);
        $cont = 0;
        foreach ($people as $person) {
            $contracts = Contract::where('deleted_at', NULL)
                            ->whereHas('paymentschedules_details', function($q){
                                $q->where('deleted_at', NULL);
                            })
                            ->whereRaw("(procedure_type_id = 1 or procedure_type_id = 5)")
                            ->where('deleted_at', NULL)->where('person_id', $person->id)->orderBy('start', 'ASC')->get();
            $bonus = collect();
            // Si tiene al menos un contrato
            if(count($contracts) > 0){
                $start = date('Y', strtotime($contracts[0]->start)) == $year ? Carbon::createFromFormat('Y-m-d', $contracts[0]->start) : Carbon::createFromFormat('Y-m-d', $year.'-01-01');
                
                // Si el año de finalización es diferente al año actual
                if(date('Y', strtotime($contracts[0]->finish)) != date('Y')){
                    $finish = Carbon::createFromFormat('Y-m-d', $year.'-12-30');
                }else{
                    $finish = Carbon::createFromFormat('Y-m-d', $contracts[0]->finish);
                }

                // si tiene mas de 1 contrato
                if(count($contracts) > 1){
                    $contracts_bonus = collect();
                    for ($i=1; $i < count($contracts); $i++) { 

                        // filtrar que los contratos estén en orden cronológico
                        if($start->format('Y-m-d') < $contracts[$i]->start){
                            $current_finish = Carbon::createFromFormat('Y-m-d', $finish->format('Y-m-d'));
                            $new_start = Carbon::createFromFormat('Y-m-d', $contracts[$i]->start);
                            if($current_finish->diffInDays($new_start) == 1){
                                $finish = Carbon::createFromFormat('Y-m-d', $contracts[$i]->finish ?? $year.'-12-30');

                                // Si el primer contrato no se corta con el segundo agregamos a la lista
                                if($i == 1){
                                    $contracts_bonus->push($contracts[0]->id);
                                }else{
                                    $contracts_bonus->push($contracts[$i -1]->id);    
                                }

                                // Agregamos el contrato a la lista
                                $contracts_bonus->push($contracts[$i]->id);

                                if($i == count($contracts) -1){
                                    if($start->diffInMonths($finish) >= 2){
                                        $duration_contract = contract_duration_calculate($start->format('Y-m-d'), $finish->format('Y-m-d'));
                                        if($duration_contract->months *30 + $duration_contract->days >= 90){
                                            $bonus->push(["start" => $start->format('Y-m-d'), "finish" => $finish->format('Y-m-d'), "contracts" => $contracts_bonus]);
                                            $contracts_bonus = collect();
                                        }
                                    }
                                }
                            }else{
                                if($start->diffInMonths($finish) >= 2){
                                    $duration_contract = contract_duration_calculate($start->format('Y-m-d'), $finish->format('Y-m-d'));
                                    if($duration_contract->months *30 + $duration_contract->days >= 90){
                                        $contracts_bonus->push($contracts[$i -1]->id);
                                        $bonus->push(["start" => $start->format('Y-m-d'), "finish" => $finish->format('Y-m-d'), "contracts" => $contracts_bonus]);
                                        $contracts_bonus = collect();
                                    }
                                }

                                if($i == count($contracts) -1){
                                    $current_finish = Carbon::createFromFormat('Y-m-d', $contracts[$i]->start);
                                    $new_start = Carbon::createFromFormat('Y-m-d', $contracts[$i]->finish ?? $year.'-12-30');
                                    if($current_finish->diffInMonths($new_start) >= 2){
                                        $duration_contract = contract_duration_calculate($contracts[$i]->start, $contracts[$i]->finish ?? $year.'-12-30');
                                        if($duration_contract->months *30 + $duration_contract->days >= 90){
                                            $contracts_bonus->push($contracts[$i]->id);
                                            $bonus->push(["start" => $contracts[$i]->start, "finish" => $contracts[$i]->finish ?? $year.'-12-30', "contracts" => $contracts_bonus]);
                                            $contracts_bonus = collect();
                                        }
                                    }
                                }

                                $start = Carbon::createFromFormat('Y-m-d', $contracts[$i]->start);
                                $finish = Carbon::createFromFormat('Y-m-d', $contracts[$i]->finish ?? $year.'-12-30');
                                
                            }
                        }
                    }
                }else{
                    if($start->diffInMonths($finish) >= 2){
                        $duration_contract = contract_duration_calculate($start->format('Y-m-d'), $finish->format('Y-m-d'));
                        if($duration_contract->months *30 + $duration_contract->days >= 90){
                            $contracts_bonus = collect($contracts[0]->id);
                            $bonus->push(["start" => $start->format('Y-m-d'), "finish" => $finish->format('Y-m-d'), "contracts" => $contracts_bonus]);
                        }
                    }
                }
            }

            $people[$cont]->bonus = $bonus;

            $cont++;
        }

        $cont = 0;
        foreach ($people as $person) {
            if(count($person->bonus) > 0){
                $amounts = collect();
                // dd($person->bonus);
                foreach ($person->bonus as $bonus) {
                    $acumulate_days = 0;
                    $acumulate_amount = 0;
                    $partial_amounts = collect();

                    $total_duration = contract_duration_calculate($bonus["start"], $bonus["finish"]);
                    $total_duration_days = $total_duration->months *30 + $total_duration->days;
                    foreach ($bonus['contracts']->sortDesc() as $item) {
                        // Obtener duración del contrato
                        $contract = Contract::where('id', $item)->first();
                        $duration = contract_duration_calculate($contract->start, $contract->finish ?? $year.'-12-30');
                        $days = $duration->months *30 + $duration->days;
                        
                        // Obtener salario del contrato
                        $salary = 0;
                        if ($contract->cargo){
                            $salary = $contract->cargo->nivel->where('IdPlanilla', $contract->cargo->idPlanilla)->first()->Sueldo;
                        }elseif ($contract->job){
                            $salary = $contract->job->salary;
                        }

                        // Calcular bono antigüedad
                        $minimum_salary = setting('planillas.minimum_salary') ?? 2164;
                        $seniority_bonus_percentage = 0;
                        $seniority_bonus_amount = 0;

                        // Si el tipo de planilla es de personal funcionamiento se calcula el bono antigüedad
                        if($contract->procedure_type_id  == 1){
                            if(count($person->seniority_bonus) > 0){
                                if(date('Ym', strtotime($person->seniority_bonus->first()->start)) <= $year.'09'){
                                    $seniority_bonus_percentage = $person->seniority_bonus->first()->type->percentage;
                                    $seniority_bonus_amount = number_format($minimum_salary * ($seniority_bonus_percentage /100), 2, '.', '');
                                }
                            }
                        }

                        if($days >= 90){
                            $partial_amounts->push([
                                "days" => $acumulate_days == 0 ? 90 : 90 - $acumulate_days,
                                "amount" => $salary,
                                "bonus" => $seniority_bonus_amount
                            ]);
                        }else{
                            if($acumulate_days + $days > 90){
                                $quantity_days = 90 - $acumulate_days;
                            }else{
                                $quantity_days = $days;
                            }
                            $partial_amounts->push([
                                "days" => $quantity_days,
                                "amount" => $salary,
                                "bonus" => $seniority_bonus_amount
                            ]);
                        }

                        $acumulate_days += $days;

                        if($acumulate_days >= 90){
                            break;
                        }
                    }
                    // Agregar la duración total de los proyectos que estan incluidos en ese lapso de tiempo
                    $amounts->push(collect(["duration" => $total_duration_days, "partial_amounts" => $partial_amounts, "contract" => $bonus["contracts"]]));
                }

                $people[$cont]->amounts = $amounts;
                $people[$cont]->last_contract = $contract;
            }
            $cont++;
        }
        
        $cont = 0;
        foreach ($people as $person) {
            if($person->amounts){
                $index = 0;
                foreach ($person->amounts->sortKeys() as $amount) {
                    $days = 0;
                    $last_contract = null;
                    $months = collect();
                    // if($person->ci == '7602393'){
                    //     dd($amount['contract']);
                    // }
                    foreach ($amount['contract']->sortKeysDesc() as $item) {
                        $contract = Contract::find($item);
                        $contract_continue = true;
                        
                        // Si ya estamos recorriendo el segundo contrato de la lista
                        if($last_contract){
                            // Obtener el inicio del ultimo contrato almacenado en la variable
                            $start = Carbon::createFromFormat('Y-m-d', $last_contract->start);
                            // Obtener el fin del siguente contrato de la lista
                            $finish = Carbon::createFromFormat('Y-m-d', $contract->finish ?? $year.'-12-30');

                            // Si la diferencia es mayor a 1 día ya no se sigue obteniendo el sueldo de ese mes hasta completar los 90 días
                            if($finish->diffInDays($start) > 1){
                                $contract_continue = false;
                            }
                        }
                        
                        $last_contract = $contract;
                        if($contract_continue){
                            foreach ($contract->paymentschedules_details->sortByDesc('paymentschedule.period.name') as $payments) {
                                if(
                                    $payments->paymentschedule->period->name != $year.'12' && // Que el periodo consultado no sea diciembre
                                    (date('Ym', strtotime($contract->finish)) != $payments->paymentschedule->period->name || date('d', strtotime($contract->finish)) >= 30 || count($months) > 0 /* Que el periodo consultado no sea el mes de finalización de contrato o q sea fin de mes*/)
                                ){
                                    if($days >= 90){
                                        break;
                                    }
                                    $days += $payments->worked_days;
                                    $months->push([
                                        'partial_salary' => $payments->partial_salary,
                                        'seniority_bonus_amount' => $payments->seniority_bonus_amount,
                                        'worked_days' => $payments->worked_days,
                                        'period' => $payments->paymentschedule->period->name
                                    ]);
                                }
                            }
                            if($days >= 90){
                                break;
                            }
                        }
                    }

                    $people[$cont]->amounts[$index]->put('months', $months);
                    $index++;
                }
            }
            $cont++;
        }

        // Eliminar a las personas que no cumplan con los 90 días
        $people = $people->reject(function ($value, $key) {
            return $value->last_contract ? false : true;
        });

        $direccion = Direccion::find($direccion_id);

        return view('paymentschedules.bonuses-generate', compact('people', 'direccion', 'year'));
    }

    public function bonuses_store(Request $request){
        $direccion_id = $request->direccion_id;
        $year = $request->year;

        $bonus = Bonus::where('deleted_at', null)
                    ->where('direccion_id', $direccion_id)->where('year', $year)->first();
        if($bonus){
            return redirect()->route('bonuses.create')->with(['message' => 'Ya se generó la planilla de aguinaldos.', 'alert-type' => 'error']);
        }

        DB::beginTransaction();

        $bonus = Bonus::create([
            'user_id' => Auth::user()->id,
            'direccion_id' => $direccion_id,
            'year' => $year
        ]);

        try {
            for ($i=0; $i < count($request->contract_id); $i++) { 
                BonusesDetail::create([
                    'bonus_id' => $bonus->id,
                    'contract_id' => $request->contract_id[$i],
                    'procedure_type_id' => $request->procedure_type_id[$i],
                    'partial_salary_1' => $request->partial_salary_1[$i],
                    'seniority_bonus_1' => $request->seniority_bonus_1[$i],
                    'partial_salary_2' => $request->partial_salary_2[$i],
                    'seniority_bonus_2' => $request->seniority_bonus_2[$i],
                    'partial_salary_3' => $request->partial_salary_3[$i],
                    'seniority_bonus_3' => $request->seniority_bonus_3[$i],
                    'days' => $request->days[$i],
                ]);
            }
            DB::commit();
            return redirect()->route('bonuses.index')->with(['message' => 'Planilla de aguinaldos registrada correctamente.', 'alert-type' => 'success']);
        } catch (\Throwable $th) {
            DB::rollback();
            // dd($th);
            return redirect()->route('bonuses.create')->with(['message' => 'Ocurrió un error en el servidor.', 'alert-type' => 'error']);
        }
    }

    public function bonuses_show($id){
        $bonus = Bonus::find($id);
        return view('paymentschedules.bonuses-read', compact('bonus'));
    }

    public function bonuses_update($id){
        $bonus = Bonus::find($id);
        $bonus->status = 2;
        $bonus->update();
        return redirect()->route('bonuses.index')->with(['message' => 'Planilla de aguinaldos promovida exitosamente.', 'alert-type' => 'success']);
    }

    public function bonuses_print($id, Request $request){
        $type_render = $request->type_render;
        $signature_field = $request->signature_field;
        $program = $request->program_id ? Program::find($request->program_id) : null;
        $bonus = Bonus::with(['details' => function($q) use($request){
                    $q->where('procedure_type_id', $request->procedure_type_id)->where('deleted_at', NULL);
                }, 'details.contract' => function($q) use($program){
                    $q->whereRaw($program ? 'program_id = '.$program->id : 1);
                }])->where('id', $id)->where('deleted_at', NULL)->first();
        $procedure_type = ProcedureType::find($request->procedure_type_id);

        if($type_render == 1){
            $pdf = PDF::loadView('paymentschedules.bonuses-print', compact('bonus', 'procedure_type', 'type_render', 'signature_field', 'program'));
            return $pdf->setPaper('legal', 'landscape')->stream();
        }elseif($type_render == 2){
            return view('paymentschedules.bonuses-print', compact('bonus', 'procedure_type', 'type_render', 'signature_field', 'program'));
        }

        // return view('paymentschedules.bonuses-print', compact('bonus', 'procedure_type', 'type_render'));
    }

    public function bonuses_delete($id, Request $request){
        DB::beginTransaction();
        try {

            Bonus::where('id', $id)->delete();
            BonusesDetail::where('bonus_id', $id)->delete();

            DB::commit();
            return redirect()->route('bonuses.index')->with(['message' => 'Planilla de aguinaldos eliminada correctamente.', 'alert-type' => 'success']);
        } catch (\Throwable $th) {
            DB::rollback();
            //throw $th;
            return redirect()->route('bonuses.index')->with(['message' => 'Ocurrió un error en el servidor.', 'alert-type' => 'error']);
        }
    }

    public function bonuses_recipes($id, $detail_id = null){
        $bonus = Bonus::with(['details' => function($q) use($detail_id){
                        $q->whereRaw($detail_id ? 'id = '.$detail_id : 1);
                    }])->where('id', $id)->where('deleted_at', NULL)->first();
        // return view('planillas.bonuses-recipe-group', compact('bonus'));
        $pdf = PDF::loadView('planillas.bonuses-recipe-group', compact('bonus'));
        return $pdf->setPaper('letter')->stream();
    }
}
