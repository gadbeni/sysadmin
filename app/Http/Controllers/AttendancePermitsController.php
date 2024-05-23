<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

// Models
use App\Models\AttendancePermit;
use App\Models\AttendancePermitContract;

class AttendancePermitsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->custom_authorize('browse_attendances-permits');
        return view('biometrics.attendances-permits.browse');
    }

    public function list(){
        $this->custom_authorize('browse_attendances-permits');
        $search = request('search') ?? null;
        $paginate = request('paginate') ?? 10;
        $data = AttendancePermit::with(['user', 'type', 'details'])
                    // ->where(function($query) use ($search){
                    //     if($search){
                    //         $query->OrwhereHas('subcategory', function($query) use($search){
                    //             $query->whereRaw("name like '%$search%'");
                    //         })
                    //         ->OrwhereHas('subcategory.category', function($query) use($search){
                    //             $query->whereRaw("name like '%$search%'");
                    //         })
                    //         ->OrwhereHas('assignments.person_asset.person', function($query) use($search){
                    //             $query->whereRaw("(first_name like '%$search%' or last_name like '%$search%' or ci like '%$search%' or phone like '%$search%' or CONCAT(first_name, ' ', last_name) like '%$search%')");
                    //         })
                    //         ->OrWhereRaw("id = '$search'")
                    //         ->orWhereRaw("code like '%$search%'")
                    //         ->orWhereRaw("code_siaf like '%$search%'")
                    //         ->orWhereRaw("code_internal like '%$search%'")
                    //         ->orWhereRaw("tags like '%$search%'")
                    //         ->orWhereRaw("description like '%$search%'")
                    //         ->orWhereRaw("observations like '%$search%'");
                    //     }
                    // })
                    ->orderBy('id', 'DESC')->paginate($paginate);
        return view('biometrics.attendances-permits.list', compact('data', 'search'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->custom_authorize('add_attendances-permits');
        $type = request('type');
        return view('biometrics.attendances-permits.edit-add', compact('type'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    public function store_personal(Request $request){
        if(!$request->contract_id){
            return response()->json(['error' => 1, 'message' => 'Debe seleccionar al menos un funcionario']);
        }
        DB::beginTransaction();
        try {
            $attendance_permit = AttendancePermit::create([
                'user_id' => Auth::user()->id,
                'attendance_permit_type_id' => is_numeric($request->attendance_permit_type_id) ? $request->attendance_permit_type_id : null,
                'category' => $request->category,
                'date' => $request->date,
                // Si es permiso especial
                'start' => $request->category == 3 ? $request->date_permit : $request->start,
                'finish' => $request->category == 3 ? $request->date_permit : $request->finish,
                'time_start' => $request->category == 3 ? $request->hour_permit : $request->time_start,
                'time_finish' => $request->category == 3 ? '23:59:59' : $request->time_finish,
                'purpose' => $request->purpose,
                'justification' => $request->justification,
                'type_transport' => $request->type_transport,
                // 'file' => $request->file,
                'observations' => $request->observations,
                'status' => 'firmado'
            ]);

            foreach ($request->contract_id as $contract_id) {
                AttendancePermitContract::create([
                    'attendance_permit_id' => $attendance_permit->id,
                    'contract_id' => $contract_id
                ]);
            }
            
            DB::commit();
            if ($request->ajax) {
                return response()->json(['success' => 1, 'message' => $request->category == 1 ? 'Licencia registrada' : 'Comisión registrada']);
            }
            return redirect()->route('attendances-permits.index')->with(['message' => 'Permiso registrado correctamente.', 'alert-type' => 'success']);
        } catch (\Throwable $th) {
            DB::rollback();
            if ($request->ajax) {
                return response()->json(['error' => 1]);
            }
            return redirect()->route('attendances-permits.index')->with(['message' => 'Ocurrió un error.', 'alert-type' => 'error']);
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
        $attendance_permits = AttendancePermit::with(['type', 'details.contract.person', 'details.contract.direccion_administrativa'])->where('id', $id)->first();
        return view('biometrics.attendances-permits.read', compact('attendance_permits'));
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
        //
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
