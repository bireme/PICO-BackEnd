<?php

namespace PICOExplorer\Services\ServiceModels;

use PICOExplorer\Http\Traits\ControllerPerformanceTrait;
use PICOExplorer\Http\Traits\ValidationTrait;
use PICOExplorer\Models\MainModels\MainModelsModel;
use PICOExplorer\Services\TimerService\Timer;

abstract class PICOServiceModel implements SubControllerInterface
{

    use ControllerPerformanceTrait;
    use ValidationTrait;

    protected $model;

    final public function get(MainModelsModel $model, Timer $ParentTimer=null)
    {
        $this->ControllerPerformanceStart($ParentTimer);
        $this->model = $model;
        $this->Process();
        $this->ControllerSummary();
    }

    protected function UpdateModel(array $data,bool $replace)
    {
        $this->model->UpdateModel($data,$replace);
    }

}
