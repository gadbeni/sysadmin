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
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $days = array('', 'Lunes', 'Martes', 'Miercoles', 'Jueves', 'Viernes', 'Sábado', 'Domingo');
                    $months = array('', 'Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic');
                    // Obtener los feriados del periodo de tiempo
                    $holidays = App\Models\Holiday::where('status', 1)
                                    ->whereRaw('CONCAT(LPAD(month,2,0),LPAD(day,2,0)) >= "'.date('md', strtotime($start)).'"' )
                                    ->whereRaw('CONCAT(LPAD(month,2,0),LPAD(day,2,0)) <= "'.date('md', strtotime($finish)).'"' )
                                    ->get();
                    $holidays_array = array();
                    foreach ($holidays as $item) {
                        array_push($holidays_array, ($item->year ?? date('Y')).'-'.str_pad($item->month, 2, "0", STR_PAD_LEFT).'-'.str_pad($item->day, 2, "0", STR_PAD_LEFT));
                    }
                    $contracts_schedules = App\Models\ContractSchedule::with(['schedule.details'])->where('contract_id', $contract->id)
                                                ->whereRaw('DATE_FORMAT(start, "%Y%m") <= "'.date('Ym', strtotime($start)).'" and (DATE_FORMAT(finish, "%Y%m") >= "'.date('Ym', strtotime($finish)).'" or finish is null)')
                                                ->get();
                    $cont = 1;
                    $days_faults = 0;
                    $days_enabled = getDiasHabiles($start, $finish, $holidays_array);
                @endphp
                @if ($contracts_schedules->count())
                    @forelse ($attendances->groupBy('fecha') as $date => $item)
                        <tr>
                            <td>{{ $cont }}</td>
                            <td>{{ $days[date('N', strtotime($date))] }} {{ date('d', strtotime($date)) }} de {{ $months[intval(date('m', strtotime($date)))] }}</td>
                            <td>
                                @php
                                    $contracts_schedule_date = $contracts_schedules->where('start', '<=', $date)->where('finish', '>=', $date)->first();
                                    $recorded_schedules = recorded_schedules($item, $contracts_schedule_date, $date);
                                    
                                    $delay = 0;
                                    $abandonment = 0;
                                @endphp
                                
                                @foreach ($recorded_schedules as $record)
                                    {{ $record['entry_record'] ? $record['entry_record'] : 'No registardo' }} - {{ $record['exit_record'] ? $record['exit_record'] : 'No registardo' }} <br>
                                    @php
                                        $delay += $record['delay'];
                                        $abandonment += $record['abandonment'];
                                    @endphp
                                @endforeach

                                @php
                                    $faults = calculate_recordes($recorded_schedules, $record, $delay, $abandonment);
                                @endphp
                            </td>
                            <td class="text-right">{{ $delay }}</td>
                            <td class="text-right">{{ $faults->faults_entry }}</td>
                            <td class="text-right">
                                @if($abandonment)
                                    <b class="text-danger">ABANDONO</b>
                                @endif
                            </td>
                            <td class="text-right">{{ $faults->faults_entry + $faults->faults_abandonment + $faults->faults_half_day }}</td>
                            <td></td>
                        </tr>
                        @php
                            $cont++;
                            $days_faults += $faults->faults_entry + $faults->faults_abandonment + $faults->faults_half_day;
                        @endphp
                    @empty
                        <tr>
                            <td colspan="7">No hay datos</td>
                        </tr>
                    @endforelse
                @endif
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="6">TOTAL</td>
                    <td class="text-right">{{ $days_faults + (count($days_enabled) - $cont) }}</td>
                    <td></td>
                </tr>
            </tfoot>
        </table>
    </div>
</div>