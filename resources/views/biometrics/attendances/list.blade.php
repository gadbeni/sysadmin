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
                                    $date = date('Y-m-d', strtotime($date));
                                    // Obtener el horario asociado a la fecha actual
                                    $contracts_schedules = $contracts_schedules->where('start', '<=', $date)->where('finish', '>=', $date)->first();
                                    // Obtener los horarios de marcación de ese días específico
                                    $schedule_details = $contracts_schedules->schedule->details->where('day', date('N', strtotime($date)));
                                    $recorded_schedules = collect();
                                    $cont_schedule_details = 0;
                                    // Recorrer los horarios
                                    foreach ($schedule_details as $detail) {
                                        $cont_schedule_details++;
                                        // Obtener la hora de entrada y salida
                                        $entry_time = new DateTime(date('Y-m-d ').$detail->entry);
                                        $exit_time = new DateTime(date('Y-m-d ').$detail->exit);
                                        // Inicializar la marcación que se va a seleccionar
                                        $entry_register_selected = '';
                                        $exit_register_selected = '';
                                        // Inicializar la cantidad de minutos mínima para que una marcación sea tomada en cuenta
                                        $min_diff_minutes_entry = 120;
                                        // Recorrer todos los registros de marcación para calcular la entrada
                                        foreach ($item as $register) {
                                            // Obtener las hora de marcación
                                            $record = new DateTime(date('H:i', strtotime($register->hora)));
                                            
                                            // Obtener si se marcó a la hora de entrada
                                            // Solo se toma en cuenta la marcación si tiene una diferencia de menos de una hora
                                            if($entry_time->diff($record)->h < 1){
                                                // Obtener la diferencia en minutos
                                                $min_diff_register = ($entry_time->diff($record)->h *60) + $entry_time->diff($record)->i;
                                                // Si es menor a la variable de referencia
                                                if($min_diff_register <= $min_diff_minutes_entry){
                                                    $min_diff_minutes_entry = $min_diff_register;
                                                    $entry_register_selected = $record->format('H:i');
                                                }
                                            }
                                        }

                                        // Inicializar la cantidad de minutos mínima para que una marcación sea tomada en cuenta
                                        // (si es la última marcación agarra hasta 4 horas, sino solo da 1 hora de espera)
                                        $min_diff_minutes_exit = $cont_schedule_details == $schedule_details->count() ? 400 : 120;
                                        // Recorrer todos los registros de marcación para calcular la salida
                                        foreach ($item as $register) {
                                            // Obtener las hora de marcación
                                            $record = new DateTime(date('H:i', strtotime($register->hora)));
                                            
                                            // Obtener si se marcó a la hora de salida
                                            // Solo se toma en cuenta la marcación si tiene una diferencia de menos de una hora
                                            // if($exit_time->diff($record)->h < 1){
                                                // Obtener la diferencia en minutos
                                                $min_diff_register = ($exit_time->diff($record)->h *60) + $exit_time->diff($record)->i;
                                                // Si es menor a la variable de referencia
                                                if($min_diff_register <= $min_diff_minutes_exit){
                                                    $min_diff_minutes_exit = $min_diff_register;
                                                    $exit_register_selected = $record->format('H:i');
                                                }
                                            // }
                                        }

                                        $delay = 0;
                                        $abandonment = 0;

                                        // Si llega luego de la hora y pasan 10 minutos
                                        if($entry_register_selected && date('h:i', strtotime(date('Y-m-d').$entry_register_selected)) > date('h:i', strtotime(date('Y-m-d').$detail->entry)) && $min_diff_minutes_entry > 10){
                                            $delay = $min_diff_minutes_entry;
                                        }

                                        // Si sale antes o no marca la salida o no marcó
                                        if(date('h:i', strtotime(date('Y-m-d').$exit_register_selected)) < date('h:i', strtotime(date('Y-m-d').$detail->exit)) || !$exit_register_selected){
                                            $abandonment = 1;
                                        }

                                        $recorded_schedules->push([
                                            'entry' => $detail->entry,
                                            'entry_record' => $entry_register_selected,
                                            'entry_minutes' => $min_diff_minutes_entry,
                                            'exit' => $detail->exit,
                                            'exit_record' => $exit_register_selected,
                                            'exit_minutes' => $min_diff_minutes_exit,
                                            'delay' => $delay,
                                            'abandonment' => $abandonment
                                        ]);
                                    }
                                    // dd($recorded_schedules);
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
                                    // Acumular munitos de atraso o descuento de medio día/día completo
                                    $faults_entry = 0;
                                    $faults_abandonment = 0;
                                    $faults_half_day = 0;
                                    $accumulated_minutes = 0;
                                    if ($delay) {
                                        // Mayor a 10 y menor a 31 minutos se acumula
                                        if ($delay > 10 && $delay < 31) {
                                            $accumulated_minutes += $delay;
                                        // Mayor a 30 y menor a 46 minutos se descuenta medio día
                                        }elseif($delay > 30 && $delay < 46){
                                            $faults_entry += 0.5;
                                            // Mayor a 45 y menor a 61 se descuenta un día
                                        }elseif($delay > 45 && $delay < 61){
                                            $faults_entry += 1;
                                            // Mayor a 60 y menor a 91 minutos se descuenta un día
                                        }elseif($delay > 60 && $delay < 91){
                                            $faults_entry += 2;
                                            // Mayor a 1 hora se descuenta un día
                                        }elseif($delay > 90){
                                            $faults_entry += 3;
                                        }
                                    }
                                    // El abandono equivale a 1 día de descuento
                                    if ($abandonment > 0) {
                                        $faults_abandonment += 1;
                                    }

                                    // En caso de trabajar en horario de oficina
                                    // Si falta media jornada
                                    if($recorded_schedules->count() == 2){
                                        foreach ($recorded_schedules as $record) {
                                            if(!$record['entry_record'] && !$record['exit_record']){
                                                $faults_half_day++;
                                            }
                                        }
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
                            <td></td>
                        </tr>
                        @php
                            $cont++;
                            $days_faults += $faults_entry + $faults_abandonment + $faults_half_day;
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