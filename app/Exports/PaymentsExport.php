<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;

// Models
use App\Models\User;

class PaymentsExport implements FromCollection
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return User::all();
    }
}
