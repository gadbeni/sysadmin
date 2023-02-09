<?php

namespace App\Http\Controllers;

use App\Models\Direccion;
use App\Http\Controllers\TcController;
use App\Models\TcArchivo;
use Illuminate\Http\Request;
use Prophecy\Doubler\Generator\Node\ReturnTypeNode;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\TcInbox;
use App\Models\TcOutbox;
use App\Models\TcPersona;
use App\Models\TcVia;
use App\Models\Unidad;
use PHPUnit\Framework\MockObject\Stub\ReturnReference;
use Illuminate\Support\Str;
use Storage;

use function PHPUnit\Framework\returnSelf;

class TcInboxController extends Controller
{
    public function index()
    {
        $funcionario = TcPersona::where('ci', Auth::user()->ci)->first();
        // return $funcionario;
        $funcionario_id = null;

        if ($funcionario) {
            $funcionario_id = $funcionario->id;
            if (!$funcionario_id) {
                return redirect()->back()->with(['message' => 'Falta tu código de funcionario contáctate con sistema para solucionarlo por favor.', 'alert-type' => 'error']);
            }
            
        }       
        return view('correspondencia.inbox.browse', compact('funcionario_id'));
    }


    public function derivacion_list($funcionario_id, $type){
        
        $paginate = request('paginate') ?? 10;
        $search = request('search') ?? null;
        
        switch ($type) {
            case 'pendientes':
                $derivaciones = TcInbox::whereHas('outbox', function($q){
                                        $q->where('urgent', 0)->whereNotIn('estado_id', [4, 6]);
                                    })->where('transferred', 0)->where('people_id_para', $funcionario_id)
                                    ->where('ok', '!=', 'ARCHIVADO')
                                    ->where(function($query) use ($search){
                                        if($search){
                                            $query->OrwhereHas('outbox', function($query) use($search){
                                                $query->whereRaw("(hr like '%$search%' or cite like '%$search%' or remitente like '%$search%' or referencia like '%$search%')");
                                            })
                                            ->OrWhereRaw("id = '$search'");
                                        }
                                    })
                                    ->orderBy('id', 'DESC')->paginate($paginate);
                                    // dd($derivaciones);

                                    
                                    // dd(TcInbox::with(['outbox'])->get());

                                    // $data = DB::connection('siscor')->table('derivations')->get();
                                    // dd($data);
                return view('correspondencia.inbox.pendientes', compact('derivaciones'));
                break;
            case 'urgentes':
                // dd($type);

                $derivaciones = TcInbox::whereHas('outbox', function($q){
                                        $q->where('urgent', 1)->whereNotIn('estado_id', [4, 6]);
                                    })->where('transferred', 0)->where('people_id_para', $funcionario_id)
                                    ->where('ok', '!=', 'ARCHIVADO')
                                    ->where(function($query) use ($search){
                                        if($search){
                                            $query->OrwhereHas('outbox', function($query) use($search){
                                                $query->whereRaw("(hr like '%$search%' or cite like '%$search%' or remitente like '%$search%' or referencia like '%$search%')");
                                            })
                                            ->OrWhereRaw("id = '$search'");
                                        }
                                    })
                                    ->orderBy('id', 'DESC')->paginate($paginate);

                                    // dd($derivaciones);
                return view('correspondencia.inbox.urgentes', compact('derivaciones'));
                break;
            case 'archivados':
                    $derivaciones = TcInbox::where('transferred', 0)->where('people_id_para', $funcionario_id)
                                        ->where('ok', 'ARCHIVADO')
                                        ->where(function($query) use ($search){
                                            if($search){
                                                $query->OrwhereHas('outbox', function($query) use($search){
                                                    $query->whereRaw("(hr like '%$search%' or cite like '%$search%' or remitente like '%$search%' or referencia like '%$search%')");
                                                })
                                                ->OrWhereRaw("id = '$search'");
                                            }
                                        })
                                        ->orderBy('id', 'DESC')->paginate($paginate);
                    return view('correspondencia.inbox.archivados', compact('derivaciones'));
                    break;
        }
    }

    
    public function show($id)
    {
        // return $id;
        // return TcOutbox::with(['archivos'])->get();
        try {
            $derivacion =  TcInbox::where('id',$id)->first();    
            // return $derivacion;
    
            $derivacion->visto = Carbon::now();
            $derivacion->save();              
            $data = TcOutbox::with(['entity', 'estado', 'archivos', 'inbox' => function($q){
                $q->where('deleted_at',null);
                            }])
                            ->where('id', $derivacion->entrada_id)
                            ->where('deleted_at', NULL)
                            ->first();
        
            // return $data;
                        
            $ok = date("d-m-Y", strtotime($data->created_at));
            
            $origen = '';
            $destino = NULL;
            if($data->tipo == 'I'){
                $direccion = Direccion::where('id', $data->direccion_id_remitente)->first();
                $unidad = Unidad::where('id', $data->direccion_id_remitente)->first();
                if($direccion){
                    $origen = $direccion->nombre;
                }
                if ($unidad) {
                    $origen = $unidad->nombre;
                }
            }
            return view('correspondencia.inbox.read', compact('data', 'origen','derivacion', ));
        } catch (\Throwable $th) {
            // return 1;
            //  dd($th);
            return redirect()->route('voyager.dashboard');
        }
    }


