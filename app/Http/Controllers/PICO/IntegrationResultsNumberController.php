<?php

namespace PICOExplorer\Http\Controllers\PICO;

use PICOExplorer\Facades\ResultsNumberIntegrationFacade;
use PICOExplorer\Models\DataTransferObject;

class IntegrationResultsNumberController extends ControllerModel implements MainControllerInterface
{

    public function ServiceBind()
    {
        return new ResultsNumberIntegrationFacade();
    }

    public function getMainModel()
    {
        return DataTransferObject::getModel('IntegrationResultsNumber');
    }

}
