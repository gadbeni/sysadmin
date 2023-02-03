<?php

namespace App\Http\Controllers;

use App\Models\TcPersona;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class TcController extends Controller
{
    // static function getIdDireccionInfo($direccion_id) {
    //     try {
    //         return DB::connection('mamore')->table('direcciones')
    //                     ->where('id', $direccion_id)
    //                     ->select('*')
    //                     ->first();
    //     } catch (\Throwable $th) {
    //         return null;
    //     }
    // }

    // static function getIdUnidadInfo($unidad_id) {
    //     try {
    //         return DB::connection('mamore')->table('unidades')
    //                     ->where('id', $unidad_id)
    //                     ->select('*')
    //                     ->first();
            
    //     } catch (\Throwable $th) {
    //         return null;
    //     }
    // }


    public function getPeoplesDerivacion(Request $request){
        // $persona = Persona::where('user_id', Auth::user()->id)->first();
        $persona = TcPersona::where('ci', Auth::user()->id)->first();
       
        $search = $request->search;
        $type = $request->type;
        $int_ext = $request->externo; //para saber si buscara funcionario interno u externo
        $funcionarios = [];
        if (!$search && $type > 0)
        {
            $funcionarios = DB::table('people as p')
                                        ->join('contracts as c', 'c.person_id', 'p.id')
                                        ->where('c.status','firmado')
                                        ->where('c.deleted_at', null)
                                        ->where('p.deleted_at', null)
                                        ->where('p.id',$type)
                                        ->select(
                                            'p.id',
                                            DB::raw("upper(CONCAT(p.first_name, ' ', p.last_name)) as text"),
                                            'p.first_name', 'last_name',
                                            'p.ci',
                                        )
                                        ->get();
            if(count($funcionarios)==0)
            {
                // $funcionarios = DB::table('siscor2021.people_exts as s')
                $funcionarios = DB::table('siscor_v2.people_exts as s')
                    ->join('sysadmin.people as m', 'm.id', '=', 's.person_id')
                    ->select(
                        'm.id',
                        DB::raw("CONCAT(m.first_name, ' ', m.last_name) as text"),
                        'm.first_name', 'm.last_name',
                        'm.ci',
                    )
                    ->where('s.person_id', $type)
                    // ->whereRaw('(m.ci like "%' .$search . '%" or '.DB::raw("CONCAT(m.first_name, ' ', m.last_name)").' like "%' .$search . '%")')
                    // ->groupBy('text')
                    ->get();
            }
        }
        else
        {
            if($int_ext==1)
            {
                $funcionarios = DB::connection('mamore')->table('people as p')
                                        ->join('contracts as c', 'c.person_id', 'p.id')
                                        ->where('c.status','firmado')
                                        ->where('c.deleted_at', null)
                                        ->where('p.deleted_at', null)
                                        // ->where('p.id',$type)
                                        ->select(
                                            'p.id',
                                            DB::raw("upper(CONCAT(p.first_name, ' ', p.last_name)) as text"),
                                            'p.first_name', 'last_name',
                                            'p.ci',
                                        )
                                        ->whereRaw('(p.ci like "%' .$search . '%" or '.DB::raw("CONCAT(p.first_name, ' ', p.last_name)").' like "%' .$search . '%")')
                                        ->groupBy('text')
                                        ->limit(10)
                                        ->get();
            }
            else
            {
                // $funcionarios = DB::table('siscor2021.people_exts as s')
                $funcionarios = DB::table('siscor_v2.people_exts as s')
                ->join('sysadmin.people as m', 'm.id', '=', 's.person_id')
                ->where('s.status', 1)
                ->where('s.deleted_at', null)
                ->select(
                    'm.id',
                    DB::raw("CONCAT(m.first_name, ' ', m.last_name) as text"),
                    'm.first_name', 'm.last_name',
                    'm.ci',
                )
                ->whereRaw('(m.ci like "%' .$search . '%" or '.DB::raw("CONCAT(m.first_name, ' ', m.last_name)").' like "%' .$search . '%")')
                // ->groupBy('text')
                ->get();
            }      
        }
        return response()->json($funcionarios);
    }
}
