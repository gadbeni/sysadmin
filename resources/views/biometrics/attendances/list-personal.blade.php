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
                        {{-- <th>Acciones</th> --}}
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
                                    <a href="#">{{ $record['entry_record'] ? $record['entry_record'] : 'No registardo' }}</a> - <a href="#">{{ $record['exit_record'] ? $record['exit_record'] : 'No registardo' }}</a> <br>
                                    @php
                                        $delay += $record['delay'];
                                        $abandonment += $record['abandonment'];
                                        $recordes = calculate_recordes($recorded_schedules, $record, $delay, $abandonment);
                                    @endphp
                                @empty
                                    <a href="#" data-toggle="modal" data-target="#attendance_permit-modal">FALTA</a>
                                @endforelse
                                @php
                                    if($recordes){
                                        $faults_entry = $recordes->faults_entry;
                                        $faults_abandonment = $recordes->faults_abandonment;
                                        $faults_half_day = $recordes->faults_half_day;
                                    }else{
                                        $faults_entry += 2;
                                        // $faults_entry++;
                                    }
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
                            {{-- <td></td> --}}
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
                        {{-- <td></td> --}}
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>

    {{-- Edita date modal --}}
    {{-- <div class="modal fade" tabindex="-1" id="store-modal" role="dialog">
        <div class="modal-dialog modal-primary">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title"><i class="voyager-calendar"></i> Manejo de registros</h4>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="period_id">Periodo</label>
                        <select name="period_id" id="select-period_id" class="form-control" required>
                            
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                    <input type="submit" class="btn btn-primary" value="Aceptar">
                </div>
            </div>
        </div>
    </div> --}}
@else
    <h4 class="text-center text-danger">No tiene horarios asignados en ese intervalo de fechas</h4>
@endif
    