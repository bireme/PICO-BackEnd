<?php

namespace PICOExplorer\Http\Controllers\PICO;

use PICOExplorer\Facades\QueryProcessFacade;
use PICOExplorer\Http\Controllers\PICO\BaseMainController;
use PICOExplorer\Models\MainModels\MainModelsModel;
use PICOExplorer\Models\MainModels\QueryBuildModel;

class QueryBuildController extends BaseMainController
{

    public static final function requestRules(){
        return [

        ];
    }

    public static final function responseRules(){
        return [

        ];
    }

    public function create()
    {
        return new QueryBuildModel();
    }

    public function MainOperation(MainModelsModel $model,array $responseRules, $globalTimer)
    {
        QueryProcessFacade::get($model, $responseRules,$globalTimer);
    }

    public function TestData()
    {
        return [

        ];
    }

}
