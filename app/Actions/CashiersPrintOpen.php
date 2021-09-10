<?php

namespace App\Actions;

use TCG\Voyager\Actions\AbstractAction;

// Models
use App\Models\Cashier;

class CashiersPrintOpen extends AbstractAction
{
    public function getTitle()
    {
        return 'Imprimir apertura';
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
        if ($cashier->status == 'abierta' || $cashier->status == 'apertura pendiente') {
            $display = 'display: block;';
        }else{
            $display = 'display: none;';
        }
        $url = route('print.open', ['cashier' => $this->data->id]);
        return [
            'class' => 'btn btn-sm btn-default pull-right',
            'style' => 'margin: 5px;'.$display,
            'onclick' => "openWindow('$url', 'Apertura de caja')"
        ];
    }

    public function getDefaultRoute(){
        return '#';
    }
    
    public function shouldActionDisplayOnDataType()
    {
        return $this->dataType->slug == 'cashiers';
    }
}