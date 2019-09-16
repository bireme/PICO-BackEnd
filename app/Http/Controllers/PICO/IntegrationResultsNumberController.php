<?php

namespace PICOExplorer\Http\Controllers\PICO;

use PICOExplorer\Models\DataTransferObject;

class IntegrationResultsNumberController extends ControllerModel implements MainControllerInterface
{

    public function ServiceBind()
    {
        return new \ResultsNumberBIREMESV();
    }

    public function getMainModel()
    {
        return DataTransferObject::getModel('IntegrationResultsNumber');
    }

}
