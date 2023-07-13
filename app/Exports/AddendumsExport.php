<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class AddendumsExport implements FromView
{
    function __construct($addendums) {
        $this->addendums = $addendums;
    }

    public function view(): View
    {
        return view('reports.management.addendums-excel', [
            'addendums' => $this->addendums
        ]);
    }
}