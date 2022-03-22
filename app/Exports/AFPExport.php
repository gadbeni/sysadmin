<?php

namespace App\Exports;

use PhpOffice\PhpSpreadsheet\Shared\Date;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\FromCollection;
use Illuminate\Support\Str;

// Models
use App\Models\Period;

class AFPExport implements WithColumnFormatting, FromCollection
{
    function __construct($data, $type) {
        $this->data = $data;
        $this->type = $type;
    }

    public function collection()
    {
        $data = [];
        $cont = 1;
        if($this->type == 1){
            foreach($this->data as $item){
                $novelty = '';
                $novelty_date = '';
                if(date('Ym', strtotime($item->contract->start)) == $item->paymentschedule->period->name){
                    $novelty = 'I';
                    $novelty_date = date('d-m-Y', strtotime($item->contract->start));
                }
                if(date('Ym', strtotime($item->contract->finish)) == $item->paymentschedule->period->name){
                    $novelty = 'E';
                    $novelty_date = date('d-m-Y', strtotime($item->contract->finish));
                }

                // Calcular edad
                $now = \Carbon\Carbon::now();
                $birthday = new \Carbon\Carbon($item->contract->person->birthday);
                $age = $birthday->diffInYears($now);
                $total_amount = $item->partial_salary + $item->seniority_bonus_amount;

                array_push($data, [
                    'A' => $cont,
                    'B' => 'CI',
                    'C' => $item->contract->person->ci,
                    'D' => '',
                    'E' => $item->contract->person->nua_cua,
                    'F' => explode(' ', $item->contract->person->last_name)[0],
                    'G' => count(explode(' ', $item->contract->person->last_name)) > 1 ? explode(' ', $item->contract->person->last_name)[1] : '',
                    'H' => '',
                    'I' => explode(' ', $item->contract->person->first_name)[0],
                    'J' => count(explode(' ', $item->contract->person->first_name)) > 1 ? explode(' ', $item->contract->person->first_name)[1] : '',
                    'K' => 'BENI',
                    'L' => $novelty,
                    'M' => $novelty_date ? Date::stringToExcel($novelty_date) : '',
                    'N' => $item->worked_days,
                    'O' => 'N',
                    'P' => $age < 65 && $item->contract->person->afp_status == 1 ? $total_amount : 0,
                    'Q' => $age >= 65 && $item->contract->person->afp_status == 1 ? $total_amount : 0,
                    'R' => $age < 65 && $item->contract->person->afp_status == 0 ? $total_amount : 0,
                    'S' => $age >= 65 && $item->contract->person->afp_status == 0 ? $total_amount : 0,
                    'T' => 0,
                    'U' => $total_amount,
                    'V' => $item->partial_salary,
                    'W' => 0,
                ]);
                $cont++;
            }
        }else{
            foreach($this->data->groupBy('contract.person.ci') as $item){
                $novelty = '';
                $novelty_date = '';
                if(date('Ym', strtotime($item[0]->contract->start)) == $item[0]->paymentschedule->period->name){
                    $novelty = 'I';
                    $novelty_date = date('d-m-Y', strtotime($item[0]->contract->start));
                }
                if(date('Ym', strtotime($item[0]->contract->finish)) == $item[0]->paymentschedule->period->name){
                    $novelty = 'E';
                    $novelty_date = date('d-m-Y', strtotime($item[0]->contract->finish));
                }
                $worked_days = $item->sum('worked_days');
                $total_amount = $item->sum('partial_salary') + $item->sum('seniority_bonus_amount');

                array_push($data, [
                    'A' => $cont,
                    'B' => 'CI',
                    'C' => $item[0]->contract->person->ci,
                    'D' => count(explode('-', $item[0]->contract->person->ci)) > 1 ? explode('-', $item[0]->contract->person->ci)[1] : '',
                    'E' => $item[0]->contract->person->nua_cua,
                    'F' => explode(' ', $item[0]->contract->person->last_name)[0],
                    'G' => count(explode(' ', $item[0]->contract->person->last_name)) > 1 ? explode(' ', $item[0]->contract->person->last_name)[1] : '',
                    'H' => '',
                    'I' => explode(' ', $item[0]->contract->person->first_name)[0],
                    'J' => count(explode(' ', $item[0]->contract->person->first_name)) > 1 ? explode(' ', $item[0]->contract->person->first_name)[1] : '',
                    'K' => $novelty,
                    'L' => $novelty_date ? Date::stringToExcel($novelty_date) : '',
                    'M' => $worked_days,
                    'N' => $total_amount,
                    'O' => 1,
                    'P' => '',
                ]);
                $cont++;
            }
        }
        return collect($data);
    }

    public function columnFormats(): array
    {
        if($this->type == 1){
            return [
                'M' => NumberFormat::FORMAT_DATE_DDMMYYYY,
            ];
        }

        if($this->type == 2){
            return [
                'L' => NumberFormat::FORMAT_DATE_DDMMYYYY,
            ];
        }
    }
}
