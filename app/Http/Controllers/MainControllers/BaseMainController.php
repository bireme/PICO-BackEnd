<?php


namespace PICOExplorer\Http\Controllers\MainControllers;

use PICOExplorer\Http\Controllers\Controller;
use PICOExplorer\Http\Traits\ControllerPerformanceTrait;
use Request;
use Response;

abstract class BaseMainController extends Controller
{
    use ControllerPerformanceTrait;

    public function index(Request $request)
    {
        $this->ControllerPerformanceStart(null);
        $model = $this->create();
        $data = json_decode($request->getContent(), true);
        if(get_class($this)==='IntegrationResultsNumberController'){
            return $data;
        }
        $model->UpdateModel($data, true);
        $globalTimer = $this->getGlobalTimer();
        $this->MainOperation($model, $globalTimer);
        $this->ControllerSummary();
        return $this->sendResponse($model);
    }

    public function Info()
    {
        $this->ControllerPerformanceStart(null);
        $model = $this->create();
        $data = $this->TestData();
        $model->UpdateModel($data, true);
        $globalTimer = $this->getGlobalTimer();
        $this->MainOperation($model, $globalTimer);
        return $this->ViewInfo($model);
    }


    protected function ViewInfo($model)
    {
        $data = $model->getResults(true);
        return $data;
    }

    protected function sendResponse($model)
    {
        $data = $model->getResults(false);
        return Response::json([
            'data' => $data,
        ], 200);

    }

}
