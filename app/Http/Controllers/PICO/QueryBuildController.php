<?php

namespace PICOExplorer\Http\Controllers\PICO;

use PICOExplorer\Models\DataTransferObject;

class QueryBuildController extends ControllerModel
{

    public function ServiceBind()
    {
        return new \QueryBuildSV();
    }

    public function getMainModel()
    {
        return DataTransferObject::getModel('QueryBuild');
    }

}
