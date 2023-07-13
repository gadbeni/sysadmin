<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class ContractsExport implements FromView
{
    function __construct($contracts) {
        $this->contracts = $contracts;
    }

    public function view(): View
    {
        return view('reports.management.contracts-excel', [
            'contracts' => $this->contracts
        ]);
    }
}