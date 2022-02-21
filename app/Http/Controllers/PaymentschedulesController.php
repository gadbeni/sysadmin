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
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('paymentschedules.browse');
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
                            $q->where('deleted_at', NULL)->where('status', 1)->orderBy('id', 'DESC')->first();
                        }, 'program', 'cargo.nivel' => function($q){
                            $q->where('Estado', 1);
                        }, 'job.direccion_administrativa', 'direccion_administrativa', 'type'])
                        ->where('direccion_administrativa_id', $request->da_id)
                        ->where('procedure_type_id', $request->procedure_type_id)
                        ->where('status', 1)
                        ->where('deleted_at', NULL)->get();
            
        $paymentschedules_file = PaymentschedulesFile::with(['details'])
                                    ->where('direccion_administrativa_id', $request->da_id)
                                    ->where('period_id', $request->period_id)
                                    ->where('procedure_type_id', $request->procedure_type_id)
                                    ->where('status', 2)->get();

        $paymentschedule = Paymentschedule::firstOrCreate([
            'direccion_administrativa_id' =>  $request->da_id,
            'period_id' => $request->period_id,
            'procedure_type_id' => $request->procedure_type_id,
            'deleted_at' => NULL,
        ]);

        if($paymentschedule->status == NULL){
            $paymentschedule->status = 1;
            $paymentschedule->save();
        }

        return view('paymentschedules.generate', compact('contracts', 'period_id', 'paymentschedule', 'paymentschedules_file'));
    }

    public function files_index()
    {
        return view('paymentschedules.files-browse');
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
                'status' => 2
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
                'status' => 2,
                'user_id' => Auth::user()->id,
            ]);

            $contract_id = json_decode($request->contract_id);
            $worked_days = json_decode($request->worked_days);
            $job = json_decode($request->job);
            $job_level = json_decode($request->job_level);
            $salary = json_decode($request->salary);
            $seniority_bonus_percentage = json_decode($request->seniority_bonus_percentage);
            $solidary = json_decode($request->solidary);
            $common_risk = json_decode($request->common_risk);
            $afp_commission = json_decode($request->afp_commission);
            $retirement = json_decode($request->retirement);
            $solidary_national = json_decode($request->solidary_national);
            $solidary_employer = json_decode($request->solidary_employer);
            $housing_employer = json_decode($request->housing_employer);
            $health = json_decode($request->health);
            $rc_iva_amount = json_decode($request->rc_iva_amount);
            $faults_quantity = json_decode($request->faults_quantity);
            
            for ($i=0; $i < count($contract_id); $i++) {
                PaymentschedulesDetail::create([
                    'paymentschedule_id' => $request->paymentschedule_id,
                    'contract_id' => $contract_id[$i],
                    'worked_days' => $worked_days[$i],
                    'job' => $job[$i],
                    'job_level' => $job_level[$i],
                    'salary' => $salary[$i],
                    'seniority_bonus_percentage' => $seniority_bonus_percentage[$i],
                    'solidary' => $solidary[$i],
                    'common_risk' => $common_risk[$i],
                    'afp_commission' => $afp_commission[$i],
                    'retirement' => $retirement[$i],
                    'solidary_national' => $solidary_national[$i],
                    'solidary_employer' => $solidary_employer[$i],
                    'housing_employer' => $housing_employer[$i],
                    'health' => $health[$i],
                    'rc_iva_amount' => $rc_iva_amount[$i],
                    'faults_quantity' => $faults_quantity[$i],
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
