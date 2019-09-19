<?php

namespace PICOExplorer\Http\Controllers\PICO;

use PICOExplorer\Facades\QueryBuildFacade;
use PICOExplorer\Models\DataTransferObject;

class QueryBuildController extends ControllerModel
{

    public function ServiceBind()
    {
        return new QueryBuildFacade();
    }

    public function getMainModel()
    {
        return DataTransferObject::getModel('QueryBuild');
    }

}
