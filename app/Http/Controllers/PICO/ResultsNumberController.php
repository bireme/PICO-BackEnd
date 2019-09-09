<?php

namespace PICOExplorer\Http\Controllers\PICO;

use PICOExplorer\Facades\ResultsNumberProcessFacade;
use PICOExplorer\Models\DataTransferObject;

class ResultsNumberController extends ControllerModel implements MainControllerInterface
{

    public function ServiceBind()
    {
        return new ResultsNumberProcessFacade();
    }

    public function getMainModel()
    {
        return DataTransferObject::getModel('ResultsNumber');
    }

}
