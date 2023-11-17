<?php

namespace App\Actions;

use TCG\Voyager\Actions\AbstractAction;

class SchedulesAssignment extends AbstractAction
{
    public function getTitle()
    {
        return 'Asignación';
    }

    public function getIcon()
    {
        return 'voyager-paperclip';
    }

    public function getPolicy()
    {
        return 'add';
    }

    public function getAttributes()
    {
        return [
            'class' => 'btn btn-sm btn-dark pull-right',
            'style' => 'margin: 5px; padding: 4px 8px',
        ];
    }

    public function getDefaultRoute()
    {
        return route('schedules.assignments', ['id' => $this->data->id]);
    }
    
    public function shouldActionDisplayOnDataType()
    {
        return $this->dataType->slug == 'schedules';
    }
}