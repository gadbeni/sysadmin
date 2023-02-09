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
use App\Models\Direccion;
use App\Models\TcAdditionalJob;
use App\Models\TcCategory;
use App\Models\TcVia;
use Illuminate\Support\Str;
// use Illuminate\Support\Facades\Storage;
use Storage;

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
        $getPeople = new TcController();
        $outbox = new TcOutbox();
        $user_auth = Person::where('ci', Auth::user()->ci)->first();

        $funcionario = $getPeople->getPeople($user_auth->id);

        $category = TcCategory::with(['organization' => function($q){
            $q->where('tipo','tptramites');
        }])->get();

        // return $category;
       
        return view('correspondencia.outbox.add-edit', compact('outbox', 'funcionario', 'category'));        
    }

    public function store(Request $request)
    {  
        // return $request;
        $getFuncionario = new TcController();
        $request->merge(['cite' =>  strtoupper($request->cite)]);

        //para verificar si exixte el cite regsitrado
        $oldtramite = TcOutbox::where('tipo',$request->tipo)
                            ->where('cite',$request->cite)
                            ->where('deleted_at',NULL)
                            ->first();       
        if ($oldtramite) {
            return redirect()->route('outbox.index')->with(['message'=>'El cite ya se encuentra registrado', 'alert-type' => 'error']);
        }

        DB::beginTransaction();
        try {

            // $persona = Persona::where('user_id', Auth::user()->id)->first();
            $persona = Person::where('ci', Auth::user()->ci)->first();
            // return $persona;
            $unidad_id_remitente = NULL;
            $direccion_id_remitente = null;
            $funcionario_remitente = NULL;
            /*
                Si el trámite es interno se debe obtener la unidad y la dirección de del remitente (funcionario_id) 
            */
            // return $request;
            // return $persona;

            if($persona->id)
            {
                // return $request->funcionario_id_remitente;
                if($request->tipo == 'I'){
                    $unidad_id_remitente = $getFuncionario->getPeople($request->funcionario_id_remitente)->id_unidad;
                    $direccion_id_remitente = $getFuncionario->getPeople($request->funcionario_id_remitente)->id_direccion;
                }
                // dd($direccion_id_remitente);
                // return $this->getPeople($persona->people_id)->cargo;
                $job = $getFuncionario->getPeople($persona->id)->cargo;
                // dd($job);
                // return $request;
                
                $data = TcOutbox::create([
                    'gestion' => date('Y'),
                    'tipo' => $request->tipo,
                    'remitente' => $request->remitent_interno,
                    'people_id_de' => $request->funcionario_id_remitente,
                    'job_de' => $job,

                    'cite' => $request->cite,
                    'referencia' => $request->referencia,
                    'nro_hojas' => $request->nro_hojas,
                    'urgent' => ($request->urgent) ? true : false,
                    'deadline' => $request->deadline,
                    // 'estado' => 'activo',
                    'fecha_registro' => $request->fecha_registro,
                    'detalles' => $request->detalles,
                    'unidad_id_remitente' => $unidad_id_remitente,
                    'direccion_id_remitente' => $direccion_id_remitente,
                    // 'funcionario_id_destino' => $request->funcionario_id_destino,
                    'people_id_para' => $request->funcionario_id_destino,
                    'job_para' => $getFuncionario->getPeople($request->funcionario_id_destino)->cargo,
                    'registrado_por' => Auth::user()->email,
                    // Cambiar el parámetro de la llamada a la funcion getIdDireccionFuncionario
                    'registrado_por_id_direccion' => $getFuncionario->getPeople($request->funcionario_id_remitente)->id_direccion,
                    'registrado_por_id_unidad' => $getFuncionario->getPeople($request->funcionario_id_remitente)->id_unidad,
                    'entity_id' => $request->entity_id,
                    'category_id' => $request->category_id,
                    'estado_id' => 6
                ]);
                // return 12;
            }
            else
            {
                return redirect()->route('outbox.index')->with(['message' => 'Ocurrio un error en el usuario.', 'alert-type' => 'error']);
            }
            // return 2;
            
            $file = $request->file('archivos');
            if ($file) {
                for ($i=0; $i < count($file); $i++) { 
                    $nombre_origen = $file[$i]->getClientOriginalName();
                    $newFileName = Str::random(20).'.'.$file[$i]->getClientOriginalExtension();
                    $dir = "entradas/".date('F').date('Y');
                    Storage::makeDirectory($dir);
                    Storage::disk('siscor')->put($dir.'/'.$newFileName, file_get_contents($file[$i]));
                    TcArchivo::create([
                        'nombre_origen' => $nombre_origen,
                        'entrada_id' => $data->id,
                        'ruta' => $dir.'/'.$newFileName,
                        'user_id' => Auth::user()->id
                    ]);
                }
            }
            // return 1;
            DB::commit();
            
            return redirect()->route('outbox.index')->with(['message' => 'Registro guardado exitosamente.', 'alert-type' => 'success']);
        } catch (\Throwable $th) {
            //  dd($th);
            DB::rollback();
            return 00;
            return redirect()->route('outbox.index')->with(['message' => 'Ocurrio un error.', 'alert-type' => 'error']);
        }

    }


    public function edit(TcOutbox $outbox)
    {
        $getFuncionario = new TcController();

        $user_auth = Person::where('ci', Auth::user()->ci)->first();  

        $funcionario = $getFuncionario->getPeople($user_auth->id);
        // return $outbox;

        // $funcionario = Persona::where('user_id', Auth::user()->id)->first();
        return view('correspondencia.outbox.add-edit', compact('outbox','funcionario'));
    }


    public function update(Request $request, TcOutbox $outbox)
    {
        $getFuncionario = new TcController();

        DB::beginTransaction();
        try {

            // return $outbox;
            $request->merge(['cite' =>  strtoupper($request->cite)]);

            $persona = Person::where('ci', Auth::user()->ici)->first();

            $unidad_id_remitente = NULL;
            $direccion_id_remitente = null;
            $funcionario_remitente = NULL;
            

            if($request->tipo == 'I'){
                $unidad_id_remitente = $outbox->unidad_id_remitente;
                $direccion_id_remitente = $outbox->direccion_id_remitente;
            }

            // return $direccion_id_remitente;
            // return $entrada;
            $date = Carbon::now();

            $outbox->update([
                'tipo' => $request->tipo,
                'remitente' => $request->remitent_interno,
                'cite' => $request->cite,
                'referencia' => $request->referencia,
                'nro_hojas' => $request->nro_hojas,
                'urgent' => ($request->urgent) ? true : false,
                'deadline' => $request->deadline,
                // 'estado' => 'activo',
                'detalles' => $request->detalles,
                // 'funcionario_id_remitente' => $request->funcionario_id_remitente,
                'people_id_de' => $request->funcionario_id_remitente,
                'unidad_id_remitente' => $unidad_id_remitente,
                'direccion_id_remitente' => $direccion_id_remitente,
                'people_id_para' => $request->funcionario_id_destino,
                'job_para' => $getFuncionario->getPeople($request->funcionario_id_destino)->cargo,
                'entity_id' => $request->entity_id,
                'actualizado_por' => auth()->user()->email,
                'category_id' => $request->category_id,
                'fecha_actualizacion' => $date->toDateTimeString()
            ]);

            $file = $request->file('archivos');
            if ($file) {
                for ($i=0; $i < count($file); $i++) { 
                    $nombre_origen = $file[$i]->getClientOriginalName();
                    $newFileName = Str::random(20).'.'.$file[$i]->getClientOriginalExtension();
                    $dir = "entradas/".date('F').date('Y');
                    Storage::makeDirectory($dir);
                    Storage::disk('siscor')->put($dir.'/'.$newFileName, file_get_contents($file[$i]));
                    TcArchivo::create([
                        'nombre_origen' => $nombre_origen,
                        'entrada_id' => $outbox->id,
                        'ruta' => $dir.'/'.$newFileName,
                        'user_id' => Auth::user()->id
                    ]);
                }
            }

            DB::commit();
            return redirect()->route('outbox.index')->with(['message' => 'Registro actualizado exitosamente.', 'alert-type' => 'success']);
        } catch (\Throwable $th) {
            //  dd($th);
            DB::rollback();
            // return 1;
            return redirect()->route('outbox.index')->with(['message' => 'Ocurrio un error.', 'alert-type' => 'error']);
        }
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

    public function printhr(TcOutbox $entrada){
        // return 3;
        // $view = view('entradas.print-hoja-ruta',['entrada' => $entrada->load('derivaciones','entity')]);
        // //return $view;
        // $pdf = \App::make('dompdf.wrapper');
        // $pdf->loadHTML($view);
        // return $pdf->stream();

        return view('correspondencia.outbox.print-hoja-ruta');
    }
    public function print(TcOutbox $outbox){

        $entrada = $outbox;
        // return $entrada;
        // $additional = DB::table('additional_jobs')
        //         ->where('person_id',$entrada->people_id_de)
        //         ->where('status', 1)
        //         ->select('*')
        //         ->get();

        $additional = TcAdditionalJob::where('person_id',$entrada->people_id_de)
                ->where('status', 1)
                ->select('*')
                ->get();
        // return $additional;
        // $additionals = DB::table('additional_jobs')
        //         ->where('person_id',$entrada->people_id_para)
        //         ->where('status', 1)
        //         ->select('*')
        //         ->get();    
        $additionals = TcAdditionalJob::where('person_id',$entrada->people_id_para)
                ->where('status', 1)
                ->select('*')
                ->get();  

        $via = TcVia::where('entrada_id', $entrada->id)->where('deleted_at', null)->get();

        return view('correspondencia.outbox..hr', compact('entrada', 'via', 'additional', 'additionals'));
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

    // Para poder subuir archivos al tramite para que le permita derivar el tram.
    public function entradaFile(Request $request)
    {
        // return $request;
        DB::beginTransaction();
        try {
            $file = $request->file('archivos');
            if ($file) {
                for ($i=0; $i < count($file); $i++) { 
                    $nombre_origen = $file[$i]->getClientOriginalName();
                    $newFileName = Str::random(20).'.'.$file[$i]->getClientOriginalExtension();
                    $dir = "entradas/".date('F').date('Y');
                    Storage::makeDirectory($dir);
                    Storage::disk('siscor')->put($dir.'/'.$newFileName, file_get_contents($file[$i]));
                    TcArchivo::create([
                        'nombre_origen' => $nombre_origen,
                        'entrada_id' => $request->id,
                        'ruta' => $dir.'/'.$newFileName,
                        'user_id' => Auth::user()->id,
                        'nci'=>1
                    ]);
                }
            }
            DB::commit();
            return redirect()->route('outbox.show', ['outbox' => $request->id])->with(['message' => 'Registro guardado exitosamente.', 'alert-type' => 'success']);

        } catch (\Throwable $th) {
            DB::rollBack();
            return redirect()->route('outbox.show', ['outbox' => $request->id])->with(['message' => 'Ocurrio un error.', 'alert-type' => 'error']);

        }
    }


    public function store_file(Request $request){
        try {
            $file = $request->file('file');
            if ($file) {
                $nombre_origen = $file->getClientOriginalName();
                $newFileName = Str::random(20).'.'.$file->getClientOriginalExtension();
                $dir = "entradas/".date('F').date('Y');
                Storage::makeDirectory($dir);
                Storage::disk('siscor')->put($dir.'/'.$newFileName, file_get_contents($file));
                TcArchivo::create([
                    'nombre_origen' => $nombre_origen,
                    'entrada_id' => $request->id,
                    'ruta' => $dir.'/'.$newFileName,
                    'user_id' => Auth::user()->id
                ]);
            }
            return redirect()->back()->with(['message' => 'Archivo agregado correctamente.', 'alert-type' => 'success']);
        } catch (\Throwable $th) {
            // dd($th);
            return redirect()->back()->with(['message' => 'Ocurrio un error.', 'alert-type' => 'error']);
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


    public function delete_derivacion_file(Request $request){
        // dd($request);
        try {
            TcArchivo::where('id', $request->id)->update(['deleted_at' => Carbon::now()]);
            return redirect()->route('outbox.show', ['outbox' => $request->entrada_id])->with(['message' => 'Archivo eliminado exitosamente.', 'alert-type' => 'success']);
        } catch (\Throwable $th) {
            // dd($th);
            return redirect()->route('outbox.show', ['outbox' => $request->entrada_id])->with(['message' => 'Ocurrio un error.', 'alert-type' => 'error']);
        }
    }



    public function store_derivacion(Request $request){
        //    return $request;
            $getPeople = new TcController();
            $destinatarios = $request->destinatarios;
            $persona = TcPersona::where('ci', Auth::user()->ci)->first();
            // return $persona;
            if(!$persona){
                return redirect()->back()->with(['message' => 'No estás registrado como funcionario.', 'alert-type' => 'error']);
            }
            DB::beginTransaction();
            try {
                $cont = 0; 
                // return $request;
                foreach ($destinatarios as $valor) {
                    $user = TcPersona::where('id', $valor)->first();
                    // return $user;
                    /*Si la derivación viene del RDE no se registra al funcionario de origen*/
                    $rde = $request->redirect ? null : 1;
                    $redirect = $request->redirect ?? 'outbox.index';
                    // return $redirect;
                    $entrada = TcOutbox::findOrFail($request->id);
                    // dd($getPeople->getPeople($valor));
                    $funcionario = $getPeople->getPeople($valor);
                    // dd($funcionario);
                    // return $funcionario->id_funcionario;
                    if($funcionario){
                        // Actualizar estado de la correspondencia
                        // return $entrada;
                        $detaillast = array();
                        if (isset($entrada->details)) {
                            $detaillast[] = $entrada->details;
                        }
                        $detalle = [
                            'id_origen' => auth()->user()->id, 
                            'nombre_origen' => $persona->first_name .' '. $persona->last_name,
                            'id_para' => $funcionario->id_funcionario,
                            'nombre_para' => $funcionario->nombre,
                            'fecha' => Carbon::now()
                        ];
                        // return $detaillast;
                      
                        if (count($detaillast) == 0) {
                            $entrada->details = $detalle;
                        } 
                        // else {
                        //     array_push($detaillast,$detalle);
                        //     return $detaillast;
                        //     $entrada->details = $detaillast;

                        // }
                        // return $entrada;

                        
                        $entrada->estado_id = 3;
                        $entrada->save();
                        $cont++;
                        // return $entrada;
                        $this->add_derivacion($funcionario, $request, null, $entrada->tipo == 'E' ? $rde: NULL);
                        // return 1;
                        // return $redirect;
                        DB::commit();
                    }else{
                        return redirect()->route($redirect)->with(['message' => 'El destinatario elegido no es un funcionario.', 'alert-type' => 'error']);
                    }
                }
                if($request->der_id)
                {
                    TcInbox::where('id', $request->der_id)->update(['derivation' => 1, 'ok' => 'SI']);
                }
                return redirect()->route($redirect)->with(['message' => 'Correspondecia derivada exitosamente.', 'alert-type' => 'success', 'funcionario_id' => $user ? $user->user_id : null]);
            } catch (\Throwable $th) {
                DB::rollback();
                return $th;            
                return redirect()->route($redirect)->with(['message' => 'Ocurrio un error.', 'alert-type' => 'error']);
            }
        }


        public function add_derivacion($funcionario, $request, $rechazo = NULL, $rde = null){
            $getPeople = new TcController();
            $persona = TcPersona::where('ci', Auth::user()->ci)->first();
    
            $vias = TcVia::where('entrada_id',$request->id)->where('deleted_at', null)->get();
            
            // dd($funcionario->id_funcionario);
            $cant = count(TcInbox::where('entrada_id',$request->id)->where('via',1)->where('deleted_at', null)->get());
            // dd($vias);
            if($cant == 0)
            {
                foreach($vias as $data)
                {   
                    $viafuncionario = $getPeople->getPeople($data->people_id);
                    // dd($viafuncionario);
                    $a = TcInbox::create([
                        'entrada_id' => $request->id,
                        // 'funcionario_id_de' => $rde ? null : $persona->funcionario_id,
                        'people_id_de' => $rde ? null : $persona->people_id,
                        // 'funcionario_id_para' => $viafuncionario->id_funcionario,
                        'people_id_para' => $viafuncionario->id_funcionario,
                        'funcionario_nombre_para' => $viafuncionario->nombre,
                        'funcionario_cargo_para' => $viafuncionario->cargo,
                        'funcionario_direccion_id_para' => $viafuncionario->id_direccion ?? null,
                        'funcionario_direccion_para' => $viafuncionario->direccion ?? null,
                        'funcionario_unidad_id_para' => $viafuncionario->id_unidad ?? null,
                        'funcionario_unidad_para' => $viafuncionario->unidad ?? null,
                        'responsable_actual' => 1,
                        'rechazo' => $rechazo,
                        'via'   => 1,
                        'registro_por' => Auth::user()->email,
                        'observacion' => $request->observacion,
                        'parent_id' => $request->der_id ? $request->der_id : $request->id,
                        'parent_type' => $request->der_id ? 'App\Models\Derivation' : 'App\Models\Entrada',
                    ]);
                }
            }
            // return $request;
            // // dd($funcionario);
            // return Auth::user()->email;
            return TcInbox::create([
                'entrada_id' => $request->id,
                // 'funcionario_id_de' => $rde ? null : $persona->funcionario_id,
                'people_id_de' => $rde ? null : $persona->id,
                // 'funcionario_id_para' => $funcionario->id_funcionario,
                'people_id_para' => $funcionario->id_funcionario,
                'funcionario_nombre_para' => $funcionario->nombre,
                'funcionario_cargo_para' => $funcionario->cargo,
                'funcionario_direccion_id_para' => $funcionario->id_direccion ?? null,
                'funcionario_direccion_para' => $funcionario->direccion ?? null,
                'funcionario_unidad_id_para' => $funcionario->id_unidad ?? null,
                'funcionario_unidad_para' => $funcionario->unidad ?? null,
                'responsable_actual' => 1,
                'rechazo' => $rechazo,
                'via'   => 0,
                'registro_por' => Auth::user()->email,
                'observacion' => $request->observacion,
                'parent_id' => $request->der_id ? $request->der_id : $request->id,
                'parent_type' => $request->der_id ? 'App\Models\Derivation' : 'App\Models\Entrada',
            ]);
        }

    

}
