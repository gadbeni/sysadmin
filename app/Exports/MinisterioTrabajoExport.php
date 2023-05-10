<?php

namespace App\Exports;

use PhpOffice\PhpSpreadsheet\Shared\Date;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\FromCollection;
use Illuminate\Support\Str;

// Models
use App\Models\Period;

class MinisterioTrabajoExport implements WithColumnFormatting, FromCollection
{
    function __construct($data) {
        $this->data = $data;
    }

    public function collection()
    {
        $data = [];
        $cont = 1;
        foreach($this->data as $item){
            $period = Period::findOrFail($item->paymentschedule->period_id);
            $year = Str::substr($period->name, 0, 4);
            $month = Str::substr($period->name, 4, 2);
            $irremovability = $item->contract->person->irremovabilities->count() ? $item->contract->person->irremovabilities[0] : NULL;
            
            if ($item->contract->person->afp == 1)
                $afp = 2;
            elseif ($item->contract->person->afp == 2)
                $afp = 1;
            else
                $afp = 3;

            if ($item->contract->cargo)
                $job = $item->contract->cargo->Descripcion;
            elseif ($item->contract->job)
                $job = $item->contract->job->name;
            else
                $job = 'No definido';

            array_push($data, [
                'A' => $cont,
                'B' => 'CI',
                'C' => $item->contract->person->ci,
                'D' => '',
                'E' => Date::stringToExcel($item->contract->person->birthday),
                'F' => explode(' ', $item->contract->person->last_name)[0],
                'G' => count(explode(' ', $item->contract->person->last_name)) > 1 ? explode(' ', $item->contract->person->last_name)[1] : '',
                'H' => $item->contract->person->first_name,
                'I' => $item->contract->person->city ? $item->contract->person->city->state->country->name : 'No definida',
                'J' => $item->contract->person->gender == 'masculino' ? 'M' : 'F',
                'K' => $item->contract->person->retired,
                'L' => $item->contract->person->afp_status,
                'M' => $irremovability ? $irremovability->irremovability_type_id == 4 ? 1 : 0 : 0,
                'N' => $irremovability ? $irremovability->irremovability_type_id == 5 ? 1 : 0 : 0,
                'O' => Date::stringToExcel($item->contract->start),
                'P' => date('Ym', strtotime($item->contract->finish)) == $year.$month ? Date::stringToExcel($item->contract->finish) : '',
                'Q' => 2,
                'R' => $item->contract->person->cc == 1 ? 6 : 2,
                'S' => $afp,
                'T' => $item->contract->person->nua_cua,
                'U' => '',
                'V' => 1,
                'W' => $job,
                'X' => $item->contract->procedure_type_id,
                'Y' => 1,
                'Z' => $item->worked_days,
                'AA' => 8,
                'AB' => number_format($item->salary, 2, '.', ''),
                'AC' => number_format($item->seniority_bonus_amount, 2, '.', ''),
                'AD' => '0',
                'AE' => '0.00',
                'AF' => '0',
                'AG' => '0.00',
                'AH' => '0',
                'AI' => '0.00',
                'AJ' => '0',
                'AK' => '0.00',
                'AL' => '0',
                'AM' => '0.00',
                'AN' => '0.00',
                'AO' => '0.00',
                'AP' => '0.00',
                'AQ' => number_format($item->rc_iva_amount, 2, '.', ''),
                'AR' => number_format($item->health, 2, '.', ''),
                'AS' => number_format($item->solidary + $item->common_risk + $item->retirement, 2, '.', ''),
                'AT' => number_format($item->faults_amount, 2, ',', '')
            ]);
            $cont++;
        }
        return collect($data);
    }

    public function columnFormats(): array
    {
        return [
            'E' => NumberFormat::FORMAT_DATE_DDMMYYYY,
            'O' => NumberFormat::FORMAT_DATE_DDMMYYYY,
            'P' => NumberFormat::FORMAT_DATE_DDMMYYYY,
        ];
    }
}
