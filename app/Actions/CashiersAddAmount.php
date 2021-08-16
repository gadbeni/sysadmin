<?php

namespace App\Actions;

use TCG\Voyager\Actions\AbstractAction;

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
        return [
            'class' => 'btn btn-sm btn-success pull-right',
            'style' => 'margin: 5px'
        ];
    }

    public function getDefaultRoute()
    {
        return route('cashiers.add.amount', ['cashier' => $this->data->id]);
    }
    
    public function shouldActionDisplayOnDataType()
    {
        return $this->dataType->slug == 'cashiers';
    }
}