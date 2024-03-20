<?php
if (!function_exists('set_setting')) {
    function set_setting($key, $value = null){
        try {
            \DB::table('settings')->where('key', $key)->update(['value' => $value]);
            return true;
        } catch (\Throwable $th) {
            //throw $th;
            return false;
        }
    }
}

if (! function_exists('contract_duration_calculate')) {
    function contract_duration_calculate($start, $finish)
    {
        // Obtener el último día de febrero (por si es un año bisiesto)
        $last_day_february = date("t", strtotime(date('Y').'-02-01'));
        $start = Carbon\Carbon::parse($start);
        $finish = Carbon\Carbon::parse($finish);
        $count_months = 0;

        if($start->format('Ym') == $finish->format('Ym')){
            $count_months = 0;
            if($finish->format('d') > 30){
                $finish = Carbon\Carbon::parse($finish->addDays(-1)->format('Y-m-d'));
            }
            $count_days = $start->diffInDays($finish) +1;
            if($finish->format('m') == 2 && ($finish->format('d') == $last_day_february)){
                $count_days += (30 - $finish->endOfMonth()->format('d'));
            }
            $count_days = $count_days > 30 ? 30 : $count_days;
        }else{
            $count_months = 0;
            if($start->format('d') > 30){
                $start = Carbon\Carbon::parse($start->addDays()->format('Y-m-d'));
            }
            $start_day = $start->format('d');
            $count_days = 30 - $start_day +1;
            $start = Carbon\Carbon::parse($start->addMonth()->format('Y-m').'-01');
            while ($start <= $finish) {
                $count_months++;
                $start->addMonth();
            }
            $count_months--;

            // Calcula la cantidad de días del ultimo mes
            $count_days_last_month = $start->subMonth()->diffInDays($finish) +1;
            // Si es mayor o igual a 30 se toma como un mes completo
            if($count_days_last_month >= 30 || ($finish->format('m') == 2 && $count_days_last_month == $last_day_february)){
                $count_days_last_month = 0;
                $count_months++;
            }
            $count_days += $count_days_last_month;
        }

        if($count_days >= 30){
            $count_months++;
            $count_days -= 30;
        }

        return json_decode(json_encode(['months' => $count_months, 'days' => $count_days]));
    }
}

if (! function_exists('getDiasHabiles')) {
    function getDiasHabiles($fechainicio, $fechafin, $diasferiados = array()) {
        // Convirtiendo en timestamp las fechas
        $fechainicio = strtotime($fechainicio);
        $fechafin = strtotime($fechafin);
       
        // Incremento en 1 dia
        $diainc = 24*60*60;
       
        // Arreglo de dias habiles, inicianlizacion
        $diashabiles = array();
       
        // Se recorre desde la fecha de inicio a la fecha fin, incrementando en 1 dia
        for ($midia = $fechainicio; $midia <= $fechafin; $midia += $diainc) {
                // Si el dia indicado, no es sabado o domingo es habil
                if (!in_array(date('N', $midia), array(6,7))) { // DOC: http://www.php.net/manual/es/function.date.php
                        // Si no es un dia feriado entonces es habil
                        if (!in_array(date('Y-m-d', $midia), $diasferiados)) {
                                array_push($diashabiles, date('Y-m-d', $midia));
                        }
                }
        }
       
        return $diashabiles;
    }
}

if (! function_exists('recorded_schedules')) {
    function recorded_schedules($item, $contracts_schedule_date, $date) {
        $date = date('Y-m-d', strtotime($date));
        $schedule_details = $contracts_schedule_date->schedule->details->where('day', date('N', strtotime($date)));
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
                if($entry_time->diff($record)->h < ($min_diff_minutes_entry /60)){
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

        return $recorded_schedules;
    }
}

if (! function_exists('calculate_recordes')) {
    function calculate_recordes($recorded_schedules, $record, $delay, $abandonment) {
        // Acumular munitos de atraso o descuento de medio día/día completo
        $faults_entry = 0;
        $faults_abandonment = 0;
        $faults_half_day = 0;
        $accumulated_minutes = 0;

        // Si no marco a la llegada 1 falta
        if(!$record['entry_record']){
            $faults_entry++;
        }

        if ($delay) {
            // Mayor a 10 y menor a 90 minutos se acumula
            if ($delay > 10 && $delay <= 90) {
                $accumulated_minutes += $delay;
            // Mayor a 90 se toma como falta
            }elseif($delay > 90){
                $faults_entry += 1;
            }
        }
        // El abandono equivale a medio día de descuento (solo es abandono si marcó la llegada)
        if ($abandonment > 0 && $record['entry_record'] && $faults_entry < 1 ) {
            $faults_abandonment += 0.5;
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

        return json_decode(json_encode(array("faults_entry" => $faults_entry, "faults_abandonment" => $faults_abandonment, "faults_half_day" => $faults_half_day, "accumulated_minutes" => $accumulated_minutes)));
    }
}