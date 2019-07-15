<?php

namespace PICOExplorer\Http\Controllers\MainControllers;

use PICOExplorer\Models\MainModels\IntegrationDeCSModel;
use DeCSBIREME;
use PICOExplorer\Models\MainModels\MainModelsModel;
use PICOExplorer\Services\TimerService\Timer;

class IntegrationDeCSController extends BaseMainController implements MainControllerInterface
{

    public function create()
    {
        return new IntegrationDeCSModel();
    }

    public function MainOperation(MainModelsModel $model, Timer $globalTimer)
    {
        DeCSBIREME::get($model, $globalTimer);
    }

    public function TestData()
    {
        return [

        ];
    }

}
