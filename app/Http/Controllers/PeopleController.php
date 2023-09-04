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
        $data = Person::with(['city', 'afp_type', 'contracts', 'irremovabilities' => function($q){
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
}
