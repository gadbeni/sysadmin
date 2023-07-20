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
        $start = Carbon\Carbon::parse($start);
        $finish = Carbon\Carbon::parse($finish);
        $count_months = 0;
        $dia_fin = 31;

        if($start->format('Ym') == $finish->format('Ym')){
            $count_months = 0;
            $count_days = $start->diffInDays($finish) +1;
            if($finish->format('m') == 2 && $count_days == 28){
                $count_days += 2;
            }
        }else{
            $count_months = 0;
            if($start->format('d') > 30){
                $start_day = 30;
                $start = Carbon\Carbon::parse($start->format('Y-m').'-30');
            }else{
                $start_day = $start->format('d');
            }
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
            if($count_days_last_month >= 30 || ($finish->format('m') == 2 && $count_days_last_month == 28)){
                $count_days_last_month = 0;
                $count_months++;
            }
            $count_days += $count_days_last_month;
        }

        // if($finish->format('d') > 30){
        //     $count_days--;
        // }

        if($count_days >= 30){
            $count_months++;
            $count_days -= 30;
        }

        return json_decode(json_encode(['months' => $count_months, 'days' => $count_days]));
    }
}