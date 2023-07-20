<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class PeopleExport implements FromView
{
    function __construct($people) {
        $this->people = $people;
    }

    public function view(): View
    {
        return view('reports.rr_hh.people-list-excel', [
            'people' => $this->people
        ]);
    }
}