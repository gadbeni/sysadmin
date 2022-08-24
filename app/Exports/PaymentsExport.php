<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class PaymentsExport implements FromView
{
    function __construct($data, $afp, $cc, $centralize, $program, $group, $type_generate, $type_render) {
        $this->data = $data;
        $this->afp = $afp;
        $this->cc = $cc;
        $this->centralize = $centralize;
        $this->program = $program;
        $this->group = $group;
        $this->type_generate = $type_generate;
        $this->type_render = $type_render;

    }

    public function view(): View
    {
        return view('paymentschedules.print', [
            'data' => $this->data,
            'afp' => $this->afp,
            'cc' => $this->cc,
            'centralize' => $this->centralize,
            'program' => $this->program,
            'group' => $this->group,
            'type_generate' => $this->type_generate,
            'type_render' => $this->type_render,
        ]);
    }
}