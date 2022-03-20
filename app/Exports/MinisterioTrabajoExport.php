<?php

namespace App\Exports;

// use Illuminate\Contracts\View\View;
// use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\FromCollection;
use Illuminate\Support\Str;

// Models
use App\Models\Period;

class MinisterioTrabajoExport implements FromCollection
{
    function __construct($data) {
        $this->data = $data;
    }

    public function collection()
    {
        $data = [];
        foreach($this->data as $item){
            $period = Period::findOrFail($item->paymentschedule->period_id);
            $year = Str::substr($period->name, 0, 4);
            $month = Str::substr($period->name, 4, 2);
            array_push($data, [
                '1' => 908,
                '2' => 'PAGO DE HABERES',
                '3' => '20 RECESP',
                '4' => '220 REG',
                '5' => $year,
                '6' => $month,
                '7' => explode('-', $item->contract->person->ci)[0],
                '8' => count(explode('-', $item->contract->person->ci)) > 1 ? explode('-', $item->contract->person->ci)[1] : '',
                '9' => 'OTRO',
                '10' => explode(' ', $item->contract->person->first_name)[0],
                '11' => count(explode(' ', $item->contract->person->first_name)) > 1 ? explode(' ', $item->contract->person->first_name)[1] : '',
                '12' => explode(' ', $item->contract->person->last_name)[0],
                '13' => count(explode(' ', $item->contract->person->last_name)) > 1 ? explode(' ', $item->contract->person->last_name)[1] : '',
                '14' => '',
                '15' => \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject(intval($item->contract->person->birthday))->format('Y-m-d'),
                '16' => \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject(intval($item->contract->start))->format('Y-m-d'),
                '17' => $item->contract->person->gender == 'masculino' ? 'M' : 'F',
                '18' => $item->contract->job_id ? $item->contract->job->item : '',
                '19' => $item->job,
                '20' => 'PERMANENTE',
                '21' => $item->contract->person->profession,
                '22' => $item->worked_days,
                '23' => number_format($item->salary, 2, ',', ''),
                '24' => number_format($item->seniority_bonus_amount, 2, ',', ''),
                '25' => 0,
                '26' => number_format($item->partial_salary, 2, ',', ''),
                '27' => number_format($item->solidary + $item->common_risk + $item->retirement, 2, ',', ''),
                '28' => number_format($item->faults_amount, 2, ',', ''),
                '29' => number_format($item->solidary, 2, ',', ''),
                '30' => number_format($item->solidary_employer, 2, ',', ''),
                '31' => number_format($item->liquid_payable, 2, ',', ''),
            ]);
        }
        return collect($data);
    }
}
