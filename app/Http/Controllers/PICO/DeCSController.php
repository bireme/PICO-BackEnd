<?php

namespace PICOExplorer\Http\Controllers\PICO;

use PICOExplorer\Facades\DeCSProcessFacade;
use PICOExplorer\Models\DataTransferObject;

class DeCSController extends ControllerModel implements MainControllerInterface
{

    public function ServiceBind()
    {
        return new DeCSProcessFacade();
    }

    public function getMainModel()
    {
        return DataTransferObject::getModel('DeCS');
    }

}
