<?php

namespace App\Exports;

use PhpOffice\PhpSpreadsheet\Shared\Date;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\FromCollection;
use Illuminate\Support\Str;

// Models
use App\Models\Period;

class MinisterioEconomiaExport implements WithColumnFormatting, FromCollection
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
                'A' => 908,
                'B' => 'PAGO DE HABERES',
                'C' => '20 RECESP',
                'D' => '220 REG',
                'E' => $year,
                'F' => $month,
                'G' => explode('-', $item->contract->person->ci)[0],
                'H' => count(explode('-', $item->contract->person->ci)) > 1 ? explode('-', $item->contract->person->ci)[1] : '',
                'I' => 'OTRO',
                'J' => $this->format_string(explode(' ', $item->contract->person->first_name)[0]),
                'K' => $this->format_string(count(explode(' ', $item->contract->person->first_name)) > 1 ? explode(' ', $item->contract->person->first_name)[1] : ''),
                'L' => $this->format_string(explode(' ', $item->contract->person->last_name)[0]),
                'M' => $this->format_string(count(explode(' ', $item->contract->person->last_name)) > 1 ? explode(' ', $item->contract->person->last_name)[1] : ''),
                'N' => '',
                'O' => Date::stringToExcel($item->contract->person->birthday),
                'P' => Date::stringToExcel($item->contract->start),
                'Q' => $item->contract->person->gender == 'masculino' ? 'M' : 'F',
                'R' => $item->contract->job_id ? $item->contract->job->item : '',
                'S' => $item->job,
                'T' => Str::upper($item->contract->type->name),
                'U' => 'NO EXISTE INFORMACION',
                'V' => $item->worked_days,
                'W' => $item->salary,
                'X' => $item->seniority_bonus_amount,
                'Y' => 0,
                'Z' => $item->partial_salary,
                'AA' => $item->solidary + $item->common_risk + $item->retirement,
                'AB' => $item->faults_amount,
                'AC' => $item->solidary,
                'AD' => $item->solidary_employer,
                'AE' => $item->liquid_payable,
            ]);
        }
        return collect($data);
    }

    public function columnFormats(): array
    {
        return [
            'O' => NumberFormat::FORMAT_DATE_DDMMYYYY,
            'P' => NumberFormat::FORMAT_DATE_DDMMYYYY,
        ];
    }

    function format_string($cadena){
		
		//Reemplazamos la A y a
		$cadena = str_replace(
		array('Á', 'À', 'Â', 'Ä', 'á', 'à', 'ä', 'â', 'ª'),
		array('A', 'A', 'A', 'A', 'a', 'a', 'a', 'a', 'a'),
		$cadena);

		//Reemplazamos la E y e
		$cadena = str_replace(
		array('É', 'È', 'Ê', 'Ë', 'é', 'è', 'ë', 'ê'),
		array('E', 'E', 'E', 'E', 'e', 'e', 'e', 'e'),
		$cadena);

		//Reemplazamos la I y i
		$cadena = str_replace(
		array('Í', 'Ì', 'Ï', 'Î', 'í', 'ì', 'ï', 'î'),
		array('I', 'I', 'I', 'I', 'i', 'i', 'i', 'i'),
		$cadena);

		//Reemplazamos la O y o
		$cadena = str_replace(
		array('Ó', 'Ò', 'Ö', 'Ô', 'ó', 'ò', 'ö', 'ô'),
		array('O', 'O', 'O', 'O', 'o', 'o', 'o', 'o'),
		$cadena);

		//Reemplazamos la U y u
		$cadena = str_replace(
		array('Ú', 'Ù', 'Û', 'Ü', 'ú', 'ù', 'ü', 'û'),
		array('U', 'U', 'U', 'U', 'u', 'u', 'u', 'u'),
		$cadena);

		//Reemplazamos la N, n, C y c
		$cadena = str_replace(
		array('Ç', 'ç'),
		array('C', 'c'),
		$cadena);
		
		return $cadena;
	}
}
