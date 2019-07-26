<?php

namespace PICOExplorer\Http\Controllers\PICO;

use PICOExplorer\Models\MainModels\IntegrationDeCSModel;
use PICOExplorer\Facades\DeCSBIREMEFacade;
use PICOExplorer\Models\MainModels\MainModelsModel;

class IntegrationDeCSController extends BaseMainController implements MainControllerInterface
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
        return new IntegrationDeCSModel();
    }

    public function MainOperation(MainModelsModel $model,array $responseRules, $globalTimer)
    {
        DeCSBIREMEFacade::get($model, $responseRules,$globalTimer);
    }

    public function TestData()
    {
        return [

        ];
    }

}
