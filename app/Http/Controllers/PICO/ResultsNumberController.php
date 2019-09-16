<?php

namespace PICOExplorer\Http\Controllers\PICO;

use PICOExplorer\Models\DataTransferObject;

class ResultsNumberController extends ControllerModel implements MainControllerInterface
{

    public function ServiceBind()
    {
        return new \ResultsNumberProcessSV();
    }

    public function getMainModel()
    {
        return DataTransferObject::getModel('ResultsNumber');
    }

}
