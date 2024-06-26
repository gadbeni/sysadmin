<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;

// Models
use App\Models\Person;
use App\Models\Program;
use App\Models\Contract;
use App\Models\Direccion;
use App\Models\Unidad;
use App\Models\ProcedureType;
use App\Models\Cargo;
use App\Models\Job;
use App\Models\Signature;
use App\Models\ContractsHistory;
use App\Models\PaymentschedulesDetail;
use App\Models\ContractsFinished;
use App\Models\Addendum;
use App\Models\ContractRatification;
use App\Models\ContractsTransfer;
use App\Models\ContractsPromotion;
use App\Models\ContractsFile;
use App\Models\ContractAdditionalDiscount;

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
        $this->custom_authorize('browse_contracts');
        if(true){
            $date = date('Y-m-d');
            Contract::where('finish', '<', $date)->where('deleted_at', NULL)->where('status', 'firmado')->update(['status' => 'concluido']);
            Addendum::where('finish', '<', $date)->where('deleted_at', NULL)->where('status', 'firmado')->update(['status' => 'concluido']);
        }
        return view('management.contracts.browse');
    }

    public function list(){
        $this->custom_authorize('browse_contracts');
        $search = request('search') ?? null;
        $procedure_type_id = request('procedure_type_id') ?? null;
        $status = request('status') ?? null;
        $user_id = request('user_id') ?? null;
        $direccion_administrativa_id = request('direccion_administrativa_id') ?? null;
        $addendums = request('addendums') ?? null;
        $paginate = request('paginate') ?? 10;
        $data = Contract::with(['user', 'person', 'program', 'cargo.nivel', 'job.direccion_administrativa', 'direccion_administrativa.tipo', 'type', 'transfers', 'promotions', 'jobs', 'finished', 'addendums.program', 'addendums' => function($q){
                        $q->orderBy('id', 'DESC');            
                    }])
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
                                $query->whereRaw("(first_name like '%$search%' or last_name like '%$search%' or ci like '%$search%' or phone like '%$search%' or CONCAT(first_name, ' ', last_name) like '%$search%')");
                            })
                            // ->OrWhereHas('user', function($query) use($search){
                            //     $query->whereRaw("name like '%$search%'");
                            // })
                            ->OrWhereHas('direccion_administrativa', function($query) use($search){
                                $query->whereRaw($search ? 'nombre like "%'.$search.'%"' : 1);
                            })
                            ->OrWhereRaw($search ? "id = '$search'" : 1)
                            ->OrWhereRaw($search ? "code like '%$search%'" : 1)
                            ->OrWhereRaw($search ? "status like '%$search%'" : 1);
                        }
                    })
                    ->where(function($query) use ($addendums){
                        if($addendums){
                            $query->OrwhereHas('addendums', function($query){
                                $query->whereRaw("1");
                            });
                        }
                    })
                    ->where(function($query) use ($procedure_type_id){
                        if($procedure_type_id){
                            $query->whereRaw("procedure_type_id = $procedure_type_id");
                        }
                    })
                    ->where(function($query) use ($direccion_administrativa_id){
                        if($direccion_administrativa_id){
                            $query->whereRaw("direccion_administrativa_id = $direccion_administrativa_id");
                        }
                    })
                    ->where(function($query) use ($user_id){
                        if($user_id){
                            $query->whereRaw("user_id = $user_id");
                        }
                    })
                    ->where(function($query) use ($status){
                        if($status){
                            $query->whereRaw("status = '$status'");
                        }
                    })
                    ->whereRaw(Auth::user()->role_id == 25 || (Auth::user()->role_id >= 16 & Auth::user()->role_id <= 18) ? 'procedure_type_id = 2' : 1)
                    // Si el usuario pertenece a jurídico solo puede ver los contratos con estado enviado
                    ->whereRaw(Auth::user()->role_id >= 19 && Auth::user()->role_id <= 21 ? '(status = "enviado" or status = "firmado" or status = "concluido")' : 1)
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
        $this->custom_authorize('add_contracts');

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
        $procedure_type = ProcedureType::where('deleted_at', NULL)
                            ->whereRaw(Auth::user()->role_id == 16 || Auth::user()->role_id == 25 ? 'id = 2' : 1)
                            ->whereRaw($ids ? "id in ($ids,6)" : 1)->get();

        $people = Person::where('deleted_at', NULL)
                    ->whereRaw(!setting('auxiliares.enable_all_people_for_contract') ? "id not in (select person_id from contracts where status <> 'concluido' and deleted_at is null)" : 1)
                    ->get();
        $direccion_administrativas = Direccion::with(['signatures' => function($q){
                                            $q->where('status', 1)->where('deleted_at', NULL);
                                        }])->whereRaw($direccion_administrativa_id ? "id = $direccion_administrativa_id" : 1)
                                        ->where('estado', 1)->get();
        $unidad_administrativas = Unidad::get();
        $programs = Program::with(['direcciones_administrativas'])->where('year', date('Y'))->where('deleted_at', NULL)->get();
        $cargos = Cargo::with(['nivel' => function($q){
            $q->where('Estado', 1);
        }])->where('estado', 1)->get();
        $jobs = Job::with(['direccion_administrativa.signatures' => function($q){
                        $q->where('status', 1)->where('deleted_at', NULL);
                    }])
                    ->whereRaw("id not in (select job_id from contracts where job_id is not NULL and status <> 'concluido' and deleted_at is null)")
                    ->whereRaw(Auth::user()->direccion_administrativa_id ? 'direccion_administrativa_id = '.Auth::user()->direccion_administrativa_id : 1)
                    ->where('status', 1)->where('deleted_at', NULL)->get();
        return view('management.contracts.edit-add', compact('procedure_type', 'people', 'direccion_administrativas', 'unidad_administrativas', 'programs', 'cargos', 'jobs'));
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
        if($request->procedure_type_id != 1){
            if($request->start >= $request->finish){
                return redirect()->route('contracts.create')->with(['message' => 'La fecha de finalización debe ser mayor a la fecha de inicio', 'alert-type' => 'error']);
            }
            if(date('Y', strtotime($request->start)) != date('Y', strtotime($request->finish))){
                return redirect()->route('contracts.create')->with(['message' => 'El contrato no puede iniciar y finalizar en una gestón diferente', 'alert-type' => 'error']);
            }

            // Verificar que el contrato no inicie pisando un contrato previo
            $contract_person = Contract::where('person_id', $request->person_id)
                                ->where('finish', '>=', $request->start)->where('status', '<>', 'anulado')
                                ->where('deleted_at', NULL)->first();
            if($contract_person && !setting('auxiliares.enable_all_people_for_contract')){
                return redirect()->route('contracts.create')->with(['message' => 'El inicio de contrato coincide con el fin de otro contrato', 'alert-type' => 'warning']);
            }
        }
        try {

            // Si es un contrato permanente se el id_da se obtiene de la tabal jobs, sino se obtiene del request
            $direccion_administrativa_id = $request->procedure_type_id == 1 ? Job::find($request->cargo_id)->direccion_administrativa_id : $request->direccion_administrativa_id;

            // Verificar que no haya ningun proceso de contratación vigente
            $contract_person = Contract::where('person_id', $request->person_id)->where('status', '<>', 'concluido')->where('deleted_at', NULL)->first();
            if($contract_person && !setting('auxiliares.enable_all_people_for_contract')){
                return redirect()->route('contracts.index')->with(['message' => 'La persona seleccionada ya tiene un contrato activo o en proceso', 'alert-type' => 'warning']);
            }

            $last_contract = Contract::whereYear('start', date('Y', strtotime($request->start)))
                                ->where('procedure_type_id', $request->procedure_type_id)
                                ->where('direccion_administrativa_id', $direccion_administrativa_id)
                                ->orderBy('id', 'DESC')->first();
            $number_contract = $last_contract ? explode('/', explode('-', $last_contract->code)[1])[0] +1 : 1;
            $d_a = Direccion::find($direccion_administrativa_id);
            $code = $d_a->sigla.'-'.str_pad($number_contract, 2, "0", STR_PAD_LEFT).'/'.date('Y', strtotime($request->start));

            // Actualizar la AFP
            Person::where('id', $request->person_id)->update(['afp' => 3]);

            $contract = Contract::create([
                'person_id' => $request->person_id,
                'program_id' => $request->program_id,
                'current_program_id' => $request->program_id,
                // Si es un contrato de consultor o inversion
                'cargo_id' => $request->procedure_type_id != 1 ? $request->cargo_id : NULL,
                // Si es un contrato de permanente
                'job_id' => $request->procedure_type_id == 1 ? $request->cargo_id : NULL,
                'job_description' => $request->job_description,
                'salary' => $request->salary,
                'bonus' => $request->bonus,
                'job_location' => $request->job_location,
                'direccion_administrativa_id' => $direccion_administrativa_id,
                'unidad_administrativa_id' => $request->unidad_administrativa_id,
                'procedure_type_id' => $request->procedure_type_id,
                'user_id' => Auth::user()->id,
                'signature_id' => $request->signature_id,
                'signature_alt_id' => $request->signature_alt_id,
                'code' => $code,
                'details_work' => $request->details_work,
                'requested_by' => $request->requested_by,
                'start' => $request->start,
                'finish' => $request->finish,
                'date_invitation' => $request->date_invitation,
                'date_limit_invitation' => $request->date_limit_invitation,
                'date_response' => $request->date_response,
                'date_statement' => $request->date_statement,
                'date_memo' => $request->date_memo,
                'workers_memo_alt' => $request->workers_memo ? json_encode($request->workers_memo) : NULL,
                'date_memo_res' => $request->date_memo_res,
                'date_note' => $request->date_note,
                'date_report' => $request->date_report,
                'table_report' => $request->table_report,
                'details_report' => $request->details_report,
                'date_autorization' => $request->date_autorization,
                'certification_poa' => $request->certification_poa,
                'certification_pac' => $request->certification_pac,
                'date_presentation' => $request->date_presentation,
                'name_job_alt' => $request->name_job_alt,
                'work_location' => $request->work_location,
                'documents_contract' => $request->documents_contract,
                'status' => $request->procedure_type_id == 6 ? 'firmado' : 'elaborado',
            ]);

            return redirect()->route('contracts.index')->with(['message' => 'Contrato guardado exitosamente', 'alert-type' => 'success']);
        } catch (\Throwable $th) {
            // throw $th;
            return redirect()->route('contracts.index')->with(['message' => 'Ocurrió un error', 'alert-type' => 'error']);
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
        $this->custom_authorize('read_contracts');
        $contract = Contract::find($id);
        return view('management.contracts.read', compact('contract'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $this->custom_authorize('edit_contracts');
        $contract = Contract::with(['user', 'person', 'program'])->where('id', $id)->first();

        // Evitar que editen un contrato ingresando por URL
        if($contract->status != 'elaborado' && $contract->status != 'enviado' && auth()->user()->role_id != 1){
            abort(403, 'THIS ACTION IS UNAUTHORIZED.');
        }

        $role_id = Auth::user()->role_id;
        $direccion_administrativa_id = Auth::user()->direccion_administrativa_id;
        $procedure_type = ProcedureType::where('deleted_at', NULL)->where('id', $contract->procedure_type_id)->get();

        $people = Person::where('id', $contract->person->id)->where('deleted_at', NULL)->get();
        $direccion_administrativas = Direccion::with(['signatures' => function($q){
                                        $q->where('status', 1)->where('deleted_at', NULL);
                                    }])->whereRaw(Auth::user()->direccion_administrativa_id ? "id = ".Auth::user()->direccion_administrativa_id : 1)->get();
        $unidad_administrativas = Unidad::get();
        $programs = Program::with(['direcciones_administrativas'])->where('year', date('Y'))->where('deleted_at', NULL)->get();
        $cargos = Cargo::with(['nivel' => function($q){
                        $q->where('Estado', 1);
                    }])->where('estado', 1)->get();
        
        $jobs = Job::with(['direccion_administrativa.signatures' => function($q){
                        $q->where('status', 1)->where('deleted_at', NULL);
                    }])
                    ->whereRaw("id not in (select job_id from contracts where job_id is not NULL and status <> 'concluido' and deleted_at is null) or id = ".($contract->job_id ?? 0))
                    ->whereRaw(Auth::user()->direccion_administrativa_id ? 'direccion_administrativa_id = '.Auth::user()->direccion_administrativa_id : 1)
                    ->where('deleted_at', NULL)->get();
        return view('management.contracts.edit-add', compact('contract', 'procedure_type', 'people', 'direccion_administrativas', 'unidad_administrativas', 'programs', 'cargos', 'jobs'));
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

            // Si es un contrato permanente se el id_da se obtiene de la tabal jobs, sino se obtiene del request
            $direccion_administrativa_id = $request->procedure_type_id == 1 ? Job::find($request->cargo_id)->direccion_administrativa_id : $request->direccion_administrativa_id;

            $contract = Contract::find($id);
            // Si se cambia el año de inicio o la dirección administrativa se debe actualizar el código de contrato
            if(date('Y', strtotime($contract->start)) != date('Y', strtotime($request->start)) || $contract->direccion_administrativa_id != $direccion_administrativa_id){
                $last_contract = Contract::whereYear('start', date('Y', strtotime($request->start)))
                                    ->where('procedure_type_id', $request->procedure_type_id)
                                    ->where('direccion_administrativa_id', $direccion_administrativa_id)
                                    ->orderBy('id', 'DESC')->first();
                $number_contract = $last_contract ? explode('/', explode('-', $last_contract->code)[1])[0] +1 : 1;
                $d_a = Direccion::find($direccion_administrativa_id);
                $code = $d_a->sigla.'-'.str_pad($number_contract, 2, "0", STR_PAD_LEFT).'/'.date('Y', strtotime($request->start));
            }else{
                $code = $contract->code;
            }

            Contract::where('id', $id)->update([
                'person_id' => $request->person_id,
                'program_id' => $request->program_id,
                'current_program_id' => $request->program_id,
                // Si es un contrato de consultor o inversion
                'cargo_id' => $request->procedure_type_id != 1 ? $request->cargo_id : NULL,
                // Si es un contrato de permanente
                'job_id' => $request->procedure_type_id == 1 ? $request->cargo_id : NULL,
                'job_description' => $request->job_description,
                'salary' => $request->salary,
                'direccion_administrativa_id' => $direccion_administrativa_id,
                'unidad_administrativa_id' => $request->unidad_administrativa_id,
                'signature_id' => $request->signature_id,
                'signature_alt_id' => $request->signature_alt_id,
                'code' => $code,
                'details_work' => $request->details_work,
                'requested_by' => $request->requested_by,
                'start' => $request->start,
                'finish' => $request->finish,
                'date_invitation' => $request->date_invitation,
                'date_limit_invitation' => $request->date_limit_invitation,
                'date_response' => $request->date_response,
                'date_statement' => $request->date_statement,
                'date_memo' => $request->date_memo,
                'workers_memo_alt' => $request->workers_memo ? json_encode($request->workers_memo) : $contract->workers_memo_alt,
                'date_memo_res' => $request->date_memo_res,
                'date_note' => $request->date_note,
                'date_report' => $request->date_report,
                'table_report' => $request->table_report,
                'details_report' => $request->details_report,
                'date_autorization' => $request->date_autorization,
                'certification_poa' => $request->certification_poa,
                'certification_pac' => $request->certification_pac,
                'date_presentation' => $request->date_presentation,
                'name_job_alt' => $request->name_job_alt,
                'work_location' => $request->work_location,
                'documents_contract' => $request->documents_contract,
            ]);

            return redirect()->route('contracts.index')->with(['message' => 'Contrato editado exitosamente', 'alert-type' => 'success']);
        } catch (\Throwable $th) {
            // throw $th;
            return redirect()->route('contracts.index')->with(['message' => 'Ocurrió un error', 'alert-type' => 'error']);
        }
    }

    public function contracts_status(Request $request){
        DB::beginTransaction();
        try {
            // Si es una resolución de contrato
            if ($request->finish) {
                // Obtener el último pago realizado
                $payment = PaymentschedulesDetail::with(['paymentschedule.period'])->where('contract_id', $request->id)
                            ->where('deleted_at', NULL)->get()->sortByDesc('paymentschedule.period.name')->first();
                if($payment){
                    // Si el mes es igual al inicio de contrato se debe tomar como referencia en 30 del mes
                    if(date('Ym', strtotime($request->finish)) == date('Ym', strtotime($payment->contract->start))){
                        // 20301"30"
                        $last_payment = $payment->paymentschedule->period->name.'30';
                    }else{
                        // 20301"días trabajados"
                        $last_payment = $payment->paymentschedule->period->name.str_pad($payment->worked_days, 2, "0", STR_PAD_LEFT);
                    }

                    // Si el último pago es mayor a la fecha de finalización que se envió mostrar error
                    if($last_payment > date('Ymd', strtotime($request->finish))){
                        return response()->json(['error' => 1, 'message' => 'El contrato pertenece a una planilla']);
                    }

                    // Si el usuario no es administrador y el contrato ya se planilló
                    if($payment->contract->procedure_type_id == 2 && Auth::user()->role_id > 1){
                        // Si el contarto es de consultoría que solo se puede concluir con fecha igual o mayor al periodo actual
                        if (date('Ym', strtotime($request->finish)) < date('Ym')) {
                            return response()->json(['error' => 1, 'message' => 'La fecha de finalización es previa al perido actual']);
                        }
                    }
                }

                // ! Validar que no se haya planillado el aguinaldo
            }

            // Si se finaliza el contrato
            $contract = Contract::findOrFail($request->id);
            $contract->status = $request->status;
            $previus_date = $contract->finish;
            if ($request->finish) {
                if ($contract->start >= $request->finish) {
                    return response()->json(['error' => 1, 'message' => 'Error al definir la fecha de finalización']);
                }
                $contract->finish = $request->finish;
            }

            $contract->update();

            ContractsHistory::create([
                'contract_id' => $request->id,
                'user_id' => Auth::user()->id,
                'status' => $request->status,
                'observations' => $request->observations,
            ]);

            if($request->status == 'concluido'){
                $last_contract = ContractsFinished::whereHas('contract', function($q) use($contract){
                                        $q->where('direccion_administrativa_id', $contract->direccion_administrativa_id)
                                        ->where('procedure_type_id', $contract->procedure_type_id)
                                        ->whereRaw('YEAR(finish) = "'.date('Y', strtotime($contract->finish)).'"');
                                    })
                                    ->where('deleted_at', NULL)->where('code', '<>', NULL)->orderBy('id', 'DESC')->first();
                $last_code = $last_contract ? str_replace("A-", "", explode('/', $last_contract->code)[0]) : 0;
                ContractsFinished::create([
                    'contract_id' => $request->id,
                    'user_id' => Auth::user()->id,
                    'code' => 'A-'.str_pad($last_code +1, 3, "0", STR_PAD_LEFT).'/'.date('Y', strtotime($request->finish)),
                    'previus_date' => $previus_date,
                    'technical_report' => $request->technical_report,
                    'nci' => $request->nci,
                    'legal_report' => $request->legal_report,
                    'observations' => $request->observations,
                    'details' => $request->details
                ]);
            }

            DB::commit();
            return response()->json(['success'=> 1, 'message' => 'Cambio realizo exitosamente']);
        } catch (\Throwable $th) {
            DB::rollback();
            // dd($th);
            return response()->json(['error' => 1, 'message' => 'Ocurrió un error']);
        }
    }

    public function contracts_resolution_update(Request $request){
        try {
            $contract =  Contract::find($request->id);
            $contract->finished->technical_report = $request->technical_report;
            $contract->finished->nci = $request->nci;
            $contract->finished->legal_report = $request->legal_report;
            $contract->finished->details = $request->details;
            $contract->finished->update();
            return response()->json(['success'=> 1, 'message' => 'Edición exitosa']);
        } catch (\Throwable $th) {
            //throw $th;
            return response()->json(['error' => 1, 'message' => 'Ocurrió un error']);
        }
    }

    public function contracts_ratificate(Request $request){
        // dd($request->all());
        try {
            ContractRatification::create([
                'contract_id' => $request->id,
                'user_id' => Auth::user()->id,
                'date' => $request->date,
                'observations' => $request->observations,
            ]);
            return response()->json(['success'=> 1, 'message' => 'Ratificación exitosa']);
        } catch (\Throwable $th) {
            // throw $th;
            return response()->json(['error' => 1, 'message' => 'Ocurrió un error']);
        }
    }

    public function contracts_addendum_store(Request $request){
        DB::beginTransaction();
        try {
            // Solo para el SEDEGES ya es una DA que genera sus adendas propias
            $contract = Contract::find($request->id);
            if($contract->direccion_administrativa->direcciones_tipo_id == 5){
                $count_addendum = Addendum::whereHas('contract', function($q) use($contract){
                                    $q->where('direccion_administrativa_id', $contract->direccion_administrativa_id);
                                })->whereYear('start', date('Y'))->withTrashed()->count() +1;
            }else{
                $count_addendum = Addendum::whereHas('contract.direccion_administrativa', function($q) use($contract){
                                    $q->where('direcciones_tipo_id', '<>', 5);
                                })->whereYear('start', date('Y'))->withTrashed()->count() +1;
            }

            // dd($count_addendum);
            
            Addendum::create([
                'contract_id' => $request->id,
                'signature_id' => $request->signature_id ?? NULL,
                'user_id' => Auth::user()->id,
                'code' => str_pad($count_addendum.'/'.date('Y', strtotime($request->start)), 8, "0", STR_PAD_LEFT),
                'start' => $request->start,
                'finish' => $request->finish,
                'signature_date' => $request->signature_date,
                'applicant_id' => $request->applicant_id,
                'nci_date' => $request->nci_date,
                'nci_code' => $request->nci_code,
                'certification_date' => $request->certification_date,
                'certification_code' => $request->certification_code,
                'request_date' => $request->request_date,
                'legal_report_date' => $request->legal_report_date
            ]);

            DB::commit();
            return response()->json(['success'=> 1, 'message' => 'Adenda registrada exitosamente']);
        } catch (\Throwable $th) {
            DB::rollback();
            return response()->json(['error' => 1, 'message' => 'Ocurrió un error']);
        }
    }

    public function contracts_addendum_status(Request $request){
        DB::beginTransaction();
        try {

            $addendum = Addendum::find($request->id);
            $addendum->status = 'firmado';
            $addendum->update();

            $contract = Contract::findOrFail($addendum->contract_id);
            $contract->finish = $addendum->finish;
            $contract->status = 'firmado';
            $contract->update();

            DB::commit();
            return response()->json(['success'=> 1, 'message' => 'Adenda firmada exitosamente']);
        } catch (\Throwable $th) {
            DB::rollback();
            return response()->json(['error' => 1, 'message' => 'Ocurrió un error']);
        }
    }

    public function contracts_addendum_update(Request $request){
        DB::beginTransaction();
        try {
            $addendum = Addendum::find($request->id);
            $addendum->signature_id = $request->signature_id ?? NULL;
            $addendum->program_id = $request->program_id ?? NULL;
            $addendum->applicant_id = $request->applicant_id ? $request->applicant_id : $addendum->applicant_id;
            $addendum->finish = $request->finish;
            $addendum->signature_date = $request->signature_date;
            $addendum->update();

            if($addendum->status == 'firmado'){
                $contract = Contract::findOrFail($addendum->contract_id);
                $contract->finish = $request->finish;
                if ($request->program_id) {
                    $contract->current_program_id = $request->program_id;
                }
                $contract->status = 'firmado';
                $contract->update();
            }

            DB::commit();
            return redirect()->route('contracts.show', $request->contract_id)->with(['message' => 'Adenda actualizada', 'alert-type' => 'success']);
        } catch (\Throwable $th) {
            DB::rollback();
            // dd($th);
            return redirect()->route('contracts.show', $request->contract_id)->with(['message' => 'Ocurrió un error', 'alert-type' => 'error']);
        }
    }

    public function contracts_addendum_delete(Request $request){
        DB::beginTransaction();
        try {
            $addendum = Addendum::find($request->id);

            // Si la adenda está firmada
            if ($addendum->status == 'firmado') {
                $contract = Contract::findOrFail($addendum->contract_id);
                // Obtener la fecha anterior del inicio de la adenda
                $finish = date('Y-m-d', strtotime($addendum->start.' -1 days'));
                $contract->finish = $finish;
                $contract->status = 'firmado';
                $contract->update();
            }

            $addendum->delete();

            DB::commit();
            return redirect()->route('contracts.show', $request->contract_id)->with(['message' => 'Adenda actualizada', 'alert-type' => 'success']);
        } catch (\Throwable $th) {
            DB::rollback();
            return redirect()->route('contracts.show', $request->contract_id)->with(['message' => 'Ocurrió un error', 'alert-type' => 'error']);
        }
    }

    public function contracts_transfer_store(Request $request){
        DB::beginTransaction();
        try {

            $contract = Contract::find($request->contract_id);

            if ($contract->start >= $request->date) {
                return response()->json(['error' => 1, 'message' => 'Error al definir la fecha de trasferencia']);
            }

            // Crear contrato
            $d_a = Direccion::find(Job::find($request->job_id)->direccion_administrativa_id);
            $last_contract = Contract::whereYear('start', date('Y', strtotime($request->date)))
                                    ->where('procedure_type_id', $contract->procedure_type_id)
                                    ->where('direccion_administrativa_id', $d_a->id)
                                    ->orderBy('id', 'DESC')->first();
            $number_contract = $last_contract ? explode('/', explode('-', $last_contract->code)[1])[0] +1 : 1;

            $program = Program::where('procedure_type_id', 1)->where('year', date('Y'))->where('deleted_at', NULL)->first();
            if(!$program){
                return response()->json(['error'=> 1, 'message' => 'No existe un programa para la planilla permanente']);
            }
            
            $code = $d_a->sigla.'-'.str_pad($number_contract, 2, "0", STR_PAD_LEFT).'/'.date('Y', strtotime($request->date));
            $new_contratc = Contract::create([
                'user_id' => Auth::user()->id,
                'person_id' => $contract->person_id,
                'procedure_type_id' => $contract->procedure_type_id,
                'program_id' => $program->id,
                'current_program_id' => $program->id,
                'job_id' => $request->job_id,
                'direccion_administrativa_id' => $d_a->id,
                'code' => $code,
                'start' => $request->date,
                'finish' => $contract->finish,
                'details_work' => $contract->details_work,
                'table_report' => $contract->table_report,
            ]);

            // Actualizar el contrato previo
            $contract->finish = date("Y-m-d", strtotime("-1 day", strtotime($request->date)));
            $contract->status = 'concluido';
            $contract->update();

            // Crear registro de transferencia
            $last_transfer = ContractsTransfer::whereYear('date', date('Y'))->where('deleted_at', NULL)->orderBy('id', 'DESC')->first();
            ContractsTransfer::create([
                'user_id' => Auth::user()->id,
                'contract_id' => $new_contratc->id,
                'previus_contract_id' => $request->contract_id,
                'code' => ($last_transfer ? explode('/', $last_transfer->code)[0] +1 : 1).'/'.date('Y'),
                'date' => $request->date,
                'observations' => $request->contract_id,
            ]);
            DB::commit();
            return response()->json(['success'=> 1, 'message' => 'Transferencia registrada exitosamente']);
        } catch (\Throwable $th) {
            DB::rollback();
            // dd($th);
            return response()->json(['error' => 1, 'message' => 'Ocurrió un error']);
        }
    }

    public function contracts_promotion_store(Request $request){
        DB::beginTransaction();
        try {

            $contract = Contract::find($request->contract_id);

            if ($contract->start >= $request->date) {
                return response()->json(['error' => 1, 'message' => 'Error al definir la fecha de trasferencia']);
            }

            // Crear contrato
            $d_a = Direccion::find(Job::find($request->job_id)->direccion_administrativa_id);
            $last_contract = Contract::whereYear('start', date('Y', strtotime($request->date)))
                                    ->where('procedure_type_id', $contract->procedure_type_id)
                                    ->where('direccion_administrativa_id', $d_a->id)
                                    ->orderBy('id', 'DESC')->first();
            $number_contract = $last_contract ? explode('/', explode('-', $last_contract->code)[1])[0] +1 : 1;

            $program = Program::where('procedure_type_id', 1)->where('year', date('Y'))->where('deleted_at', NULL)->first();
            if(!$program){
                return response()->json(['error'=> 1, 'message' => 'No existe un programa para la planilla permanente']);
            }
            
            $code = $d_a->sigla.'-'.str_pad($number_contract, 2, "0", STR_PAD_LEFT).'/'.date('Y', strtotime($request->date));
            $new_contratc = Contract::create([
                'user_id' => Auth::user()->id,
                'person_id' => $contract->person_id,
                'procedure_type_id' => $contract->procedure_type_id,
                'program_id' => $program->id,
                'current_program_id' => $program->id,
                'job_id' => $request->job_id,
                'direccion_administrativa_id' => $d_a->id,
                'code' => $code,
                'start' => $request->date,
                'finish' => $contract->finish,
                'details_work' => $contract->details_work,
                'table_report' => $contract->table_report,
            ]);

            // Actualizar el contrato previo
            $contract->finish = date("Y-m-d", strtotime("-1 day", strtotime($request->date)));
            $contract->status = 'concluido';
            $contract->update();

            // Crear registro de promoci|ón
            $last_promotion = ContractsPromotion::whereYear('date', date('Y'))->where('deleted_at', NULL)->orderBy('id', 'DESC')->first();
            ContractsPromotion::create([
                'user_id' => Auth::user()->id,
                'contract_id' => $new_contratc->id,
                'previus_contract_id' => $request->contract_id,
                'code' => ($last_promotion ? explode('/', $last_promotion->code)[0] +1 : 1).'/'.date('Y'),
                'date' => $request->date,
                'observations' => $request->contract_id,
            ]);
            DB::commit();
            return response()->json(['success'=> 1, 'message' => 'Promoción registrada exitosamente']);
        } catch (\Throwable $th) {
            DB::rollback();
            // dd($th);
            return response()->json(['error' => 1, 'message' => 'Ocurrió un error']);
        }
    }

    public function contracts_reassignment_store(Request $request){
        DB::beginTransaction();
        try {
            // Verificar que el cargo esté acéfalo
            $contract = Contract::where('job_id', $request->job_id)->where('status', '<>', 'concluido')->where('deleted_at', null)->first();
            if($contract){
                return response()->json(['error' => 1, 'message' => 'El cargo seleccionado está asignado en otro contrato']);
            }

            // Concluir contrato actual
            $contract = Contract::find($request->id);
            $contract->finish = date('Y-m-d', strtotime($request->date.' -1 days'));
            $contract->status = 'concluido'; 
            $contract->update();

            // Nuevo contrato
            $d_a = Direccion::find(Job::find($request->job_id)->direccion_administrativa_id);
            $last_contract = Contract::whereYear('start', date('Y', strtotime($request->date)))
                                    ->where('procedure_type_id', $contract->procedure_type_id)
                                    ->where('direccion_administrativa_id', $d_a->id)
                                    ->orderBy('id', 'DESC')->first();
            $number_contract = $last_contract ? explode('/', explode('-', $last_contract->code)[1])[0] +1 : 1;

            $program = Program::where('procedure_type_id', 1)->where('year', date('Y'))->where('deleted_at', NULL)->first();
            if(!$program){
                return response()->json(['error'=> 1, 'message' => 'No existe un programa para la planilla permanente en la gestión '.date('Y', strtotime($request->date))]);
            }
            
            $code = $d_a->sigla.'-'.str_pad($number_contract, 2, "0", STR_PAD_LEFT).'/'.date('Y', strtotime($request->date));
            $new_contratc = Contract::create([
                'user_id' => Auth::user()->id,
                'person_id' => $contract->person_id,
                'procedure_type_id' => $contract->procedure_type_id,
                'program_id' => $program->id,
                'current_program_id' => $program->id,
                'job_id' => $request->job_id,
                'direccion_administrativa_id' => $d_a->id,
                'code' => $code,
                'start' => $request->date,
                'signature_date' => $request->signature_date,
                'details_work' => $contract->details_work,
                'table_report' => $contract->table_report,
            ]);

            ContractsHistory::create([
                'contract_id' => $request->id,
                'user_id' => Auth::user()->id,
                'status' => 'Reasignado',
                'observations' => $request->observations,
            ]);

            DB::commit();
            return response()->json(['success'=> 1, 'message' => 'Reasignación de cargo registrada exitosamente']);
        } catch (\Throwable $th) {
            DB::rollback();
            //throw $th;
            return response()->json(['error' => 1, 'message' => 'Ocurrió un error']);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id, Request $request)
    {
        // dd($request->all());
        DB::beginTransaction();
        try {
            $payments = PaymentschedulesDetail::where('contract_id', $id)->where('deleted_at', NULL)->first();
            if(!$payments){
                Contract::where('id', $id)->update([
                    'status' => 'anulado',
                    'deleted_at' => Carbon::now()
                ]);

                ContractsHistory::create([
                    'contract_id' => $id,
                    'user_id' => Auth::user()->id,
                    'status' => 'Anulado',
                    'observations' => $request->observations,
                ]);

                DB::commit();

                return response()->json(['success'=> 1, 'message' => 'Anulado exitosamente']);
            }else{
                return response()->json(['error' => 1, 'message' => 'El contrato ya fue planillado, no se puede eliminar']);
            }
        } catch (\Throwable $th) {
            DB::rollback();
            //throw $th;
            return response()->json(['error' => 1, 'message' => 'Ocurrió un error']);
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
                            ->whereRaw(Auth::user()->role_id == 16 || Auth::user()->role_id == 25 ? 'id = 2' : 1)
                            ->where('deleted_at', NULL)->get();
        return response()->json($tipo_planilla);
    }

    // ================================
    
    public function print($id, $document) {
        $contract = Contract::with(['user', 'person', 'program', 'finished', 'cargo.nivel', 'direccion_administrativa', 'job.direccion_administrativa', 'unidad_administrativa', 'signature', 'signature_alt', 'addendums.signature', 'transfers', 'promotions', 'files' => function($q) use($document){
                        $q->where('name', $document);
                    }])->where('id', $id)->first();
        // Si no tiene comisión evaluadora del sistema actual buscar en el antiguo sistema
        if($contract->workers_memo_alt != null){
            $contract->workers = Contract::with(['person', 'job', 'cargo', 'alternate_job' => function($q){
                                        $q->where('status', 1)->where('deleted_at', NULL);
                                    }])
                                    ->whereIn('id', json_decode($contract->workers_memo_alt))->get();
        }else{
            $contract->workers = $contract->workers_memo != null && $contract->workers_memo != "null" ? DB::connection('mysqlgobe')->table('contribuyente')->whereIn('ID', json_decode($contract->workers_memo))->get() : [];
        }
        return view('management.docs.'.$document, compact('contract', 'document'));
    }

    public function file_store($id, Request $request) {
        try {
            ContractsFile::create([
                'contract_id' => $id,
                'user_id' => Auth::user()->id,
                'name' => $request->name,
                'text' => $request->text
            ]);
            return redirect()->route('contracts.print', ['id' => $id, 'document' => $request->name])->with(['message' => 'Contrato editado exitosamente', 'alert-type' => 'success']);
        } catch (\Throwable $th) {
            return redirect()->route('contracts.print', ['id' => $id, 'document' => $request->name])->with(['message' => 'Ocurrió un error', 'alert-type' => 'error']);
        }
    }

    public function contracts_additional_discount_store(Request $request) {
        try {
            ContractAdditionalDiscount::create([
                'user_id' => Auth::user()->id,
                'contract_id' => $request->contract_id,
                'type' => $request->type,
                'amount' => $request->amount,
                'amount_total' => $request->type == 1 ? $request->amount : $request->salary_amount * ($request->amount /100),
                'reason' => $request->reason,
                'details' => $request->details,
            ]);
            return response()->json(['success'=> 1, 'message' => 'Descuento adicional registrado exitosamente']);
        } catch (\Throwable $th) {
            return response()->json(['error' => 1, 'message' => 'Ocurrió un error']);
        }
    }

    public function contracts_additional_discount_delete($id, Request $request) {
        $discount = ContractAdditionalDiscount::find($id);
        $discount->delete();
        return redirect()->to($_SERVER['HTTP_REFERER'])->with(['message' => 'Descuento adicional anulado', 'alert-type' => 'success']);
    }

    public function file_destroy($id, Request $request){
        try {
            $file = ContractsFile::find($id);
            $contract_id = $file->contract_id;
            $file->delete();
            return redirect()->route('contracts.show', $contract_id)->with(['message' => 'Archivo eliminado', 'alert-type' => 'success']);
        } catch (\Throwable $th) {
            return redirect()->route('contracts.index')->with(['message' => 'Ocurrió un error', 'alert-type' => 'error']);
        }
    }

    public function finished_destroy($id, Request $request){
        DB::beginTransaction();
        try {
            $contract_finish = ContractsFinished::where('contract_id', $id)->orderBy('id', 'DESC')->first();
            $contract = Contract::findOrFail($id);
            
            // Si la persona tiene un contrato activo no se debe restaurar
            if(Contract::where('person_id', $contract->person_id)->whereRaw('(status = "elaborado" or status = "enviado" or status = "firmado")')->where('deleted_at', NULL)->first()){
                return response()->json(['error' => 1, 'message' => 'La persona ya tiene un contrato firmado o en proceso de elaboración']);
            }
            
            $contract->status = 'firmado';
            $contract->finish = $contract_finish->previus_date ?? null;
            $contract->update();

            $contract_finish->delete();
            
            DB::commit();
            return response()->json(['success'=> 1, 'message' => 'Cambio realizo exitosamente']);
        } catch (\Throwable $th) {
            DB::rollback();
            // dd($th);
            return response()->json(['error' => 1, 'message' => 'Ocurrió un error']);
        }
    }

    public function search(){
        $search = request('search');
        $contracts = Contract::with('person', 'cargo', 'job', 'direccion_administrativa', 'unidad_administrativa')->where('status', 'firmado')
                        ->where(function($query) use ($search){
                            if($search){
                                $query->OrwhereHas('person', function($query) use($search){
                                    $query->whereRaw("(first_name like '%$search%' or last_name like '%$search%' or ci like '%$search%' or phone like '%$search%' or CONCAT(first_name, ' ', last_name) like '%$search%')");
                                });
                            }
                        })
                        ->where('deleted_at', NULL)->get();
        return response()->json($contracts);
    }

    // **** Métodos funcionales ****
}