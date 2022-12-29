<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class BonusExport implements FromView
{
    function __construct($bonuses, $year) {
        $this->bonuses = $bonuses;
        $this->year = $year;
    }

    public function view(): View
    {
        return view('reports.paymentschedules.bonus-excel', [
            'bonuses' => $this->bonuses,
            'year' => $this->year
        ]);
    }
}
