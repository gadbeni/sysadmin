<?php

namespace App\Imports;

use Maatwebsite\Excel\Concerns\ToModel;

use Illuminate\Support\Facades\Auth;

// Models
use App\Models\SeniorityBonusPerson;
use App\Models\Person;

class AuxiliarImport implements ToModel
{
    function __construct($type) {
        $this->type = $type;
    }

    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        if($this->type == 'bono antiguedad'){
            $person = Person::where('ci', $row[0])->where('deleted_at', NULL)->first();
            if($person){
                
                // Actualizar el anterior registro de bono antiguedad a estado inactivo (2)
                SeniorityBonusPerson::where('person_id', $person->id)->where('status', 1)->where('deleted_at', NULL)->update([
                    'status' => 2
                ]);

                $start = intval($row[5]);

                return new SeniorityBonusPerson([
                    'seniority_bonus_type_id' => $row[1],
                    'person_id' => $person->id,
                    'user_id' => Auth::user()->id,
                    'years' => $row[2],
                    'months' => $row[3],
                    'days'  => $row[4],
                    'quantity' => 1,
                    'start' => \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($start)->format('Y-m-d')    
                ]);   
            }
        }
    }
}
