<?php

namespace App\Imports;

use Maatwebsite\Excel\Concerns\ToModel;

use Illuminate\Support\Facades\Auth;

// Models
use App\Models\PaymentschedulesFilesDetails;
use App\Models\Person;

class PaymentschedulesFilesImport implements ToModel
{
    function __construct($id, $type) {
        $this->id = $id;
        $this->type = $type;
    }

    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        $person = Person::where('ci', $row[2])->where('deleted_at', NULL)->first();
        if($person){
            $details = '';
            if($this->type == 'rc-iva'){
                $details = json_encode([
                    'salary' => $row[3],
                    'iva' => $row[4],
                    'iue' => $row[5],
                    'it' => $row[6]
                ]);
            }elseif($this->type == 'biométrico'){
                $details = json_encode([
                    'faults' => $row[3],
                    'observations' => $row[4]
                ]);
            }
            return new PaymentschedulesFilesDetails([
                'paymentschedules_file_id' => $this->id,
                'person_id' => $person->id,
                'details' => $details
            ]);
        }
    }
}
