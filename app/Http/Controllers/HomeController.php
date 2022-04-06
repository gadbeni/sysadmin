<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Aguinaldo;
use App\Models\PaymentschedulesDetail;

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
}
