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

        if($start->format('Y-m') == $finish->format('Y-m')){
            $count_months = 0;
            if($finish->format('d') < 30){
                $dia_fin = $finish->format('d') +1;
            }
            $count_days = $dia_fin - $start->format('d');
        }else{
            $count_months = 0;
            $count_days = 31 - $start->format('d');
            $start = Carbon\Carbon::parse($start->addMonth()->format('Y-m').'-01');
            while ($start <= $finish) {
                $count_months++;
                $start->addMonth();
            }
            $count_months--;
            $count_days += $start->subMonth()->diffInDays($finish) +1;
            if($count_days > 30){
                $count_days -= 30;
                $count_months++;
            }
        }

        if($finish->format('d') > 30){
            $count_days--;
        }

        return json_decode(json_encode(['months' => $count_months, 'days' => $count_days]));
    }
}