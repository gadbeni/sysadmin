<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

// Models
use App\Models\PersonExternal;

class FinancesController extends Controller
{
    public function person_external_create(){
        return view('finance.person_externals.edit-add');
    }

    public function person_external_store(Request $request){
        try {
            PersonExternal::create([
                'person_external_type_id' => $request->person_external_type_id,
                'person_id' => $request->person_id,
                'full_name' => $request->full_name,
                'ci_nit' => $request->ci_nit,
                'number_acount' => $request->number_acount,
                'phone' => $request->phone,
                'address' => $request->address,
                'email' => $request->email,
            ]);
            return redirect()->route('voyager.person-externals.index')->with(['message' => 'Registro guardado exitosamente.', 'alert-type' => 'success']);
        } catch (\Throwable $th) {
            // throw $th;
            return redirect()->route('voyager.person-externals.index')->with(['message' => 'Ocurrió un error.', 'alert-type' => 'error']);
        }
    }
}
