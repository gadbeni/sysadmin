<form action="{{ route('attendances.absences.store') }}" id="form-attendances" class="form-submit" method="post">
    @csrf
    <div class="col-md-12">
        <div class="table-responsive">
            <table id="dataTable" class="table table-bordered table-hover">
                <thead>
                    <tr>
                        <th>N&deg;</th>
                        <th>Persona</th>
                        <th>Atrasos</th>
                        <th>Faltas</th>
                        <th>Abandonos</th>
                        <th>Días de <br> descuentos</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $days = array('', 'Lunes', 'Martes', 'Miercoles', 'Jueves', 'Viernes', 'Sábado', 'Domingo');
                        $months = array('', 'Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic');
    
                        // Variable para habilitar o deshabilitar el envío del formulario (si un funcionario no tiene horario asignado)
                        $form_send_status = true;
                        $cont = 1;
                        $total_faults = 0;
                        $total_abandonment = 0;
                        $total_days = 0;
                        $days_enabled = getDiasHabiles($start, $finish, $holidays_array);
                    @endphp
                    @forelse ($contracts as $item)
                        <tr>
                            @php
                                $ci = str_replace(' ', '-', $item->person->ci);
                                $ci = explode('-', $ci)[0];
                                $attendances = DB::connection('sia')
                                                    ->table('Asistencia')
                                                    ->where('IdPersona', $ci)
                                                    ->whereDate('Fecha', '>=', $start)
                                                    ->whereDate('Fecha', '<=', $finish)
                                                    ->select(DB::raw('IdPersona as ci'), DB::raw('Fecha as fecha'), DB::raw('Hora as hora'))
                                                    ->get();
                                $contracts_schedules = App\Models\ContractSchedule::with(['schedule.details'])->where('contract_id', $item->id)
                                                        ->whereRaw('DATE_FORMAT(start, "%Y%m") <= "'.date('Ym', strtotime($start)).'" and (DATE_FORMAT(finish, "%Y%m") >= "'.date('Ym', strtotime($finish)).'" or finish is null)')
                                                        ->get();
                                $absences = 0;
                                $array_faults = collect();
                                $days_faults = 0;
                                $count_days = 0;
                                $count_delay = 0;
                                $count_abandonment = 0;
                                if($contracts_schedules->count()){
                                    $delay = 0;
                                    $abandonment = 0;
                                    foreach($attendances->groupBy('fecha') as $date => $attendance){
                                        $contracts_schedule_date = $contracts_schedules->where('start', '<=', $date)->where('finish', '>=', $date)->first();
                                        $recorded_schedules = recorded_schedules($attendance, $contracts_schedule_date, $date);
                                        
                                        // $delay = 0;
                                        // $abandonment = 0;

                                        foreach($recorded_schedules as $record){
                                            $delay += $record['delay'];
                                            $abandonment += $record['abandonment'];
                                        }
                                        $faults = calculate_recordes($recorded_schedules, $delay, $abandonment);
                                        $array_faults->push($faults);
                                        $count_days++;
                                    }
                                    $count_delay += $delay;
                                    $count_abandonment += $abandonment;

                                    // Recorer los días hábiles no marcados
                                    $days_not_recorded = [];
                                    foreach ($days_enabled as $day_enabled) {
                                        $day_recorded = false;
                                        foreach ($attendances->groupBy('fecha') as $date => $attendance_date) {
                                            if($day_enabled == date('Y-m-d', strtotime($date))){
                                                $day_recorded = true;
                                            }
                                        }
                                        // Si no está en la lista de marcaciones se lo pone en la lista de días no marcados
                                        if(!$day_recorded){
                                            array_push($days_not_recorded, $day_enabled);
                                        }
                                    }
                                    // Recorrer los días para buscar si tienen permisos
                                    $days_permit = 0;
                                    foreach ($days_not_recorded as $day_not_recorded) {
                                        $permit = App\Models\AttendancePermitContract::with(['attendance_permit.type'])
                                                    ->where('contract_id', $contracts_schedule_date->contract_id)
                                                    ->whereHas('attendance_permit', function($q) use($day_not_recorded){
                                                        $q->where('start', '<=', $day_not_recorded)->where('finish', '>=', $day_not_recorded)
                                                        // Solo para permisos de jornada completa
                                                        ->whereRaw('time_start is NULL and time_finish is NULL');
                                                    })->first();
                                        if($permit){
                                            $days_permit++;
                                        }
                                    }
    
                                    $absences = count($days_enabled) - $attendances->groupBy('fecha')->count() - $days_permit;
                                    $days_faults = $array_faults->sum('faults_entry') + $array_faults->sum('faults_abandonment') + $array_faults->sum('faults_half_day');
                                }

                                // Si el nivel del funcionario es <= 7 no se toma en cuenta su marcación
                                if($item->job){
                                    if($item->job->level <= 7){
                                        $days_faults = 0;
                                        $absences = 0;
                                    }
                                }
                            @endphp
                            <td>{{ $cont }}</td>
                            <td title="{{ $item->absences->count() ? 'Último perido generado '.$item->absences->sortByDesc('period_id')->first()->period->name : '' }}">
                                <span class="{{ $contracts_schedules->count() == 0 ? 'text-danger' : '' }}">{{ $item->person->first_name }} {{ $item->person->last_name }}</span> <br>
                                <label class="label label-default">{{ date('d/m/Y', strtotime($item->start)) }} - {{ $item->finish ? date('d/m/Y', strtotime($item->finish)) : 'Indefinido' }}</label>
                            </td>
                            <td class="text-right">{{ $count_delay }}</td>
                            <td class="text-right">{{ $array_faults->sum('faults_entry') + ($absences *2) }}</td>
                            <td class="text-right">{{ $count_abandonment }}</td>
                            <td class="text-right">
                                {{ $days_faults + ($absences *2) }}
                                <input type="hidden" name="quantity[]" value="{{ $days_faults + ($absences *2) }}">
                                <input type="hidden" name="contract_id[]" value="{{ $item->id }}">
                            </td>
                            <td class="no-sort no-click bread-actions text-right">
                                <a href="#" title="Ver" class="btn btn-sm btn-warning view">
                                    <i class="voyager-eye"></i> <span class="hidden-xs hidden-sm">Ver</span>
                                </a>
                                <a href="#" title="Editar" data-toggle="modal" data-target="#edit-modal" data-absences='@json($item->absences)' data-contract='@json($item)' class="btn btn-sm btn-info">
                                    <i class="voyager-edit"></i> <span class="hidden-xs hidden-sm">Editar</span>
                                </a>
                            </td>
                        </tr>
                        @php
                            $cont++;
                            $total_faults += $array_faults->sum('faults_entry') + ($absences *2);
                            $total_abandonment += $count_abandonment;
                            $total_days += $days_faults + ($absences *2);
                        @endphp
                    @empty
                        
                    @endforelse
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="3" class="text-right"><b>TOTAL</b></td>
                        <td class="text-right">{{ $total_faults }}</td>
                        <td class="text-right">{{ $total_abandonment }}</td>
                        <td class="text-right">{{ $total_days }}</td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
    
    <div class="col-md-12 text-right" style="margin-bottom: 20px">
        <button type="button" @if(!$form_send_status) disabled title="Existe un funcionario que no tiene horario asignado" @endif class="btn btn-primary btn-register" data-toggle="modal" data-target="#store-modal"><i class="voyager-calendar"></i> Registrar</button>
    </div>
    
    <div class="modal fade" tabindex="-1" id="store-modal" role="dialog">
        <div class="modal-dialog modal-primary">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title"><i class="voyager-calendar"></i> Registrar faltas</h4>
                </div>
                <div class="modal-body">
                    {{-- <div class="panel panel-bordered" style="border-left: 5px solid #62A8Ea">
                        <div class="panel-body" style="padding: 10px">
                            <div class="col-md-12">
                                <p class="text-info">Si la marcación de algún funcionario ya fué generada para el periodo seleccionado no sufrirá cambios.</p>
                            </div>
                        </div>
                    </div> --}}
                    <div class="form-group">
                        <label for="period_id">Periodo</label>
                        <select name="period_id" id="select-period_id" class="form-control" required>
                            <option selected value="">Seleccione el periodo</option>
                            @foreach (App\Models\Period::where('deleted_at', NULL)->where('status', 1)->orderBy('name', 'DESC')->get() as $item)
                                <option value="{{ $item->id }}">{{ $item->name }}</option>
                            @endforeach
                        </select>
                        <input type="hidden" name="date_register">
                    </div>
                    <div class="form-group">
                        <label class="checkbox-inline"><input type="checkbox" name="accept" value="1" required>Aceptar y continuar, ésta operación no puede deshacer</label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary btn-submit">Aceptar</button>
                </div>
            </div>
        </div>
    </div>
</form>

<script>
    $(document).ready(function(){
        $('#select-period_id').select2({ dropdownParent: "#store-modal" });
        
        $('.btn-register').click(function(){
            $('#form-attendances input[name="date_register"]').val($('#form-search input[name="finish"]').val());
        });

        $('.btn-info').click(function(){
            let absences = $(this).data('absences');
            let contract = $(this).data('contract');
            $('#form-edit').attr('action', "{{ url('admin/people') }}/"+contract.person_id+"/attendance/update");
            $('#form-edit input[name="contract_id"]').val(contract.id);
            if(absences.length){
                $('#form-edit input[name="start"]').attr('min', moment(absences[absences.length -1].date_register, "YYYY-MM-DD").add(1, 'days').format("YYYY-MM-DD"));
                $('#form-edit input[name="start"]').val(moment(absences[absences.length -1].date_register, "YYYY-MM-DD").add(1, 'days').format("YYYY-MM-DD"));
            }else{
                $('#form-edit input[name="start"]').removeAttr('min');
                $('#form-edit input[name="start"]').val('');
            }
        });
    });
</script>