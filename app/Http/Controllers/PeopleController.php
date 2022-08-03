<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Carbon;

use App\Models\Person;
use App\Models\PersonRotation;
use App\Models\Contract;
use App\Models\PersonIrremovability;

class PeopleController extends Controller
{
    public function index(){
        $people =   Person::whereHas('contracts', function($query){
                        $query->where('status', 'firmado')->where('deleted_at', NULL);
                    })->where('deleted_at', NULL)->get();
        return view('management.people.browse', compact('people'));
    }

    public function list($search = null){
        $paginate = request('paginate') ?? 10;
        $data = Person::with(['city'])
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
                    ->whereRaw(Auth::user()->direccion_administrativa_id ? "user_id = ".Auth::user()->id : 1)
                    ->where('deleted_at', NULL)->orderBy('id', 'DESC')->paginate($paginate);
        return view('management.people.list', compact('data'));
    }

    public function read($id){
        $person = Person::with(['contracts.rotations', 'irremovabilities.type'])->where('id', $id)->first();
        return view('management.people.read', compact('person'));
    }

    public function rotation_store($id, Request $request){
        try {
            $person = Person::where('id', $id)->whereHas('contracts', function($query){
                $query->where('status', 'firmado')->where('deleted_at', NULL);
            })->where('deleted_at', NULL)->first();
            if($person->contracts){
                return redirect()->route('voyager.people.index')->with(['message' => 'El funcionario no tiene un contrato vigente', 'alert-type' => 'error']);
            }

            $destiny = Contract::where('person_id', $request->destiny_id)->where('status', 'firmado')->where('deleted_at', NULL)->first();
            $responsible = Contract::where('person_id', $request->responsible_id)->where('status', 'firmado')->where('deleted_at', NULL)->first();

            $rotation = PersonRotation::create([
                'destiny_id' => $destiny->person_id,
                'destiny_job' => $destiny->cargo ? $destiny->cargo->Descripcion : $destiny->job->name,
                'destiny_dependency' => $request->destiny_dependency,
                'responsible_id' => $responsible->person_id,
                'responsible_job' => $responsible->cargo ? $responsible->cargo->Descripcion : $responsible->job->name,
                'contract_id' => $person->contracts->first()->id,
                'date' => $request->date,
                'observations' => $request->observations
            ]);

            return redirect()->route('voyager.people.index')->with(['message' => 'Rotación registrada correctamente', 'alert-type' => 'success', 'rotation_id' => $rotation->id]);
        } catch (\Throwable $th) {
            dd($th);
            return redirect()->route('voyager.people.index')->with(['message' => 'Ocurrió un error', 'alert-type' => 'error']);
        }
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
            return redirect()->route('voyager.people.index')->with(['message' => 'Inamovilidad reggistrada correctamente', 'alert-type' => 'success']);
        } catch (\Throwable $th) {
            // dd($th);
            return redirect()->route('voyager.people.index')->with(['message' => 'Ocurrió un error', 'alert-type' => 'error']);
        }
    }
}
