<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Maatwebsite\Excel\Facades\Excel;

// Imporst
use App\Imports\AuxiliarImport;

class ImportsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function imports_index(){
        return view('imports.browse');
    }

    public function imports_store(Request $request){
        // dd($request->all());

        // $url = $request->file('file')->store(
        //     'paymentshedulesfiles', 'public'
        // );
        Excel::import(new AuxiliarImport($request->type), request()->file('file'));
        return redirect()->route('imports.index')->with(['message' => 'Importación completa.', 'alert-type' => 'success']);
    }
}
