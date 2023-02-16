<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Aguinaldo;
use App\Models\PaymentschedulesDetail;
use Illuminate\Support\Facades\Http;

// Models
use App\Models\PersonExternal;

class HomeController extends Controller
{
    public function index(){
        return view('welcome');
    }

    public function search_payroll_by_ci(Request $request){
        $search = $request->search;
    
        if($search){
            $data = PaymentschedulesDetail::with('paymentschedule.period')->whereHas('contract.person', function($q) use ($search){
                $q->where('ci', $search);
            })->where('status', 'habilitado')->where('deleted_at', NULL)->limit(2)->get();
            // $aguinaldo = Aguinaldo::with('payment.cashier.user')->where('ci', $search)->where('deleted_at', NULL)->where('estado', 'pendiente')->first();
            return response()->json(['search' => $data]);
        }else{
            return response()->json(['error' => 'La cédula de identidad ingresada no está registrada en el sistema.']);
        }
    }

    public function register_person(){
        return view('register');
    }

    public function register_person_store(Request $request){
        try {
            $person = PersonExternal::where('ci_nit', $request->ci_nit)->first();
            if($person){
                return response()->json(['error' => 1, 'message' => 'La persona ya está registrada']);
            }
            PersonExternal::create([
                'city_id' => $request->city_id,
                'full_name' => $request->full_name,
                'birthday' => $request->birthday,
                'gender' => $request->gender,
                'job' => $request->job,
                'family' => $request->family,
                'ci_nit' => $request->ci_nit,
                'phone' => $request->phone,
                'address' => $request->address,
                'location' => $request->location
            ]);
            try {
                Http::get('https://whatsapp-api.beni.gob.bo/?number=591'.$request->phone.'&message=Muchas gracias '.$request->full_name.', por contribuir en el desarrollo del Departamento del Beni con tu registro nos ayudará a tomar decisiones en beneficio del departamento.');
            } catch (\Throwable $th) {
                //throw $th;
            }
            return response()->json(['success' => 1, 'message' => 'Datos registrados correctamente']);
        } catch (\Throwable $th) {
            //throw $th;
            return response()->json(['error' => 1, 'message' => 'Ocurrió un error en el servidor']);
        }
    }
}
