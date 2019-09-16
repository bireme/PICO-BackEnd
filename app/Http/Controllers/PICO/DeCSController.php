<?php

namespace PICOExplorer\Http\Controllers\PICO;

use PICOExplorer\Models\DataTransferObject;

class DeCSController extends ControllerModel implements MainControllerInterface
{

    public function ServiceBind()
    {
        return new \DeCSProcessSV();
    }

    public function getMainModel()
    {
        return DataTransferObject::getModel('DeCS');
    }

}
