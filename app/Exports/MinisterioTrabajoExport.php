<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class MinisterioTrabajoExport implements FromView
{
    function __construct($data) {
        $this->data = $data;
    }

    public function view(): View
    {
        return view('paymentschedules.partials.reporte_ministerio', [
            'data' => $this->data
        ]);
    }
}
