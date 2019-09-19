<?php

namespace PICOExplorer\Http\Controllers\PICO;

use PICOExplorer\Facades\ResultsNumberFacade;
use PICOExplorer\Models\DataTransferObject;

class ResultsNumberController extends ControllerModel implements MainControllerInterface
{

    public function ServiceBind()
    {
        return new ResultsNumberFacade();
    }

    public function getMainModel()
    {
        return DataTransferObject::getModel('ResultsNumber');
    }

}
