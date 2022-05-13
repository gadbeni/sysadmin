<?php

namespace App\Exports;

use PhpOffice\PhpSpreadsheet\Shared\Date;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;
use Illuminate\Support\Str;

// Models
use App\Models\Period;

class AFPExport implements WithColumnFormatting, FromCollection, WithHeadings, WithTitle
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
                    $novelty = 'R';
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
                    'P' => $age < 65 && $item->contract->person->afp_status == 1 ? number_format($total_amount, 2, '.', '') : 0.0,
                    'Q' => $age >= 65 && $item->contract->person->afp_status == 1 ? number_format($total_amount, 2, '.', '') : 0.0,
                    'R' => $age < 65 && $item->contract->person->afp_status == 0 ? number_format($total_amount, 2, '.', '') : 0.0,
                    'S' => $age >= 65 && $item->contract->person->afp_status == 0 ? number_format($total_amount, 2, '.', '') : 0.0,
                    'T' => 0.0,
                    'U' => number_format($total_amount, 2, '.', ''),
                    'V' => number_format($total_amount, 2, '.', ''),
                    'W' => 0.0,
                ]);
                $cont++;
            }
        }else{
            foreach($this->data->groupBy('contract.person.ci') as $item){
                $novelty = '';
                $novelty_date = '';
                if(date('Ym', strtotime($item[0]->contract->start)) == $item[0]->paymentschedule->period->name){
                    $novelty = 'I';
                    $novelty_date = date('Ymd', strtotime($item[0]->contract->start));
                }
                if(date('Ym', strtotime($item[0]->contract->finish)) == $item[0]->paymentschedule->period->name){
                    $novelty = 'R';
                    $novelty_date = date('Ymd', strtotime($item[0]->contract->finish));
                }
                $worked_days = $item->sum('worked_days');
                $total_amount = $item->sum('partial_salary') + $item->sum('seniority_bonus_amount');

                $now = \Carbon\Carbon::now();
                $birthday = new \Carbon\Carbon($item[0]->contract->person->birthday);
                $age = $birthday->diffInYears($now);
                $type = '';
                if($age < 65 && $item[0]->contract->person->afp_status == 1){
                    $type = '1';
                }
                if($age >= 65 && $item[0]->contract->person->afp_status == 1){
                    $type = '8';
                }
                if($age < 65 && $item[0]->contract->person->afp_status == 0){
                    $type = 'C';
                }
                if($age >= 65 && $item[0]->contract->person->afp_status == 0){
                    $type = 'D';
                }

                array_push($data, [
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
                    'L' => $novelty_date ? $novelty_date : '',
                    'M' => $worked_days,
                    'N' => $total_amount,
                    'O' => $type,
                    'P' => '',
                ]);
                $cont++;
            }
        }
        return collect($data);
    }

    public function headings(): array
    {
        if($this->type == 1){
            return [
                'No',
                'Tipo',
                'Numero',
                'EXP.',
                'NUA / CUA',
                'Primer Papellido (Paterno)',
                'Primer Papellido (Materno)',
                'Apellido Casada',
                'Primer Nombre',
                'Segundo Nombre',
                'Departamento',
                'Novedad (I/R/L/S)',
                'Fecha Novedad dd/mm/aaaa',
                'Dias Cotizados',
                'Tipo de Asegurado',
                'Total Ganado dep. o aseg. < 65 Aporta',
                'Total Ganado dep. o aseg. > 65 Aporta',
                'Total Ganado aseg. con pens. < 65 no Aporta',
                'Total Ganado aseg. con pens. > 65 no Aporta',
                'Cotizacion Adicional',
                'Total Ganado Bs. Vivienda',
                'Total Ganado Bs. Fondo Social',
                'Total Ganado Bs.(minero)'
            ];
        }else{
            return [
                'Tipo Doc',
                'Numero Documento',
                'Alfa Numero',
                'NUA/CUA',
                'Ap. Paterno',
                'Ap. Materno',
                'Ap. Casada',
                'Primer Nombre',
                'Seg. Nombre',
                'Novedad',
                'Fecha Novedad',
                'Dias',
                'Total Ganado',
                'Tipo Cotizante',
                'Tipo Asegurado'
            ];
        }
    }

    public function columnFormats(): array
    {
        if($this->type == 1){
            return [
                'M' => NumberFormat::FORMAT_DATE_DDMMYYYY
            ];
        }

        return [];
    }

    public function title(): string
    {
        return 'Hoja1';
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
		array('Ñ', 'ñ', 'Ç', 'ç'),
		array('N', 'n', 'C', 'c'),
		$cadena);
		
		return $cadena;
	}
}
