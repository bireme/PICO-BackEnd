<?php

namespace PICOExplorer\Http\Controllers\PICO;

use PICOExplorer\Facades\KeywordManagerFacade;
use PICOExplorer\Models\DataTransferObject;

class KeywordManagerController extends ControllerModel implements MainControllerInterface
{

    public function ServiceBind()
    {
        return new KeywordManagerFacade();
    }

    public function getMainModel()
    {
        return DataTransferObject::getModel('KeywordManager');
    }

}
