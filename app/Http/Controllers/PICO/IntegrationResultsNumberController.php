<?php

namespace PICOExplorer\Http\Controllers\PICO;

use PICOExplorer\Facades\ResultsNumberBIREMEFacade;
use PICOExplorer\Models\DataTransferObject;

class IntegrationResultsNumberController extends ControllerModel implements MainControllerInterface
{

    public function ServiceBind()
    {
        return new ResultsNumberBIREMEFacade();
    }

    public function getMainModel()
    {
        return DataTransferObject::getModel('IntegrationResultsNumber');
    }

}
