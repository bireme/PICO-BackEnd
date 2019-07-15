<?php


namespace PICOExplorer\Http\Controllers\MainControllers;

use PICOExplorer\Models\MainModels\MainModelsModel;
use PICOExplorer\Services\TimerService\Timer;

interface MainControllerInterface
{

    /**
     * @return MainModelsModel
     */
    public function create();

    public function MainOperation(MainModelsModel $model, Timer $globalTimer);

    /**
     * @return array
     */
    public function TestData();

}
