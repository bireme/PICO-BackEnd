<?php

namespace PICOExplorer\Http\Controllers\PICO;

use PICOExplorer\Facades\DeCSBIREMEFacade;
use PICOExplorer\Models\DataTransferObject;

class IntegrationDeCSController extends ControllerModel implements MainControllerInterface
{

    public function ServiceBind()
    {
        return new DeCSBIREMEFacade();
    }

    public function getMainModel()
    {
        return DataTransferObject::getModel('IntegrationDeCS');
    }

}
