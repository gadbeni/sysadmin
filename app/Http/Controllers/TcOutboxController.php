<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Outbox;
use Illuminate\Support\Facades\DB;
use App\Models\TcOutbox;
use App\Models\TcInbox;
use App\Models\TcPersona;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class TcOutboxController extends Controller
{
    public function index()
    {
        return view('correspondencia.outbox.browse');
    }

    public function list(){
        // dd(1);
        $paginate = request('paginate') ?? 10;
        $search = request('search') ?? null;
        // $funcionario = Persona::where('user_id', Auth::user()->id)->first();
        $funcionario = TcPersona::where('ci', Auth::user()->ci)->first();
        $query_filtro = 'people_id_de = '.$funcionario->id;
        if (auth()->user()->hasRole('admin'))
        {
            $query_filtro = 1;
        }
        // dd(1);
        $data = TcOutbox::with(['entity:id,nombre', 'estado:id,nombre'])
                        ->whereRaw($query_filtro)
                        ->select([
                            'id','tipo','gestion','estado_id','cite', 'hr','remitente','referencia','entity_id','created_at', 'people_id_para'
                        ])
                        ->whereRaw($search ? "(hr like '%$search%' or cite like '%$search%' or remitente like '%$search%' or referencia like '%$search%')" : 1)
                        ->where('deleted_at', NULL)->orderBy('id', 'DESC')->paginate($paginate);
        // dd($data);
        
        return view('correspondencia.outbox.list', compact('data'));
    }

    public function create()
    {

       
       
        return view('correspondencia.outbox.add-edit');        
    }



    public function destroy($id)
    {  
        DB::beginTransaction();
        try {
            $entrada = TcOutbox::findOrFail($id);
            $entrada->inbox()->delete();
            $entrada->delete();
            DB::commit();
            return redirect()->route('outbox.index')->with(['message' => 'Registro anulado exitosamente.', 'alert-type' => 'success']);
        } catch (\Throwable $th) {
            DB::rollback();
        }   
       
    }
}
