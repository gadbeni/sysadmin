<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use DataTables;

// Models
use App\Models\Spreadsheet;
use App\Models\ChecksPayment;
use App\Models\User;

class SpreadsheetsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('social-security.spreadsheets-browse');
    }

    public function list(){
        $data = Spreadsheet::with(['user'])->where('deleted_at', NULL)->get();
        return
            DataTables::of($data)
            ->addColumn('checkbox', function($row){
                return '<div><input type="checkbox" name="id[]" onclick="checkId()" value="'.$row->id.'" /></div>';
            })
            ->addColumn('details', function($row){
                $direccion_administrativa = DB::connection('mysqlgobe')->table('direccionadministrativa')->where('ID', $row->direccion_administrativa_id ?? 0)->first();
                $tipo_planilla = DB::connection('mysqlgobe')->table('tplanilla')->where('ID', $row->tipo_planilla_id ?? 0)->first();
                return ($direccion_administrativa ? $direccion_administrativa->NOMBRE : 'Desconocida').' <br> <b>'.($tipo_planilla ? $tipo_planilla->Nombre : 'Desconocida').'</b>';
            })
            ->addColumn('afp', function($row){
                return $row->afp_id == 1 ? 'Futuro' : 'Previsión';
            })
            ->addColumn('user', function($row){
                return User::find($row->user_id)->name;
            })
            ->addColumn('period', function($row){
                $monts = ['', 'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'];
                return $monts[intval($row->month)].'/'.$row->year;
            })
            ->addColumn('total', function($row){
                return number_format($row->total, 2, ',', '.');
            })
            ->addColumn('total_afp', function($row){
                return number_format($row->total_afp, 2, ',', '.');
            })
            ->addColumn('created_at', function($row){
                return date('d/m/Y H:i', strtotime($row->created_at)).'<br><small>'.Carbon::parse($row->created_at)->diffForHumans().'</small>';
            })
            ->addColumn('actions', function($row){
                $actions = '
                    <div class="no-sort no-click bread-actions text-right">
                        <a href="'.route('spreadsheets.edit', ['spreadsheet' => $row->id]).'" title="Editar" class="btn btn-sm btn-info edit">
                            <i class="voyager-edit"></i> <span class="hidden-xs hidden-sm">Editar</span>
                        </a>
                        <button type="button" onclick="deleteItem('."'".route('spreadsheets.destroy', ['spreadsheet' => $row->id])."'".')" data-toggle="modal" data-target="#delete-modal" title="Eliminar" class="btn btn-sm btn-danger edit">
                            <i class="voyager-trash"></i> <span class="hidden-xs hidden-sm">Borrar</span>
                        </button>
                    </div>';
                return $actions;
            })
            ->rawColumns(['checkbox', 'details', 'afp', 'period', 'user', 'created_at', 'actions'])
            ->make(true);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $type = 'create';
        $direccion_administrativa = DB::connection('mysqlgobe')->table('direccionadministrativa')
                                        ->where('Estado', 1)
                                        ->get();
        $tipo_planilla = DB::connection('mysqlgobe')->table('tplanilla')
                            ->where('Estado', 1)
                            ->get();
        return view('social-security.spreadsheets-edit-add', compact('type', 'direccion_administrativa', 'tipo_planilla'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // dd($request);
        try {
            Spreadsheet::create([
                'user_id' => Auth::user()->id,
                'direccion_administrativa_id' => $request->direccion_administrativa_id,
                'tipo_planilla_id'  => $request->tipo_planilla_id,
                'codigo_planilla'  => $request->codigo_planilla,
                'year'  => $request->year,
                'month' => str_pad($request->month, 2, "0", STR_PAD_LEFT),
                'people' => $request->people,
                'afp_id' => $request->afp_id,
                'total' => $request->total,
                'total_afp' => $request->total_afp
            ]);
            return redirect()->route('spreadsheets.index')->with(['message' => 'Planilla registrada correctamente.', 'alert-type' => 'success']);
        } catch (\Throwable $th) {
            env('APP_DEBUG') ? dd($th) : null;
            return redirect()->route('spreadsheets.index')->with(['message' => 'Ocurrió un error.', 'alert-type' => 'error']);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $data = Spreadsheet::findOrFail($id);
        $type = 'edit';
        $direccion_administrativa = DB::connection('mysqlgobe')->table('direccionadministrativa')
                                        ->where('Estado', 1)
                                        ->get();
        $tipo_planilla = DB::connection('mysqlgobe')->table('tplanilla')
                            ->where('Estado', 1)
                            ->get();
        return view('social-security.spreadsheets-edit-add', compact('type', 'direccion_administrativa', 'tipo_planilla', 'data'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        try {
            Spreadsheet::where('id', $id)->update([
                'direccion_administrativa_id' => $request->direccion_administrativa_id,
                'tipo_planilla_id'  => $request->tipo_planilla_id,
                'codigo_planilla'  => $request->codigo_planilla,
                'year'  => $request->year,
                'month' => $request->month,
                'people' => $request->people,
                'afp_id' => $request->afp_id,
                'total' => $request->total,
                'total_afp' => $request->total_afp
            ]);
            return redirect()->route('spreadsheets.index')->with(['message' => 'Planilla editada correctamente.', 'alert-type' => 'success']);
        } catch (\Throwable $th) {
            env('APP_DEBUG') ? dd($th) : null;
            return redirect()->route('spreadsheets.index')->with(['message' => 'Ocurrió un error.', 'alert-type' => 'error']);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            Spreadsheet::where('id', $id)->update([
                'deleted_at' => Carbon::now()
            ]);
            return redirect()->route('spreadsheets.index')->with(['message' => 'Planilla eliminada correctamente.', 'alert-type' => 'success']);
        } catch (\Throwable $th) {
            env('APP_DEBUG') ? dd($th) : null;
            return redirect()->route('spreadsheets.index')->with(['message' => 'Ocurrió un error.', 'alert-type' => 'error']);
        }
    }

    public function destroy_multiple(Request $request){
        try {
            foreach ($request->id as $id) {
                Spreadsheet::where('id', $id)->update([
                    'deleted_at' => Carbon::now()
                ]);
            }
            return redirect()->route('spreadsheets.index')->with(['message' => 'Planillas eliminadas correctamente.', 'alert-type' => 'success']);
        } catch (\Throwable $th) {
            env('APP_DEBUG') ? dd($th) : null;
            return redirect()->route('spreadsheets.index')->with(['message' => 'Ocurrió un error.', 'alert-type' => 'error']);
        }
    }
}
