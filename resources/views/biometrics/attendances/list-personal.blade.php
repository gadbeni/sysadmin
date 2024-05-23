@if ($contracts_schedules->count())
    <div class="col-md-12">
        <div class="table-responsive">
            <table id="dataTable" class="table table-bordered table-hover">
                <thead>
                    <tr>
                        <th>N&deg;</th>
                        <th>Fecha</th>
                        <th>Marcaciones</th>
                        <th>Atrasos</th>
                        <th>Faltas</th>
                        <th>Abandonos</th>
                        <th>Días de <br> descuentos</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $days = array('', 'Lunes', 'Martes', 'Miercoles', 'Jueves', 'Viernes', 'Sábado', 'Domingo');
                        $months = array('', 'Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic');
                        
                        $cont = 1;
                        $days_faults = 0;
                        $days_enabled = getDiasHabiles($start, $finish, $holidays_array);
                    @endphp
                    @foreach ($days_enabled as $day_enabled)
                        <tr>
                            <td>{{ $cont }}</td>
                            <td>{{ $days[date('N', strtotime($day_enabled))] }} {{ date('d', strtotime($day_enabled)) }} de {{ $months[intval(date('m', strtotime($day_enabled)))] }}</td>
                            <td>
                                @php
                                    $recorded_schedules = [];
                                    $delay = 0;
                                    $abandonment = 0;
                                    $recordes = null;
                                    $attendance = $attendances->where('fecha', $day_enabled);
                                    if($attendance->count()){
                                        $contracts_schedule_date = $contracts_schedules->where('start', '<=', $day_enabled)->where('finish', '>=', $day_enabled)->first();
                                        $recorded_schedules = recorded_schedules($attendance, $contracts_schedule_date, $day_enabled);
                                    }
                                    $faults_entry = 0;
                                    $faults_abandonment = 0;
                                    $faults_half_day = 0;
                                @endphp
                                @forelse ($recorded_schedules as $record)
                                    @if ($record['entry_permit'])
                                        <a href="#" class="btn-show-permit text-success" data-permit='@json($record['entry_permit'])' data-toggle="modal" data-target="#show-permit-modal">{{ Str::upper($record['entry_permit']->attendance_permit->category == 1 ? 'licencia' : 'comisión') }}</a>
                                    @else
                                        <a href="#">{{ $record['entry_record'] ? $record['entry_record'] : 'No registardo' }}</a>
                                    @endif
                                    -
                                    @if ($record['exit_permit'])
                                        <a href="#" class="btn-show-permit text-success" data-permit='@json($record['exit_permit'])' data-toggle="modal" data-target="#show-permit-modal">{{ Str::upper($record['exit_permit']->attendance_permit->category == 1 ? 'licencia' : 'comisión') }}</a>
                                    @else
                                        <a href="#">{{ $record['exit_record'] ? $record['exit_record'] : 'No registardo' }}</a>
                                    @endif
                                    @php
                                        $delay += $record['delay'];
                                        $abandonment += $record['abandonment'];
                                    @endphp
                                @empty
                                    @php
                                        // Obtener si tiene permiso
                                    $permit = App\Models\AttendancePermitContract::with(['attendance_permit.type'])
                                                ->where('contract_id', $contracts_schedule_date->contract_id)
                                                ->whereHas('attendance_permit', function($q) use($day_enabled){
                                                    $q->where('start', '<=', $day_enabled)->where('finish', '>=', $day_enabled);
                                                })->first();
                                    @endphp
                                    @if ($permit)
                                        <a href="#" class="btn-show-permit text-success" data-permit='@json($permit)' data-toggle="modal" data-target="#show-permit-modal">{{ Str::upper($permit->attendance_permit->category == 1 ? 'licencia' : 'comisión') }}</a>
                                    @else
                                        <a href="#">FALTA</a>
                                        @php
                                            $faults_entry += 2;
                                        @endphp
                                    @endif
                                @endforelse
                                @php
                                    $recordes = calculate_recordes($recorded_schedules, $delay, $abandonment);
                                    // Mejorar el cálculo en caso de horario discontinuo
                                    $faults_entry += $recordes->faults_entry;
                                    $faults_abandonment += $recordes->faults_abandonment;
                                    $faults_half_day += $recordes->faults_half_day;
                                @endphp
                            </td>
                            <td class="text-right">{{ $delay }}</td>
                            <td class="text-right">{{ $faults_entry }}</td>
                            <td class="text-right">
                                @if($abandonment)
                                    <b class="text-danger">ABANDONO</b>
                                @endif
                            </td>
                            <td class="text-right">{{ $faults_entry + $faults_abandonment + $faults_half_day }}</td>
                        </tr>
                        @php
                            $cont++;
                            $days_faults += $faults_entry + $faults_abandonment + $faults_half_day;
                        @endphp
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="6" class="text-right"><b>TOTAL</b></td>
                        <td class="text-right"><b>{{ $days_faults + (count($days_enabled) - ($cont -1)) }}</b></td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
@else
    <h4 class="text-center text-danger">No tiene horarios asignados en ese intervalo de fechas</h4>
@endif

<script>
    $(document).ready(function(){
        $('.btn-show-permit').click(function(){
            let permit = $(this).data('permit');
            $('.div-field').fadeOut('fast');
            $(`.div-${permit.attendance_permit.category}`).fadeIn('fast');
            $('#label-category').text(permit.attendance_permit.category == 1 ? 'Licencia' : 'Comisión');
            $('#label-status').text(permit.attendance_permit.status.charAt(0).toUpperCase() + permit.attendance_permit.status.slice(1));
            $('#label-type').text(permit.attendance_permit.type ? permit.attendance_permit.type.name : 'No definida');
            $('#label-date').text(moment(permit.attendance_permit.date).format('DD [de] MMMM [de] YYYY'));
            $('#label-start').text(moment(permit.attendance_permit.start).format('dddd[,] DD [de] MMMM [de] YYYY'));
            $('#label-finish').text(moment(permit.attendance_permit.finish).format('dddd[,] DD [de] MMMM [de] YYYY'));
            $('#label-observations').text(permit.attendance_permit.observations ? permit.attendance_permit.observations : 'Ninguna');
            $('#label-purpose').text(permit.attendance_permit.purpose ? permit.attendance_permit.purpose : 'No definido');
            $('#label-justification').text(permit.attendance_permit.justification ? permit.attendance_permit.justification : 'Ninguna');

            // Mostrar hora
            if(permit.attendance_permit.start == permit.attendance_permit.finish){
                $('.div-finish').fadeOut('fast', () => {
                    $('.div-time').fadeIn('fast');
                });
                $('.div-start label small').text('Fecha de '+(permit.attendance_permit.category == 1 ? 'licencia' : 'comisión'));
                $('#label-time').text(permit.attendance_permit.time_start == permit.attendance_permit.time_start ? moment('2024-01-01 '+permit.attendance_permit.time_start).format('HH:mm') : moment('2024-01-01 '+permit.attendance_permit.time_start).format('HH:mm')+' - '+moment('2024-01-01 '+permit.attendance_permit.time_finish).format('HH:mm'));
            }else{
                $('.div-time').fadeOut('fast', () => {
                    $('.div-finish').fadeIn('fast');
                });
                $('.div-start label small').text('Fecha de inicio');
            }
        });
        
    });
</script>
    