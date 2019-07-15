<?php

namespace PICOExplorer\Http\Controllers\MainControllers;

use PICOExplorer\Models\MainModels\IntegrationResultsNumberModel;
use PICOExplorer\Models\MainModels\MainModelsModel;
use PICOExplorer\Services\TimerService\Timer;
use ResultsNumberBIREME;

class IntegrationResultsNumberController extends BaseMainController implements MainControllerInterface
{

    public function create()
    {
        return new IntegrationResultsNumberModel();
    }

    public function MainOperation(MainModelsModel $model, Timer $globalTimer)
    {
        ResultsNumberBIREME::get($model, $globalTimer);
    }

    public function TestData()
    {
        return [

        ];
    }

}
