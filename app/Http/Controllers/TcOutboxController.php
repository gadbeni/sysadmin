<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Outbox;
use App\Models\Person;
use App\Models\TcArchivo;
use Illuminate\Support\Facades\DB;
use App\Models\TcOutbox;
use App\Models\TcInbox;
use App\Models\TcPersona;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\TcController;
use App\Models\TcVia;

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
        // $query_filtro = 'people_id_de = '.$funcionario->id;
        if (auth()->user()->hasRole('admin'))
        {
            $query_filtro = 1;
        }
        if ($funcionario)
        {
            $query_filtro = 'people_id_de = '.$funcionario->id;
        }
        // dd($query_filtro);
        $data = TcOutbox::with(['entity:id,nombre', 'estado:id,nombre'])
                        ->whereRaw($query_filtro)
                        ->select([
                            'id','tipo','gestion','estado_id','cite', 'hr','remitente','referencia','entity_id','created_at', 'people_id_para'
                        ])
                        ->whereRaw($search ? "(hr like '%$search%' or cite like '%$search%' or remitente like '%$search%' or referencia like '%$search%')" : 1)
                        ->where('deleted_at', NULL)->orderBy('id', 'DESC')->paginate($paginate);
        
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


    public function show($id)
    {
        // return $id;
        $data = TcOutbox::with(['entity', 'estado', 'archivos.user', 'inbox' => function($q){
                    $q->where('deleted_at', NULL);
                      //->whereNull('parent_id');
                }, 'archivos' => function($q){
                    $q->where('deleted_at', NULL);
                },'vias'])->where('id', $id)
                ->where('deleted_at', NULL)->first();

        $nci = TcArchivo::where('entrada_id', $id)->where('deleted_at', null)->get();
        /*
            En caso de se una nota interna obtener los datos del remietente
        */

        $origen = '';
        $destino = NULL;
        if($data->tipo == 'I'){
            // $destino = $this->getPeopleSN($data->people_id_para);
            $destino = Person::where('id', $data->people_id_para)->select('id as id_funcionario', 'ci as N_Carnet', DB::raw("CONCAT(first_name, ' ', last_name) as nombre"))
            ->first();
        }
        // return $destino;

        return view('correspondencia.outbox.read', compact('data', 'destino', 'nci'));
    }
    
    public function store_vias(Request $request){
        $getPeople = new TcController();
        DB::beginTransaction();
        try {
            $entrada = TcOutbox::findOrFail($request->id);
            $via = $getPeople->getPeople($request->via);
            // return $via;
            
            if($via){
                $entrada->vias()->create([
                    'funcionario_id' => $via->id_funcionario,
                    'people_id' => $via->id_funcionario,
                    'nombre' => $via->nombre,
                    'cargo' => $via->cargo,
                ]);
                DB::commit();
               
            }else{
                return redirect()->back()->with(['message' => 'El destinatario elegido no es un funcionario.', 'alert-type' => 'error']);
            }
            return redirect()->back()->with(['message' => 'Via agregada exitosamente.', 'alert-type' => 'success']);
        } catch (\Throwable $th) {
            DB::rollback();
            //dd($th);
            return 'Contactese con el Administrador';
            // return redirect()->route($redirect)->with(['message' => 'Ocurrio un error.', 'alert-type' => 'error']);
        }
    }

    public function anulacion_via(Request $request){
        try {
            TcVia::findOrFail($request->id)->delete();
            return redirect()->route('outbox.show', ['outbox' => $request->entrada_id])->with(['message' => 'Via Anulada exitosamente.', 'alert-type' => 'success']);
        } catch (\Throwable $th) {
            // dd($th);
            return redirect()->route('outbox.show', ['outbox' => $request->entrada_id])->with(['message' => 'Ocurrio un error.', 'alert-type' => 'error']);
        }
    }

    // para eliminar la primera derivacion del tramite
    public function delete_derivacions(Request $request){
        DB::beginTransaction();
        try {
            TcInbox::where('entrada_id', $request->entrada_id)->where('deleted_at', null)->update(['deleted_at' => Carbon::now()]);

            TcVia::where('entrada_id', $request->entrada_id)->where('deleted_at', null)->update(['deleted_at' => Carbon::now()]);

            DB::commit();
            return redirect()->route('outbox.show', ['outbox' => $request->entrada_id])->with(['message' => 'Derivación anulada exitosamente.', 'alert-type' => 'success']);
        } catch (\Throwable $th) {
            DB::rollBack();
            return redirect()->route('outbox.show', ['outbox' => $request->entrada_id])->with(['message' => 'Ocurrio un error.', 'alert-type' => 'error']);
        }
    }
    public function delete_derivacion(Request $request){
        // return $request;
        DB::beginTransaction();
        try {
            $ok = TcInbox::where('id', $request->id)->where('deleted_at', null)->where('entrada_id', $request->entrada_id)->first();
            // return $ok;
            $ok->update(['deleted_at' => Carbon::now()]);

            $data = TcInbox::where('parent_id', $ok->parent_id)->where('deleted_at', null)->where('entrada_id', $request->entrada_id)->count();
            
            // return $data;
            if($data == 0)
            {
                TcInbox::where('id', $ok->parent_id)->where('deleted_at', null)->where('entrada_id', $request->entrada_id)
                ->update(['derivation'=>0, 'ok'=>'NO']);
            }

            DB::commit();
            return redirect()->route('outbox.show', ['outbox' => $request->entrada_id])->with(['message' => 'Derivación anulada exitosamente.', 'alert-type' => 'success']);
        } catch (\Throwable $th) {
            DB::rollBack();
            return redirect()->route('outbox.show', ['outbox' => $request->entrada_id])->with(['message' => 'Ocurrio un error.', 'alert-type' => 'error']);
        }
    }

}
