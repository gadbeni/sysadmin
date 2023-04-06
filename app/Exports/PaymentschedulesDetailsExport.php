<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class PaymentschedulesDetailsExport implements FromView
{
    function __construct($payments, $grouped) {
        $this->payments = $payments;
        $this->grouped = $grouped;
    }

    public function view(): View
    {
        return view('reports.paymentschedules.paymentschedules_details_status-excel', [
            'payments' => $this->payments,
            'grouped' => $this->grouped
        ]);
    }
}