    public function derivacion_archivar(Request $request){
        $getPeople = new TcController();
        if(!$getPeople->isset())
        {
            return redirect()->route('outbox.index')->with(['message' => 'Se encuentra sin contrato activo', 'alert-type' => 'error']);
        }
        try {
            TcInbox::where('id',$request->derivacion_id)->update(['ok'=>'ARCHIVADO']);
            return redirect()->route('inbox.index')->with(['message' => 'Correspondencia archivada exitosamente.', 'alert-type' => 'success']);
        } catch (\Throwable $th) {
            // dd($th);
            return redirect()->route('inbox.index')->with(['message' => 'Ocurrio un error.', 'alert-type' => 'error']);
        }
    }


    public function bandejaDerivationDelete(Request $request)
    {
        $getPeople = new TcController();
        if(!$getPeople->isset())
        {
            return redirect()->route('outbox.index')->with(['message' => 'Se encuentra sin contrato activo', 'alert-type' => 'error']);
        }

        DB::beginTransaction();
        try {
            $ok = TcInbox::where('deleted_at', null)->where('visto', null)->where('entrada_id', $request->entrada_id)->where('id',$request->id)->first();
            $ok->update(['deleted_at'=> Carbon::now()]);
            // return $ok->parent_id;

            $data = TcInbox::where('deleted_at', null)->where('entrada_id', $request->entrada_id)->where('parent_id', $ok->parent_id)->get();
            // return count($data);
            if(count($data)==0)
            {
                TcInbox::where('deleted_at', null)->where('id', $ok->parent_id)->where('entrada_id', $request->entrada_id)->update(['derivation'=>0, 'ok'=>'NO']);
            }
            DB::commit();
            // window.location = "{{ url('admin/bandeja') }}/"+id;

            return redirect('admin/inbox/'.$ok->parent_id)->with(['message' => 'Derivación anulada exitosamente.', 'alert-type' => 'success']);
        } catch (\Throwable $th) {
            DB::rollBack();
            return redirect('admin/inbox/'.$ok->parent_id)->with(['message' => 'Ocurrio un error.', 'alert-type' => 'error']);
        }
    }


    public function store_derivacion(Request $request){
        $getPeople = new TcController();
        if(!$getPeople->isset())
        {
            return redirect()->route('outbox.index')->with(['message' => 'Se encuentra sin contrato activo', 'alert-type' => 'error']);
        }
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
                    $redirect = $request->redirect ?? 'inbox.index';
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
                // dd($th);
                return 0;
            
                return redirect()->route($redirect)->with(['message' => 'Ocurrio un error.', 'alert-type' => 'error']);
            }
        }


        public function add_derivacion($funcionario, $request, $rechazo = NULL, $rde = null){
            $getPeople = new TcController();
            if(!$getPeople->isset())
            {
                return redirect()->route('outbox.index')->with(['message' => 'Se encuentra sin contrato activo', 'alert-type' => 'error']);
            }
            $persona = TcPersona::where('ci', Auth::user()->ci)->first();
    
            $vias = TcVia::where('entrada_id',$request->id)->where('deleted_at', null)->get();
            
            // dd($funcionario->id_funcionario);
            $cant = count(TcInbox::where('entrada_id',$request->id)->where('via',1)->where('deleted_at', null)->get());
            // dd($vias);
            if($cant == 0)
            {
                foreach($vias as $data)
                {   
                    // dd($data->people_id);
                    $viafuncionario = $this->getPeople($data->people_id);
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


        public function store_file(Request $request){
            $getPeople = new TcController();
            if(!$getPeople->isset())
            {
                return redirect()->route('outbox.index')->with(['message' => 'Se encuentra sin contrato activo', 'alert-type' => 'error']);
            }
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



    
}
