<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

// Models
use App\Models\Program;

class ProgramsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    public function list(){
        $this->custom_authorize('browse_programs');
        $search = request('search') ?? null;
        $direccion_administrativa_id = request('direccion_administrativa_id') ?? null;
        $procedure_type_id = request('procedure_type_id') ?? null;
        $year = request('year') ?? null;
        $paginate = request('paginate') ?? 10;
        $data = Program::with(['direccion_administrativa', 'unidad_administrativa', 'procedure_type', 'direcciones_administrativas.unidades_administrativas'])
                    ->whereRaw($direccion_administrativa_id ? "direccion_administrativa_id = ".$direccion_administrativa_id : 1)
                    ->whereRaw($procedure_type_id ? "procedure_type_id = ".$procedure_type_id : 1)
                    ->whereRaw($year ? "year = '".$year."'" : 1)
                    ->where(function($query) use ($search){
                        if($search){
                            $query->OrwhereHas('direccion_administrativa', function($query) use($search){
                                $query->whereRaw("nombre like '%$search%'");
                            })
                            ->OrwhereHas('procedure_type', function($query) use($search){
                                $query->whereRaw("name like '%$search%'");
                            })
                            ->OrwhereHas('direcciones_administrativas', function($query) use($search){
                                $query->whereRaw("nombre like '%$search%'");
                            })
                            ->OrWhereRaw("name = '$search'")
                            ->orWhereRaw("class like '%$search%'")
                            ->orWhereRaw("number like '%$search%'")
                            ->orWhereRaw("programatic_category like '%$search%'")
                            ->orWhereRaw("amount like '%$search%'")
                            ->orWhereRaw("year like '%$search%'");
                        }
                    })
                    ->orderBy('id', 'DESC')->paginate($paginate);
        return view('vendor.voyager.programs.list', compact('data', 'search'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        DB::beginTransaction();
        try {
            $program = Program::create([
                'procedure_type_id' => $request->procedure_type_id,
                'name' => strtoupper($request->name),
                'description' => $request->description,
                'class' => strtoupper($request->class),
                'number' => $request->number,
                'programatic_category' => $request->programatic_category,
                'amount' => $request->amount,
                'year' => $request->year
            ]);
            $program->direcciones_administrativas()->sync($request->direccion_id);
            DB::commit();
            return redirect()->route('voyager.programs.index')->with(['message' => 'Programa registrado exitosamente', 'alert-type' => 'success']);
        } catch (\Throwable $th) {
            DB::rollback();
            return redirect()->route('voyager.programs.index')->with(['message' => 'Ocurrió un error', 'alert-type' => 'error']);
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
        //
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
        DB::beginTransaction();
        try {
            $program = Program::find($id);
            $program->procedure_type_id = $request->procedure_type_id;
            $program->name = strtoupper($request->name);
            $program->description = $request->description;
            $program->class = strtoupper($request->class);
            $program->number = $request->number;
            $program->programatic_category = $request->programatic_category;
            $program->amount = $request->amount;
            $program->year = $request->year;
            $program->direcciones_administrativas()->sync($request->direccion_id);
            $program->update();
            DB::commit();
            return redirect()->route('voyager.programs.index')->with(['message' => 'Programa editado exitosamente', 'alert-type' => 'success']);
        } catch (\Throwable $th) {
            DB::rollback();
            dd($th);
            return redirect()->route('voyager.programs.index')->with(['message' => 'Ocurrió un error', 'alert-type' => 'error']);
        }
    }

    public function restrict(Request $request){
        try {
            $program = Program::find($request->id);
            $program->unidad_administrativa_id = $request->unidad_administrativa_id;
            $program->update();
            return redirect()->route('voyager.programs.index')->with(['message' => 'Programa restringido exitosamente', 'alert-type' => 'success']);
        } catch (\Throwable $th) {
            //throw $th;
            return redirect()->route('voyager.programs.index')->with(['message' => 'Ocurrió un error', 'alert-type' => 'error']);
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
        //
    }
}
