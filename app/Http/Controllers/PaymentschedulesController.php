<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Str;

use Maatwebsite\Excel\Facades\Excel;

// Imporst
use App\Imports\PaymentschedulesFilesImport;

// Models
use App\Models\TipoDireccionAdministrativa;
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
                    }])
                    ->whereRaw(Auth::user()->direccion_administrativa_id ? 'user_id = '.Auth::user()->id : 1)
                    ->where(function($query) use ($search){
                        if($search){
                            $query->OrwhereHas('period', function($query) use($search){
                                $query->whereRaw($search ? 'name like "%'.$search.'%"' : 1);
                            })
                            ->OrWhereHas('user', function($query) use($search){
                                $query->whereRaw($search ? 'name like "%'.$search.'%"' : 1);
                            });
                        }
                    })
                    ->where('status', '!=', 'borrador')
                    ->where('status', '!=', 'anulada')
                    ->where('deleted_at', NULL)->orderBy('id', 'DESC')->paginate($paginate);
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
        $tipo_da = TipoDireccionAdministrativa::with(['direcciones_administrativas'])->where('Estado', 1)->get();
        // dd($tipo_da);
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
        if($paymentschedule){
            return view('paymentschedules.generate', compact('paymentschedule'));
        }

        $period = Period::findOrFail($request->period_id);
        $year = Str::substr($period->name, 0, 4);
        $month = Str::substr($period->name, 5, 2);

        $contracts = Contract::with(['user', 'person.seniority_bonus.type', 'person.seniority_bonus' => function($q){
                            $q->where('deleted_at', NULL)->where('status', 1);
                        }, 'program', 'cargo.nivel' => function($q){
                            $q->where('Estado', 1);
                        }, 'job.direccion_administrativa', 'direccion_administrativa', 'type'])
                        ->where('direccion_administrativa_id', $direccion_administrativa_id)
                        ->where('procedure_type_id', $procedure_type_id)
                        ->whereRaw('CONCAT(YEAR(start), MONTH(start)) <= "'.$year.intval($month).'"')
                        // ->whereRaw('CONCAT(YEAR(finish), MONTH(finish)) >= "'.$year.$month.'"')
                        ->whereRaw("id not in (select pd.contract_id from paymentschedules as p, paymentschedules_details as pd
                                                    where p.id = pd.paymentschedule_id and p.period_id = ".$period->id." and p.deleted_at is null and pd.deleted_at is null)")
                        ->where('status', 'firmado')
                        ->where('deleted_at', NULL)->get();

        $paymentschedules_file = PaymentschedulesFile::with(['details'])
                                    ->where('direccion_administrativa_id', $direccion_administrativa_id)
                                    ->where('period_id', $period->id)
                                    ->where('procedure_type_id', $procedure_type_id)
                                    ->where('status', 'cargado')->get();
        dd($contracts);

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

            // Verificar si no existe una planilla generada para la DA, el periodo y tipo de planilla
            $paymentschedule = Paymentschedule::where([
                'direccion_administrativa_id' =>  $request->direccion_administrativa_id,
                'period_id' => $request->period_id,
                'procedure_type_id' => $request->procedure_type_id,
                'deleted_at' => NULL,
            ])->first();
            if($paymentschedule){
                return redirect()->route('paymentschedules.index')->with(['message' => 'Ya se generó una planilla con los datos ingresados.', 'alert-type' => 'error']);
            }

            // Registrar nueva planilla
            $paymentschedule = Paymentschedule::create([
                'direccion_administrativa_id' =>  $request->direccion_administrativa_id,
                'period_id' => $request->period_id,
                'procedure_type_id' => $request->procedure_type_id,
                'centralize' => $request->centralize,
                'observations' => $request->observations,
                'status' => 'procesada',
                'user_id' => Auth::user()->id,
            ]);
            // dd($paymentschedule);

            // En caso de ser centralizada asignarle el número correspondiente
            if($request->centralize){
                $centralize_paymentschedule = Paymentschedule::where('period_id', $request->period_id)
                                                    ->where('procedure_type_id', $request->procedure_type_id)
                                                    ->where('centralize', 1)->where('id', '<>', $paymentschedule->id)->where('deleted_at', NULL)->first();
                // Si ya existen planillas centralizadas para ese periodo y tipo de planilla
                if($centralize_paymentschedule){
                    $paymentschedule->centralize_code = $centralize_paymentschedule->centralize_code;
                    $paymentschedule->save();
                }else{
                    $paymentschedule->centralize_code = $paymentschedule->id.'-c';
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

            return redirect()->route('paymentschedules.index')->with(['message' => 'Planilla generada correctamente.', 'alert-type' => 'success']);
        } catch (\Throwable $th) {
            DB::rollback();
            dd($th);
            return redirect()->route('paymentschedules.index')->with(['message' => 'Ocurrió un error.', 'alert-type' => 'error']);
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

        $data = Paymentschedule::with(['user', 'direccion_administrativa', 'period', 'procedure_type', 'details.contract' => function($q){
                        $q->where('deleted_at', NULL)->orderBy('id', 'DESC')->get();
                    }])
                    ->where('id', $id)->where('deleted_at', NULL)->first();
        
        if($centralize){
            $centralize_code = $data->centralize_code;
            $data->details = PaymentschedulesDetail::with(['contract.program'])
                            ->whereHas('paymentschedule', function($q) use($centralize_code){
                                $q->where('centralize_code', $centralize_code);
                            })
                            ->where('deleted_at', NULL)->orderBy('item', 'ASC')->get();
        }

        // Si se elije una AFP, se filtran los contratos que correspondan a esa AFP
        if($afp){
            $details = collect();
            foreach($data->details as $detail){
                if($detail->contract->person->afp == $afp){
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
            return view('paymentschedules.print', compact('data', 'afp', 'centralize', 'program'));
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
        DB::beginTransaction();
        try {
            $paymentschedule = Paymentschedule::findOrFail($request->id);
            // Si es centralizada, se debe actualizar el estado de todas las planillas que están asociadas a la centralización
            if($paymentschedule->centralize_code){
                // Actualizar estado de todas las planillas que pertecencen al grupo centralizado
                Paymentschedule::where('centralize_code', $paymentschedule->centralize_code)->where('deleted_at', NULL)->update(['status' => $request->status]);
                
                if($request->status == 'enviada'){
                    // Obtener el detalle de las planillas que pertenecen al grupo de planillas centralizadas
                    // y actualizar su item
                    $paymentschedules = Paymentschedule::with(['details' => function($query){
                        $query->where('deleted_at', NULL);
                    }])
                    ->where('centralize_code', $paymentschedule->centralize_code)
                    ->where('deleted_at', NULL)->orderBy('direccion_administrativa_id', 'ASC')->get();

                    $cont = 1;
                    foreach ($paymentschedules as $paymentschedule) {
                        foreach ($paymentschedule->details as $item) {
                            PaymentschedulesDetail::where('id', $item->id)->update(['item' => $cont]);
                            $cont++;
                        }
                    }
                }

                if($request->status == 'habilitada'){
                    $paymentschedules = Paymentschedule::with(['details' => function($query){
                        $query->where('deleted_at', NULL);
                    }])
                    ->where('centralize_code', $paymentschedule->centralize_code)
                    ->where('deleted_at', NULL)->orderBy('direccion_administrativa_id', 'ASC')->get();
                    foreach ($paymentschedules as $paymentschedule) {
                        foreach ($paymentschedule->details->where('status', 'procesado') as $item) {
                            $detail = PaymentschedulesDetail::where('id', $item->id)->first();
                            if(!$request->afp || $request->afp == $detail->contract->person->afp){
                                $detail->update(['status' => 'habilitado']);
                            }
                        }
                    }
                }

            }else{
                $paymentschedule->update(['status' => $request->status]);

                if($request->status == 'enviada'){
                    $cont = 1;
                    foreach ($paymentschedule->details as $item) {
                        PaymentschedulesDetail::where('id', $item->id)->update(['item' => $cont]);
                        $cont++;
                    }
                }
                
                if($request->status == 'habilitada'){
                    $cont = 1;
                    foreach ($paymentschedule->details->where('status', 'procesado') as $item) {
                        $detail = PaymentschedulesDetail::where('id', $item->id)->where('deleted_at', NULL)->first();
                        if(!$request->afp || $request->afp == $detail->contract->person->afp){
                            $detail->update(['status' => 'habilitado']);
                        }
                    }
                }
            }
            
            DB::commit();
            return redirect()->route('paymentschedules.index')->with(['message' => 'Estado actualizado correctamente.', 'alert-type' => 'success']);
        } catch (\Throwable $th) {
            DB::rollback();
            dd($th);
            return redirect()->route('paymentschedules.index')->with(['message' => 'Ocurrió un error.', 'alert-type' => 'error']);
        }
    }

    public function cancel(Request $request){
        DB::beginTransaction();
        try {
            if($request->observations == ''){
                return redirect()->route('paymentschedules.index')->with(['message' => 'Debe describir un motivo de anulación.', 'alert-type' => 'error']); 
            }

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
            return redirect()->route('paymentschedules.index')->with(['message' => 'Planilla Anulada correctamente.', 'alert-type' => 'success']);
        } catch (\Throwable $th) {
            DB::rollback();
            // dd($th);
            return redirect()->route('paymentschedules.index')->with(['message' => 'Ocurrió un error.', 'alert-type' => 'error']);
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
                            });
                        }
                    })
                    ->where('deleted_at', NULL)->orderBy('id', 'DESC')
                    ->paginate($paginate);
        // dd($data);
        return view('paymentschedules.files-list', compact('data', 'search'));
    }

    public function files_create(){
        $tipo_da = TipoDireccionAdministrativa::with(['direcciones_administrativas'])->where('Estado', 1)->get();
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
            return redirect()->route('paymentschedules.files.create')->with(['message' => 'Ocurrió un error.', 'alert-type' => 'error']);
        }
    }

    public function files_delete(Request $request){
        DB::beginTransaction();
        try {

            PaymentschedulesFile::where('id', $request->id)->delete();
            PaymentschedulesFilesDetails::where('paymentschedules_file_id', $request->id)->delete();
            DB::commit();

            return redirect()->route('paymentschedules.files.create')->with(['message' => 'Borrador anulado correctamente.', 'alert-type' => 'success']);
        } catch (\Throwable $th) {
            DB::rollback();
            // dd($th);
            return redirect()->route('paymentschedules.files.create')->with(['message' => 'Ocurrió un error.', 'alert-type' => 'error']);
        }
    }
}
