<?php

namespace App\Actions;

use TCG\Voyager\Actions\AbstractAction;

// Models
use App\Models\Cashier;

class CashiersAddAmount extends AbstractAction
{
    public function getTitle()
    {
        return 'Abonar monto';
    }

    public function getIcon()
    {
        return 'voyager-dollar';
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
            'class' => 'btn btn-sm btn-success pull-right',
            'style' => 'margin: 5px;'.$display,
        ];
    }

    public function getDefaultRoute()
    {
        return route('cashiers.amount', ['cashier' => $this->data->id]);
    }
    
    public function shouldActionDisplayOnDataType()
    {
        return $this->dataType->slug == 'cashiers';
    }
}