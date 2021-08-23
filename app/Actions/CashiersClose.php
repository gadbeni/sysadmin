<?php

namespace App\Actions;

use TCG\Voyager\Actions\AbstractAction;

// Models
use App\Models\Cashier;

class CashiersClose extends AbstractAction
{
    public function getTitle()
    {
        return 'Cerrar';
    }

    public function getIcon()
    {
        return 'voyager-lock';
    }

    public function getPolicy()
    {
        return 'add';
    }

    public function getAttributes()
    {
        $cashier = Cashier::findOrFail($this->data->id);
        $display = $cashier->status == 'cierre pendiente' ? 'display: block;' : 'display: none;';
        return [
            'class' => 'btn btn-sm btn-dark pull-right',
            'style' => 'margin: 5px;'.$display
        ];
    }

    public function getDefaultRoute()
    {
        return route('cashiers.confirm_close', ['cashier' => $this->data->id]);
    }
    
    public function shouldActionDisplayOnDataType()
    {
        return $this->dataType->slug == 'cashiers';
    }
}