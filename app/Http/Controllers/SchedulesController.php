<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

// Models
use App\Models\Schedule;
use App\Models\ScheduleDetail;
use App\Models\ContractSchedule;
use App\Models\Contract;

class SchedulesController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function create(){
        return view('biometrics.schedules.edit-add');
    }

    public function store(Request $request){
        DB::beginTransaction();
        try {
            $schedule = Schedule::create([
                'name' => $request->name,
                'description' => $request->description,
                'user_id' => Auth::user()->id
            ]);

            for ($i=1; $i <= $request->days; $i++) { 
                ScheduleDetail::create([
                    'schedule_id' => $schedule->id,
                    'day' => $i,
                    'entry' => $request->entry_1,
                    'exit' => $request->exit_1
                ]);

                // Si el horario no es continuo
                if ($request->entry_2 && $request->exit_2) {
                    ScheduleDetail::create([
                        'schedule_id' => $schedule->id,
                        'day' => $i,
                        'entry' => $request->entry_2,
                        'exit' => $request->exit_2
                    ]);
                }
            }
            DB::commit();

            return redirect()->route('voyager.schedules.index')->with(['message' => 'Horario registrado correctamente.', 'alert-type' => 'success']);
        } catch (\Throwable $th) {
            DB::rollback();
            //throw $th;
            return redirect()->route('voyager.schedules.index')->with(['message' => 'Ocurrió un error', 'alert-type' => 'error']);
        }
    }

    public function edit($id){
        $schedule = Schedule::findOrFail($id);
        return view('biometrics.schedules.edit-add', compact('schedule'));
    }

    public function update($id, Request $request){
        DB::beginTransaction();
        try {

            $schedule = Schedule::findOrFail($id);
            $schedule->name = $request->name;
            $schedule->description = $request->description;
            $schedule->update();

            if ($request->days) {
                
                ScheduleDetail::where('schedule_id', $id)->delete();
                
                for ($i=1; $i <= $request->days; $i++) { 
                    ScheduleDetail::create([
                        'schedule_id' => $id,
                        'day' => $i,
                        'entry' => $request->entry_1,
                        'exit' => $request->exit_1
                    ]);
    
                    // Si el horario no es continuo
                    if ($request->entry_2 && $request->exit_2) {
                        ScheduleDetail::create([
                            'schedule_id' => $id,
                            'day' => $i,
                            'entry' => $request->entry_2,
                            'exit' => $request->exit_2
                        ]);
                    }
                }
            }
            DB::commit();
            return redirect()->route('voyager.schedules.index')->with(['message' => 'Horario editado correctamente.', 'alert-type' => 'success']);
        } catch (\Throwable $th) {
            DB::rollback();
            return redirect()->route('voyager.schedules.index')->with(['message' => 'Ocurrió un error', 'alert-type' => 'error']);
        }
    }

    public function assignments_index($id){
        $this->custom_authorize('browse_schedules-assignments');
        $schedule = Schedule::with(['contracts_schedules.contract.person', 'contracts_schedules.contract.jobs', 'contracts_schedules.contract.cargo'])->where('id', $id)->first();
        return view('schedules.assignments-browse', compact('schedule'));
    }

    public function assignments_create($id){
        $this->custom_authorize('add_schedules-assignments');
        return view('schedules.assignments-edit-add', compact('id'));
    }

    public function assignments_store($id, Request $request){
        if(!$request->contract_id){
            return redirect()->route('schedules.assignments.create', $id)->with(['message' => 'Debe seleccionar al menos un funcionario', 'alert-type' => 'error']);
        }
        DB::beginTransaction();
        try {
            foreach ($request->contract_id as $item) {
                $contract = Contract::findOrFail($item);
                ContractSchedule::create([
                    'user_id' => Auth::user()->id,
                    'schedule_id' => $id,
                    'contract_id' => $item,
                    'start' => $contract->start,
                    // * Si es un contrato permanente se pone como fin el 2030
                    'finish' => $contract->finish ?? '2030-12-30'
                ]);
            }
            DB::commit();
            return redirect()->route('schedules.assignments', $id)->with(['message' => 'Horario asignado correctamente.', 'alert-type' => 'success']);
        } catch (\Throwable $th) {
            DB::rollback();
            return redirect()->route('schedules.assignments', $id)->with(['message' => 'Ocurrió un error', 'alert-type' => 'error']);
        }
    }

    public function assignments_delete($id, Request $request){
        try {
            ContractSchedule::where('id', $id)->delete();
            return redirect()->to($_SERVER['HTTP_REFERER'])->with(['message' => 'Horario asignado correctamente.', 'alert-type' => 'success']);
        } catch (\Throwable $th) {
            return redirect()->to($_SERVER['HTTP_REFERER'])->with(['message' => 'Ocurrió un error', 'alert-type' => 'error']);
        }
    }
}
