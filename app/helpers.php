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