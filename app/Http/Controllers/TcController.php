<?php

namespace App\Http\Controllers;

use App\Models\Direccion;
use App\Models\Person;
use App\Models\TcOutbox;
use App\Models\TcPersona;
use App\Models\TcPersonaExt;
use App\Models\Unidad;
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



    //  la Obetener todos los funcionarios del mamore y de la base de datos siscor "como personas externas"
    public function getPeoplesDerivacion(Request $request){
       
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
                                        // ->whereRaw('(p.ci like "%' .$search . '%" or '.DB::raw("CONCAT(p.first_name, ' ', p.last_name)").' like "%' .$search . '%")')
                                        // ->groupBy('text')
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
                $funcionarios = DB::table('people as p')
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
                                        ->limit(5)
                                        ->get();
            }
            else
            {

                $funcionarios = DB::connection('siscor')->table('people_exts as s')
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
                    ->limit(5)
                    ->get();
            }      
        }
        return response()->json($funcionarios);
    }


    // Obtencion de Funcionarios
    public function getPeople($id)
    {        
        // return $id;
        $funcionario = 'null';
        $funcionario = DB::table('people as p')
            ->leftJoin('contracts as c', 'p.id', 'c.person_id')
            // ->leftJoin('contracts as c', 'p.id', 'c.person_id')
            ->leftJoin('direcciones as d', 'd.id', 'c.direccion_administrativa_id')
            ->leftJoin('unidades as u', 'u.id', 'c.unidad_administrativa_id')
            ->leftJoin('jobs as j', 'j.id', 'c.job_id')
            ->where('c.status', 'firmado')
            ->where('c.deleted_at', null)
            ->where('p.id', $id)
            ->where('p.deleted_at', null)
            ->select('p.id as id_funcionario', 'p.ci as N_Carnet', 'c.cargo_id', 'c.job_id', 'j.name as cargo',
                DB::raw("CONCAT(p.first_name, ' ', p.last_name) as nombre"), 'c.direccion_administrativa_id as id_direccion', 'd.nombre as direccion',
                    'c.unidad_administrativa_id as id_unidad', 'u.nombre as unidad')
            ->first();

        if($funcionario && $funcionario->cargo_id != NULL)
        {
            // return "dentro";
            $cargo = DB::connection('mysqlgobe')->table('cargo')
                ->where('id',$funcionario->cargo_id)
                ->select('*')
                ->first();
    
            $funcionario->cargo=$cargo->Descripcion;
        }

        if(!$funcionario)
        {
                $funcionario = TcPersonaExt::where('person_id', $id)
                    ->where('status',1)
                    ->select('direccion_id as id_direccion', 'unidad_id as id_unidad', 'cargo', 'person_id as id_funcionario')
                    ->first();
                if($funcionario)
                {
                    $funcionario->unidad = Unidad::where('id', $funcionario->id_unidad)->first()->nombre;
                    $funcionario->direccion= Direccion::where('id', $funcionario->id_direccion)->first()->nombre;
                    $funcionario->nombre = Person::where('id', $funcionario->id_funcionario)->select(DB::raw("CONCAT(first_name, ' ', last_name) as nombre"))->first()->nombre;
                }            
        }
        if(!$funcionario)
        {
            return "Error";
        }
        return $funcionario;
    }


    // para saber si el cite ya se encuentra registrado 
    public function getCite($id,$cite)
    {
       $aux ='';
       $i =0;
       $cite = strtoupper($cite);

       while($i < strlen($cite))
       {
           if($cite[$i]=='&')
           {
               $aux = $aux.'/';
           }
           else
           {
               $aux = $aux.$cite[$i];
           }
           $i++;
       }

       if($id == 1)
       {
           $ok = TcOutbox::where('cite', $aux)->where('deleted_at', null)->first();
       }
       else
       {
           $ok = TcOutbox::where('id', '!=', $id)->where('cite', $aux)->where('deleted_at', null)->first();
       }
       if($ok)
       {
           return 1;
       }
       else
       {
           return 0;
       }
    }

    // public function getPeopleSN($id)
    // {                
    //     $funcionario = DB::connection('mamore')->table('people as p')
    //         ->where('p.id', $id)
    //         ->where('p.deleted_at', null)
    //         ->select('p.id as id_funcionario', 'p.ci as N_Carnet', DB::raw("CONCAT(p.first_name, ' ', p.last_name) as nombre"))
    //         ->first();
    //     return $funcionario;
    // }
}
