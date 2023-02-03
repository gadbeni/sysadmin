<?php

namespace App\Http\Controllers;

use App\Models\Direccion;
use App\Models\TcArchivo;
use Illuminate\Http\Request;
use Prophecy\Doubler\Generator\Node\ReturnTypeNode;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\TcInbox;
use App\Models\TcOutbox;
use App\Models\TcPersona;
use App\Models\Unidad;
use PHPUnit\Framework\MockObject\Stub\ReturnReference;

class TcInboxController extends Controller
{
    public function index()
    {
        $funcionario = TcPersona::where('ci', Auth::user()->ci)->first();
        $funcionario_id = null;

        if ($funcionario) {
            $funcionario_id = $funcionario->id;
            if (!$funcionario_id) {
                return redirect()->back()->with(['message' => 'Falta tu código de funcionario contáctate con sistema para solucionarlo por favor.', 'alert-type' => 'error']);
            }
            
        }        

        // dd(TcInbox::all());
        // dd(TcInbox::with(['outbox'])->withTrashed()->get());

        // dd(DB::connection('siscor')->table('derivations as d')->join('entradas as e', 'e.id', 'd.entrada_id')->get());
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
        // return $request;
        try {
            TcInbox::where('id',$request->derivacion_id)->update(['ok'=>'ARCHIVADO']);
            return redirect()->route('inbox.index')->with(['message' => 'Correspondencia archivada exitosamente.', 'alert-type' => 'success']);
        } catch (\Throwable $th) {
            // dd($th);
            return redirect()->route('inbox.index')->with(['message' => 'Ocurrio un error.', 'alert-type' => 'error']);
        }
    }



    
}
