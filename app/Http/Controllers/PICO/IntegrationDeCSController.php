<?php

namespace PICOExplorer\Http\Controllers\PICO;

use PICOExplorer\Facades\DeCSIntegrationFacade;
use PICOExplorer\Models\DataTransferObject;

class IntegrationDeCSController extends ControllerModel implements MainControllerInterface
{

    public function ServiceBind()
    {
        return new DeCSIntegrationFacade();
    }

    public function getMainModel()
    {
        return DataTransferObject::getModel('IntegrationDeCS');
    }

}
