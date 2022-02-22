<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

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
        $data = Paymentschedule::with(['user', 'details', 'direccion_administrativa', 'period', 'procedure_type'])
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
        $period_id = $request->period_id;
        $contracts = Contract::with(['user', 'person.seniority_bonus.type', 'person.seniority_bonus' => function($q){
                            $q->where('deleted_at', NULL)->where('status', 'borrador')->orderBy('id', 'DESC')->first();
                        }, 'program', 'cargo.nivel' => function($q){
                            $q->where('Estado', 1);
                        }, 'job.direccion_administrativa', 'direccion_administrativa', 'type'])
                        ->where('direccion_administrativa_id', $request->da_id)
                        ->where('procedure_type_id', $request->procedure_type_id)
                        ->where('status', 'firmado')
                        ->where('deleted_at', NULL)->get();
            
        $paymentschedules_file = PaymentschedulesFile::with(['details'])
                                    ->where('direccion_administrativa_id', $request->da_id)
                                    ->where('period_id', $request->period_id)
                                    ->where('procedure_type_id', $request->procedure_type_id)
                                    ->where('status', 'borrador')->get();

        $paymentschedule = Paymentschedule::firstOrCreate([
            'direccion_administrativa_id' =>  $request->da_id,
            'period_id' => $request->period_id,
            'procedure_type_id' => $request->procedure_type_id,
            'deleted_at' => NULL,
        ]);

        if($paymentschedule->status == NULL){
            $paymentschedule->status = 'borrador';
            $paymentschedule->save();
        }

        return view('paymentschedules.generate', compact('contracts', 'period_id', 'paymentschedule', 'paymentschedules_file'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $payment_schedule = Paymentschedule::where('id', $request->paymentschedule_id)->first();
        if($payment_schedule->status != 'borrador'){
            return redirect()->back()->with(['message' => 'La planilla ya ha sido generada.', 'alert-type' => 'error']);
        }

        DB::beginTransaction();
        try {
            // Obtener datos de la planilla actual
            $centralize_code = '';
            if($request->centralize){
                $centralize_code = $request->paymentschedule_id.'-c';
                $current_paymentschedule = Paymentschedule::find($request->paymentschedule_id);
                $paymentschedule = Paymentschedule::where('period_id', $current_paymentschedule->period_id)
                                        ->where('procedure_type_id', $current_paymentschedule->procedure_type_id)
                                        ->where('centralize', 1)->where('deleted_at', NULL)->first();
                if($paymentschedule){
                    $centralize_code = $paymentschedule->centralize_code ?? $request->paymentschedule_id.'-c';
                }
            }

            Paymentschedule::where('id', $request->paymentschedule_id)->update([
                'centralize' => $request->centralize,
                'centralize_code' => $centralize_code,
                'observations' => $request->observations,
                'status' => 'procesada',
                'user_id' => Auth::user()->id,
            ]);

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
                    'paymentschedule_id' => $request->paymentschedule_id,
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
        $data = Paymentschedule::with(['user', 'details.contract', 'direccion_administrativa', 'period', 'procedure_type'])
                    ->where('id', $id)->where('deleted_at', NULL)->first();
        return view('paymentschedules.read', compact('data'));
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
