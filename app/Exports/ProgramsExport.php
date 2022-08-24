<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class ProgramsExport implements FromView
{
    function __construct($program, $type_render) {
        $this->program = $program;
        $this->type_render = $type_render;
    }

    public function view(): View
    {
        return view('reports.rr_hh.projects_details-list-print', [
            'program' => $this->program,
            'type_render' => $this->type_render
        ]);
    }
}