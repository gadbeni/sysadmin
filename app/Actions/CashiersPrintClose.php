<?php

namespace App\Actions;

use TCG\Voyager\Actions\AbstractAction;

// Models
use App\Models\Cashier;

class CashiersPrintClose extends AbstractAction
{
    public function getTitle()
    {
        return 'Imprimir cierre';
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
        $display = $cashier->status == 'cerrada' ? 'display: block;' : 'display: none;';
        return [
            'class' => 'btn btn-sm btn-default pull-right',
            'style' => 'margin: 5px;'.$display,
            'target' => '_blank'
        ];
    }

    public function getDefaultRoute()
    {
        return route('print.close', ['cashier' => $this->data->id]);
    }
    
    public function shouldActionDisplayOnDataType()
    {
        return $this->dataType->slug == 'cashiers';
    }
}