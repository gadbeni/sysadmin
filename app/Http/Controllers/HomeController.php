<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

// Models
use App\Models\Aguinaldo;
use App\Models\PaymentschedulesDetail;
use App\Models\PersonExternal;
use App\Models\Suggestion;
use App\Models\PersonNotification;

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

    public function person($id){
        return 'info';
    }

    public function send_message(Request $request){
        try {
            if (setting('servidores.whatsapp') && setting('servidores.whatsapp-session')) {
                $phone = strlen($request->phone) == 8 ? '591'.$request->phone : (strlen($request->phone) == 11 ? $request->phone : null);
                if ($phone) {
                    $image = $this->store_image($request->image, 'notifications');
                    Http::post(setting('servidores.whatsapp').'/send?id='.setting('servidores.whatsapp-session'), [
                        'phone' => $phone,
                        'text' => $request->message,
                        'image_url' => $image
                    ]);

                    PersonNotification::create([
                        'user_id' => Auth::user()->id,
                        'person_id' => $request->person_id,
                        'phone' => $phone,
                        'message' => $request->message,
                        'file' => $image
                    ]);
                }else{
                    return response()->json(['error' => 1]);
                }
            }
            return response()->json(['success' => 1, 'message' => 'Notificación enviada']);
        } catch (\Throwable $th) {
            //throw $th;
            return response()->json(['error' => 1]);
        }
    }

    public function send_suggestion(Request $request){
        try {
            Suggestion::create([
                'user_id' => Auth::user()->id,
                'details' => $request->details
            ]);
            return response()->json(['success' => 1]);
        } catch (\Throwable $th) {
            //throw $th;
            return response()->json(['error' => 1]);
        }
    }

    public function get_duration($start, $finish) {
        $data = contract_duration_calculate($start, $finish);
        return response()->json(['duration' => $data]);
    }
}