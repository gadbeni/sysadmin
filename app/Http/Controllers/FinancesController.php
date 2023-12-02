<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

// Models
use App\Models\PersonExternal;
use App\Models\MemosType;

class FinancesController extends Controller
{
    public function person_external_create(){
        return view('finance.person_externals.edit-add');
    }

    public function person_external_store(Request $request){
        try {
            $person_external = PersonExternal::create([
                'person_external_type_id' => $request->person_external_type_id,
                'person_id' => $request->person_id,
                'full_name' => $request->full_name,
                'ci_nit' => $request->ci_nit,
                'number_acount' => $request->number_acount,
                'phone' => $request->phone,
                'address' => $request->address,
                'email' => $request->email,
            ]);

            if($request->ajax){
                return response()->json($person_external);
            }

            return redirect()->route('voyager.person-externals.index')->with(['message' => 'Registro guardado exitosamente.', 'alert-type' => 'success']);
        } catch (\Throwable $th) {
            // throw $th;
            if($request->ajax){
                return response()->json(['error' => 1]);
            }
            return redirect()->route('voyager.person-externals.index')->with(['message' => 'Ocurrió un error', 'alert-type' => 'error']);
        }
    }

    public function memos_types_create(){
        return view('finance.memos_types.edit-add');
    }

    public function memos_types_store(Request $request){
        // dd($request->all());
        try {
            $memos_type = MemosType::create([
                'memos_types_group_id' => $request->memos_types_group_id,
                'origin_id' => $request->origin_id,
                'destiny_id' => $request->destiny_id,
                'description' => $request->description,
                'concept' => $request->concept,
                'status' => $request->status ? 1 : 0
            ]);

            if($request->ajax){
                return response()->json($memos_type);
            }

            return redirect()->route('voyager.memos-types.index')->with(['message' => 'Registro guardado exitosamente.', 'alert-type' => 'success']);

        } catch (\Throwable $th) {
            //throw $th;
            if($request->ajax){
                return response()->json(['error' => 1]);
            }
            return redirect()->route('voyager.memos-types.index')->with(['message' => 'Ocurrió un error', 'alert-type' => 'error']);
        }
    }

    public function memos_types_show($id){
        $data = MemosType::find($id);
        return view('finance.memos_types.read', compact('data'));
    }

    public function memos_types_edit($id){
        $type = MemosType::find($id);
        return view('finance.memos_types.edit-add', compact('type'));
    }

    public function memos_types_update($id, Request $request){
        try {
            MemosType::where('id', $id)->update([
                'memos_types_group_id' => $request->memos_types_group_id,
                'origin_id' => $request->origin_id,
                'destiny_id' => $request->destiny_id,
                'description' => $request->description,
                'concept' => $request->concept,
                'status' => $request->status ? 1 : 0
            ]);

            return redirect()->route('voyager.memos-types.index')->with(['message' => 'Registro editado exitosamente.', 'alert-type' => 'success']);

        } catch (\Throwable $th) {
            //throw $th;
            return redirect()->route('voyager.memos-types.index')->with(['message' => 'Ocurrió un error', 'alert-type' => 'error']);
        }
    }
}
