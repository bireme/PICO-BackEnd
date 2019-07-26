<?php

namespace PICOExplorer\Services\ServiceModels;

use PICOExplorer\Exceptions\Exceptions\AppError\ServiceResultsIsNull;
use PICOExplorer\Http\Controllers\CustomController;
use PICOExplorer\Models\MainModels\MainModelsModel;
use PICOExplorer\Services\AdvancedLogger\Exceptions\DontCatchException;

abstract class PICOServiceModel extends CustomController
{

    /**
     * @var MainModelsModel
     */
    protected $model;
    protected $responseRules;

    public function Process(){}

    final public function get(MainModelsModel $model, array $responseRules, $ParentTimer = null)
    {
        $this->responseRules=$responseRules;
        $this->ControllerPerformanceStart($model->InitialData,$ParentTimer);
        $this->model = $model;
        $wasSucess=false;
        try {
            $this->Process();
            $wasSucess=true;
        } catch (DontCatchException $ex) {
        } finally {
            $this->ControllerSummary($model->results,$wasSucess);
        }
    }

    protected function setResults(string $referer, array $data)
    {
        if (!($data)) {
            throw new ServiceResultsIsNull(['referer'=>$referer]);
        }

        $this->model->setResults(get_class($this) . ':' . $referer, $data, $this->responseRules);
    }

    protected function UpdateModel(string $referer, array $data, array $rules)
    {
        $this->model->UpdateModel(get_class($this) . ':' . $referer, $data, $rules);
    }

}
