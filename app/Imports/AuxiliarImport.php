<?php

namespace App\Imports;

use Maatwebsite\Excel\Concerns\ToModel;

use Illuminate\Support\Facades\Auth;

// Models
use App\Models\SeniorityBonusPerson;
use App\Models\Person;
use App\Models\Contract;
use App\Models\Direccion;
use App\Models\Job;

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

        if($this->type == 'funcionamiento'){
            $person = Person::where('ci', $row[3])->first();
            if(!$person){
                $birthday = $start = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject(intval($row[10]))->format('Y-m-d');
                $person = Person::create([
                    'first_name' => $row[0],
                    'last_name' => $row[1].' '.$row[2],
                    'ci' => $row[3],
                    'profession' => $row[4],
                    'phone' => $row[5],
                    'address' => $row[6],
                    'email' => $row[7],
                    'afp' => $row[8],
                    'gender' => $row[9],
                    'birthday' => $birthday,
                    'civil_status' => 1,
                    'nua_cua' => $row[11],
                    'user_id' => Auth::user()->id,
                ]);

                $start = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject(intval($row[13]))->format('Y-m-d');

                Contract::create([
                    'person_id' => $person->id,
                    'program_id' => 1,
                    'procedure_type_id' => 1,
                    'user_id' => Auth::user()->id,
                    'code' => $row[12],
                    'start' => $start
                ]);
            }
        }

        if($this->type == 'estructura permanente'){
            if($row[0]){
                return new Job([
                    'item' => $row[0],
                    'level' => $row[1],
                    'name' => $row[2],
                    'direccion_administrativa_id' => $row[3],
                    'salary' => $row[4]
                ]);
            }
        }
    }
}
