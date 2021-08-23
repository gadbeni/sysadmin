<?php

namespace App\Actions;

use TCG\Voyager\Actions\AbstractAction;

// Models
use App\Models\Cashier;

class CashiersPrintOpen extends AbstractAction
{
    public function getTitle()
    {
        return 'Imprimir';
    }

    public function getIcon()
    {
        return 'voyager-file-text';
    }

    public function getPolicy()
    {
        return 'add';
    }

    public function getAttributes()
    {
        $cashier = Cashier::findOrFail($this->data->id);
        $display = $cashier->status == 'abierta' ? 'display: block;' : 'display: none;';
        return [
            'class' => 'btn btn-sm btn-default pull-right',
            'style' => 'margin: 5px;'.$display
        ];
    }

    public function getDefaultRoute()
    {
        return route('print.open', ['cashier' => $this->data->id]);
    }
    
    public function shouldActionDisplayOnDataType()
    {
        return $this->dataType->slug == 'cashiers';
    }
}