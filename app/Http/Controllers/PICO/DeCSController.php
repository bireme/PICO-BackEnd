<?php

namespace PICOExplorer\Http\Controllers\PICO;

use PICOExplorer\Facades\DeCSFacade;
use PICOExplorer\Models\DataTransferObject;

class DeCSController extends ControllerModel implements MainControllerInterface
{

    public function ServiceBind()
    {
        return new DeCSFacade();
    }

    public function getMainModel()
    {
        return DataTransferObject::getModel('DeCS');
    }

}
