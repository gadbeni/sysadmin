<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use Storage;

use App\Models\Person;
use App\Models\PersonRotation;
use App\Models\Contract;
use App\Models\PersonIrremovability;
use App\Models\PersonFile;
use App\Models\PersonAsset;
use App\Models\PersonAssetDetail;
use App\Models\PersonAssetSignature;
use App\Models\ContractScheduleRegisterEdit;
use App\Models\Holiday;

class PeopleController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(){
        $date = date('Y-m-d');
        Contract::where('finish', '<', $date)->where('deleted_at', NULL)->where('status', 'firmado')->update(['status' => 'concluido']);
        return view('management.people.browse');
    }

    public function list($search = null){
        $paginate = request('paginate') ?? 10;
        $data = Person::with(['city', 'afp_type', 'contracts', 'schedules.schedule', 'irremovabilities' => function($q){
                        $q->where('start', '<=', date('Y-m-d'))->whereRaw("(finish is NULL or finish >= '".date('Y-m-d')."')");
                    }, 'assignments.details' => function($q){
                        $q->where('active', 1);
                    }])
                    ->where(function($query) use ($search){
                        if($search){
                            $query->OrwhereHas('city', function($query) use($search){
                                $query->whereRaw("name like '%$search%'");
                            })
                            ->OrWhereRaw($search ? "id = '$search'" : 1)
                            ->OrWhereRaw($search ? "first_name like '%$search%'" : 1)
                            ->OrWhereRaw($search ? "last_name like '%$search%'" : 1)
                            ->OrWhereRaw($search ? "CONCAT(first_name, ' ', last_name) like '%$search%'" : 1)
                            ->OrWhereRaw($search ? "ci like '%$search%'" : 1)
                            ->OrWhereRaw($search ? "nua_cua like '%$search%'" : 1)
                            ->OrWhereRaw($search ? "phone like '%$search%'" : 1);
                        }
                    })
                    ->where(function($query){
                        if(Auth::user()->direccion_administrativa_id){
                            $query->OrwhereHas('contracts', function($query){
                                $query->whereRaw("direccion_administrativa_id = ".Auth::user()->direccion_administrativa_id);
                            })
                            ->OrWhereRaw("user_id = ".Auth::user()->id);
                        }
                    })
                    ->where('deleted_at', NULL)->orderBy('id', 'DESC')->paginate($paginate);
        return view('management.people.list', compact('data'));
    }

    public function search($type = null){
        switch ($type) {
            case 'schedules':
                $direcciones_tipo_id = request('direcciones_tipo_id') ?? null;
                $direccion_administrativa_id = request('direccion_administrativa_id') ?? null;
                $contracts = Contract::with(['person'])
                                ->whereHas('direccion_administrativa.tipo', function($q) use($direcciones_tipo_id){
                                    $q->whereRaw($direcciones_tipo_id ? "id = $direcciones_tipo_id" : 1);
                                })
                                ->whereHas('direccion_administrativa', function($q) use($direccion_administrativa_id){
                                    $q->whereRaw($direccion_administrativa_id ? "id = $direccion_administrativa_id" : 1);
                                })
                                ->withCount('schedules')->where('status', 'firmado')->get()->where('schedules_count', 0);
                return view('schedules.partials.people-list', compact('contracts'));
                break;
            
            default:
                $search = request('search');
                $people = Person::whereRaw("(first_name like '%$search%' or last_name like '%$search%' or ci like '%$search%' or phone like '%$search%' or CONCAT(first_name, ' ', last_name) like '%$search%')")->where('deleted_at', NULL)->get();
                return response()->json($people);
                break;
        }
    }

    public function read($id){
        $person = Person::with(['contracts.rotations', 'irremovabilities.type', 'assignments.details.asset'])->where('id', $id)->first();
        return view('management.people.read', compact('person'));
    }

    public function rotation_store($id, Request $request){
        try {
            $person = Person::with(['contracts' => function($q){
                    $q->where('status', 'firmado')->where('deleted_at', NULL);
                }])
                ->where('id', $id)->whereHas('contracts', function($query){
                    $query->where('status', 'firmado')->where('deleted_at', NULL);
                })->where('deleted_at', NULL)->first();
            if(!$person){
                return response()->json(['error' => 1, 'message' => 'El funcionario no tiene un contrato vigente']);
            }

            $destiny = Contract::where('person_id', $request->destiny_id)->where('status', 'firmado')->where('deleted_at', NULL)->first();
            if(!$destiny){
                return response()->json(['error' => 1, 'message' => 'El funcionario solicitante no tiene un contrato vigente']);
            }
            $responsible = Contract::where('person_id', $request->responsible_id)->where('status', 'firmado')->where('deleted_at', NULL)->first();
            if(!$responsible){
                return response()->json(['error' => 1, 'message' => 'El funcionario responsable no tiene un contrato vigente']);
            }

            $rotation = PersonRotation::create([
                'user_id' => Auth::user()->id,
                'destiny_id' => $destiny->person_id,
                'destiny_job' => $destiny->cargo ? $destiny->cargo->Descripcion : $destiny->job->name,
                'destiny_dependency' => $request->destiny_dependency,
                'responsible_id' => $responsible->person_id,
                'responsible_job' => $responsible->cargo ? $responsible->cargo->Descripcion : $responsible->job->name,
                'contract_id' => $person->contracts->sortDesc()->first()->id,
                'date' => $request->date,
                'observations' => $request->observations
            ]);
            return response()->json(['success' => 1, 'message' => 'Rotación registrada correctamente', 'rotation' => $rotation]);
        } catch (\Throwable $th) {
            // dd($th);
            return response()->json(['error' => 1, 'message' => 'Ocurrió un error']);
        }
    }

    public function rotation_delete($people, $id, Request $request){
        try {
            $irremovability = PersonRotation::find($id);
            $irremovability->delete();
            return redirect($_SERVER['HTTP_REFERER'] ?? 'admin/people')->with(['message' => 'Rotación eliminada correctamente', 'alert-type' => 'success']);
        } catch (\Throwable $th) {
            // dd($th);
            return redirect($_SERVER['HTTP_REFERER'] ?? 'admin/people')->with(['message' => 'Ocurrió un error', 'alert-type' => 'error']);
        }
    }

    public function file_store($id, Request $request){
        try {
            $file_name = Str::random(20).'.'.$request->file->getClientOriginalExtension();
            $dir = "people/$id/".date('F').date('Y');
            Storage::makeDirectory($dir);
            Storage::disk('public')->put($dir.'/'.$file_name, file_get_contents($request->file));
            PersonFile::create([
                'user_id' => Auth::user()->id,
                'person_id' => $id,
                'title' => $request->title,
                'file' => $dir.'/'.$file_name,
                'observations' => $request->observations,
            ]);
            return response()->json(['success' => 1, 'message' => 'File registrado correctamente']);
        } catch (\Throwable $th) {
            // throw $th;
            return response()->json(['error' => 1, 'message' => 'Ocurrió un error']);
        }
    }

    public function file_update($id, Request $request){
        try {
            $file = PersonFile::find($request->id);
            $file->title = $request->title;
            if($request->file){
                $file_name = Str::random(20).'.'.$request->file->getClientOriginalExtension();
                $dir = "people/$id/".date('F').date('Y');
                Storage::makeDirectory($dir);
                Storage::disk('public')->put($dir.'/'.$file_name, file_get_contents($request->file));
                $file->file = $dir.'/'.$file_name;
            }
            $file->observations = $request->observations;
            $file->update();

            return redirect()->route('voyager.people.show', $id)->with(['message' => 'File editado correctamente', 'alert-type' => 'success']);
        } catch (\Throwable $th) {
            // throw $th;
            return redirect()->route('voyager.people.show', $id)->with(['message' => 'Ocurrió un error', 'alert-type' => 'error']);
        }
    }

    public function file_delete($people, $id, Request $request){
        try {
            $file = PersonFile::find($id);
            $file->delete();
            return redirect()->route('voyager.people.show', $people)->with(['message' => 'File eliminado correctamente', 'alert-type' => 'success']);
        } catch (\Throwable $th) {
            // dd($th);
            return redirect()->route('voyager.people.show', $people)->with(['message' => 'Ocurrió un error', 'alert-type' => 'error']);
        }
    }

    public function assets_store($id, Request $request){
        // dd($request->all());
        DB::beginTransaction();
        try {

            // *Verificar que alguno de los activo no esté asignado a otro funcionario
            for ($i=0; $i < count($request->asset_id); $i++) { 

                $asset_details = PersonAssetDetail::where('asset_id', $request->asset_id[$i])->where('active', 1)->first();
                if ($asset_details) {
                    return response()->json(['error' => 1, 'message' => 'El activo con el código '.$asset_details->asset->code.' ya está en uso']);
                }
            }

            // Verificar que el código de acta no esté registrado
            if(PersonAsset::where('code', $request->code)->first()){
                return response()->json(['error' => 1, 'message' => 'El código de acta ya está registrado']);
            }

            // Verificar si la persona tiene contrato
            $contract = Contract::where('person_id', $id)->where('status', 'firmado')->first();
            if(!$contract){
                return response()->json(['error' => 1, 'message' => 'La persona no tiene contrato vigente']);
            }

            $person_asset = PersonAsset::create([
                'person_id' => $id,
                'contract_id' => $contract->id,
                'user_id' => Auth::user()->id,
                'code' => $request->code,
                'date' => $request->date,
                'observations' => $request->observations
            ]);

            // Registrar detalle
            for ($i=0; $i < count($request->asset_id); $i++) { 
                PersonAssetDetail::create([
                    'person_asset_id' => $person_asset->id,
                    'asset_id' => $request->asset_id[$i],
                    // 'office_id',
                    'observations' => $request->asset_observations[$i],
                    'status' => $request->status[$i]
                ]);
            }

            // Registrar personas que firman
            for ($i=0; $i < count($request->signature_id); $i++) { 
                PersonAssetSignature::create([
                    'person_asset_id' => $person_asset->id,
                    'contract_id' => $request->signature_id[$i]
                ]);
            }

            DB::commit();
            return response()->json(['success' => 1, 'message' => 'Custodio registrada correctamente', 'person_asset' => $person_asset]);
        } catch (\Throwable $th) {
            DB::rollback();
            // throw $th;
            return response()->json(['error' => 1, 'message' => 'Ocurrió un error']);
        }
    }

    public function assets_print($person_id, $person_asset_id){
        $person_asset = PersonAsset::find($person_asset_id);
        return view('management.people.print.assets', compact('person_asset'));
    }

    public function rotation_print($id){
        $rotation = PersonRotation::with(['destiny', 'responsible', 'contract.person'])->find($id);
        return view('management.docs.permanente.rotation', compact('rotation'));
    }

    public function irremovability_store($id, Request $request){
        try {
            PersonIrremovability::create([
                'person_id' => $id,
                'user_id' => Auth::user()->id,
                'irremovability_type_id' => $request->irremovability_type_id,
                'start' => $request->start,
                'finish' => $request->finish,
                'observations' => $request->observations,
            ]);
            return response()->json(['success' => 1, 'message' => 'Inamovilidad registrada correctamente']);
        } catch (\Throwable $th) {
            // dd($th);
            return response()->json(['error' => 1, 'message' => 'Ocurrió un error']);
        }
    }

    public function irremovability_delete($people, $id, Request $request){
        try {
            $irremovability = PersonIrremovability::find($id);
            $irremovability->delete();
            return redirect()->route('voyager.people.show', $people)->with(['message' => 'Inamovilidad eliminada correctamente', 'alert-type' => 'success']);
        } catch (\Throwable $th) {
            // dd($th);
            return redirect()->route('voyager.people.show', $people)->with(['message' => 'Ocurrió un error', 'alert-type' => 'error']);
        }
    }

    public function afp_status($id, Request $request){
        try {
            Person::where('id', $id)->update([
                'afp_status' => $request->afp_status ? 1 : 0,
                'retired' => $request->retired ? 1 : 0
            ]);
            return response()->json(['success' => 1, 'message' => 'Estado de AFP actualizado']);
        } catch (\Throwable $th) {
            //throw $th;
            return response()->json(['error' => 1, 'message' => 'Ocurrió un error en el servidor']);
        }
    }

    public function attendances($id, Request $request){
        $person = Person::find($id);
        $ci = str_replace(' ', '-', $person->ci); // Reemplazar los espacios en blancos con -
        $ci = explode('-', $ci)[0]; // Obtener solo el valor numérico de CI
        $attendances = DB::connection('sia')
                            ->table('Asistencia')
                            ->where('IdPersona', $ci)
                            ->whereDate('Fecha', $request->date)
                            ->select(DB::raw('IdPersona as ci'), DB::raw('Fecha as fecha'), DB::raw('Hora as hora'))
                            ->get();
        $last_attendance = DB::connection('sia')->table('Asistencia')->select(DB::raw('Fecha as fecha'), DB::raw('Hora as hora'))->orderBy('Fecha', 'DESC')->orderBy('Hora', 'DESC')->first();
        return response()->json(['attendances' => $attendances, 'person_id' => $id, 'last_attendance' => $last_attendance]);
    }

    public function attendances_store($id, Request $request){
        if (!$request->new_hour) {
            return response()->json(['error' => 1]);
        }
        try {
            $person = Person::find($id);
            $ci = str_replace(' ', '-', $person->ci); // Reemplazar los espacios en blancos con -
            $ci = explode('-', $ci)[0]; // Obtener solo el valor numérico de CI
            $insert = DB::connection('sia')
                ->table('Asistencia')
                ->insert([
                    'IdPersona' => $ci,
                    'Fecha' => date('Ymd', strtotime($request->date)),
                    'Hora' => '18991230 '.$request->new_hour.':'.rand(10,59),
                    'Tipo' => 'R'
                ]);
            return response()->json(['success' => 1]);
        } catch (\Throwable $th) {
            return response()->json(['error' => 1]);
        }
    }

    public function attendances_update($id, Request $request){
        DB::beginTransaction();
        try {
            $person = Person::find($id);
            $ci = str_replace(' ', '-', $person->ci); // Reemplazar los espacios en blancos con -
            $ci = explode('-', $ci)[0]; // Obtener solo el valor numérico de CI
            if($request->update_range){
                // Obtener el contrato de la persona
                $contract = Contract::with(['schedules.schedule.details'])->where('id', $request->contract_id)->first();;
                // Inicializar las fechas para recorrerla
                $date = date('Y-m-d', strtotime($request->start));
                $finish = date('Y-m-d', strtotime($request->finish));
                $details = [];
                // Iniciar el recorrido
                while ($date <= $finish) {
                    // Buscar las marcaciones de el día que se está recorriendo
                    $asistencias = DB::connection('sia')->table('Asistencia')->where('IdPersona', $ci)->whereDate('Fecha', $date)->get();
                    // Verificar que no sea feriado
                    $holiday = Holiday::where('status', 1)->whereRaw('CONCAT(LPAD(month,2,0),LPAD(day,2,0)) = "'.date('md', strtotime($date)).'"')->first();
                    // Si no tiene marcaciones en ese día
                    if ($asistencias->count() == 0 && !$holiday) {
                        // Obtener el horario que tenía asignado esa fecha
                        $current_schedule = $contract->schedules->where('start', '<=', $date)->where('finish', '>=', $date)->first();
                        // Si existe horario asignado para esa fecha 
                        if($current_schedule->schedule){
                            // Variable auxiliar para confirmar que se registró ese día
                            $registered = false;
                            // Recorrer el horario de ese día (en caso de que no sea continuo)
                            foreach ($current_schedule->schedule->details->where('day', date('N', strtotime($date))) as $schedule_detail) {
                                // Insertar ingreso
                                DB::connection('sia')->table('Asistencia')->insert([
                                    'IdPersona' => $ci,
                                    'Fecha' => date('Ymd', strtotime($date)),
                                    'Hora' => '18991230 '.$schedule_detail->entry,
                                    'Tipo' => 'M'
                                ]);
                                // Insertar salida
                                DB::connection('sia')->table('Asistencia')->insert([
                                    'IdPersona' => $ci,
                                    'Fecha' => date('Ymd', strtotime($date)),
                                    'Hora' => '18991230 '.$schedule_detail->exit,
                                    'Tipo' => 'M'
                                ]);
                                $registered = true;
                            }

                            if ($registered) {
                                array_push($details, $date);
                            }
                        }
                    }
                    $date = date('Y-m-d', strtotime($date.' +1 days'));
                }

                // Registrar actualización
                ContractScheduleRegisterEdit::create([
                    'user_id' => Auth::user()->id,
                    'contract_id' => $request->contract_id,
                    'start' => $request->start,
                    'finish' => $request->finish,
                    'reason' => $request->reason,
                    'details' => json_encode($details),
                    'file' => $request->file
                ]);
            }else{
                DB::connection('sia')
                    ->table('Asistencia')
                    ->where('IdPersona', $ci)
                    ->whereDate('Fecha', $request->date)
                    ->whereTime('Hora', $request->current_hour)
                    ->update(['Hora' => '18991230 '.$request->new_hour]);
            }
            DB::commit();
            return response()->json(['success' => 1]);
        } catch (\Throwable $th) {
            DB::rollback();
            return response()->json(['error' => 1]);
        }
    }

    public function attendances_delete($id, Request $request){
        try {
            $person = Person::find($id);
            $ci = str_replace(' ', '-', $person->ci); // Reemplazar los espacios en blancos con -
            $ci = explode('-', $ci)[0]; // Obtener solo el valor numérico de CI
            DB::connection('sia')
                ->table('Asistencia')
                ->where('IdPersona', $ci)
                ->whereDate('Fecha', $request->date)
                ->whereTime('Hora', $request->current_hour)
                ->delete();
            return response()->json(['success' => 1]);
        } catch (\Throwable $th) {
            return response()->json(['error' => 1]);
        }
    }
}
