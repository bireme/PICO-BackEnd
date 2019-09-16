<?php

namespace PICOExplorer\Http\Controllers\PICO;

use PICOExplorer\Models\DataTransferObject;

class IntegrationDeCSController extends ControllerModel implements MainControllerInterface
{

    public function ServiceBind()
    {
        return new \DeCSBIREMESV();
    }

    public function getMainModel()
    {
        return DataTransferObject::getModel('IntegrationDeCS');
    }

}
