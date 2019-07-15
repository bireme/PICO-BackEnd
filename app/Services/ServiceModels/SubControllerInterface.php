<?php

namespace PICOExplorer\Services\ServiceModels;

use PICOExplorer\Models\MainModels\MainModelsModel;
use PICOExplorer\Services\TimerService\Timer;

interface SubControllerInterface
{

    public function get(MainModelsModel $model,Timer $ParentTimer);

}
