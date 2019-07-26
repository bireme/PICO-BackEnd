<?php

namespace PICOExplorer\Http\Controllers\PICO;

use Illuminate\Support\Facades\Facade;
use Illuminate\Support\Facades\View;
use PICOExplorer\Exceptions\Exceptions\AppError\ControllerResultsIsNull;
use PICOExplorer\Exceptions\Exceptions\AppError\ServiceEntryPointIsNotAFacade;
use PICOExplorer\Exceptions\Exceptions\AppError\TheModelIsNotInstanceOfMainModelsModel;
use PICOExplorer\Exceptions\Exceptions\AppError\TheModelIsNull;
use PICOExplorer\Exceptions\Exceptions\AppError\TheServiceIsNull;
use PICOExplorer\Exceptions\Exceptions\AppError\TheServicelIsNotInstanceOfPICOServiceFacade;
use PICOExplorer\Exceptions\Exceptions\ClientError\TheDataSentWasNotJSONEncoded;
use PICOExplorer\Facades\PICOServiceFacade;
use PICOExplorer\Http\Controllers\CustomController;
use PICOExplorer\Models\MainModels\MainModelsModel;
use PICOExplorer\Services\AdvancedLogger\Exceptions\Models\CustomException;
use PICOExplorer\Services\AdvancedLogger\Traits\TranslatedMessageTrait;
use Throwable;
use PICOExplorer\Services\AdvancedLogger\Traits\SpecialValidator;
use Request;
use Response;
use Exception;

abstract class BaseMainController extends CustomController
{

    use TranslatedMessageTrait;
    use SpecialValidator;

    public function index(Request $request)
    {
        return $this->OperationHandler('index', $request);
    }

    public function Info()
    {
        $data = $this->TestData();
        return $this->OperationHandler('Info', $data);
    }

    protected function ErrorViewMaker(int $code, string $title, string $message)
    {
        $ErrData = [
            'code' => $code,
            'title' => $title,
            'message' => $message,
        ];
        return View::make('vendor.laravel-log-viewer.errortxt')->with(['Data' => $ErrData]);
    }

    protected function OperationHandler(string $operation, $data)
    {
        $res = null;
        $status = 500;
        try {
            if ($operation !== 'Info') {
                $data = $data->getContent();
            }
            $res = $this->core($data);
            $status = 200;
            return $this->ResponseHandler($operation, $res, $status);
        } catch (Throwable $ex) {
            return $this->HandleFinalException($ex, $operation, $res, $status);
        }
    }

    protected function getRequestWarnings(){
        return null;
        //FALTA ESTA PARTE
    }

    protected function ResponseHandler(string $operation, $res, int $status)
    {
        $Warnings = $this->getRequestWarnings();
        if ($operation !== 'Info') {
            $response = ['Data' => $res];
            if ($Warnings) {
                //FALTA ESTA PARTE
            }
            return Response::json($response, $status);
        } else {
            $response = $res;
            if ($Warnings) {
                $warningtxt=NULL;
                //FALTA ESTA PARTE
                $response = 'Warnings' . PHP_EOL . $warningtxt . PHP_EOL . PHP_EOL . $response;
            }
            return $response;
        }
    }

    protected function HandleFinalException(Throwable $ex, string $operation, $data)
    {
        $code = $ex->getCode();
        if ($code < 100) {
            $code = 500;
        }
        if ($operation !== 'Info') {
            if ($code < 499) {
                $message = get_class($ex);
                $title = 'Client Error';
                $dbtext = $this->getTranslatedMessage($message, $ex instanceof CustomException);
                if ($dbtext) {
                    $message = $message . PHP_EOL . $dbtext;
                }
            } else {
                $title = 'Server Error';
                $message = 'Internal Server Error';
            }
            return Response::json(['Error' => ['title' => $title, 'message' => $message], 'Data' => $data], $code);
        } else {
            if ($code < 499) {
                $title = 'Client Error';
                if (!(is_string($data))) {
                    $data = json_encode($data);
                }
                $message = get_class($ex) . PHP_EOL . $data;
            } else {
                $title = 'Server Error';
                $message = 'Internal Server Error';
            }
            return $this->ErrorViewMaker($code, $title, $message);
        }
    }

    public function core(string $JSONEncodedData)
    {
        $initialData = null;
        try {
            $data = json_decode($JSONEncodedData, true);
            $initialData = ['InitialData' => $data];
        } catch (Exception $ex) {
            throw new TheDataSentWasNotJSONEncoded(['Model' => get_class($this), 'Data' => $JSONEncodedData]);
        }
        $this->ControllerPerformanceStart($initialData);
        $model = $this->ControllerProcess($initialData);
        $this->ControllerSummary($model->results, (!!$data));
        return $this->ValidateResults($model);
    }

    protected function ValidateResults(MainModelsModel $model)
    {
        $res = $model->getResults();
        $referer = 'core@' . get_class($this);
        if (!($res)) {
            throw new ControllerResultsIsNull(['referer' => $referer]);
        }
        $ruleArr = $this->responseRules();
        $this->SpecialValidate($res, $ruleArr, $referer, get_class($this));
        return $res;
    }

    /**
     * @return string
     */
    abstract protected function TestData();

    /**
     * @return array
     */
    abstract static protected function requestRules();

    abstract static protected function responseRules();

    /**
     * @return MainModelsModel
     */
    abstract protected function create();

    protected function ControllerProcess($data)
    {
        $ControllerData = $this->create();
        $model = $ControllerData['model'] ?? null;
        $Service = $ControllerData['service'] ?? null;
        $controllerinfo = get_class($this);
        if (!($model)) {
            throw new TheModelIsNull(['model' => null, 'controller' => $controllerinfo]);
        }
        if (!($Service)) {
            throw new TheServiceIsNull(['service' => null, 'controller' => $controllerinfo]);
        }
        if (!($model instanceof MainModelsModel)) {
            $info = get_class($Service) ?? null;
            throw new TheModelIsNotInstanceOfMainModelsModel(['model' => $info, 'controller' => $controllerinfo]);
        }
        if (!($Service instanceof PICOServiceFacade)) {
            $info = get_class($Service) ?? null;
            throw new TheServicelIsNotInstanceOfPICOServiceFacade(['service' => $info, 'controller' => $controllerinfo]);
        }
        if (!($Service instanceof Facade)) {
            $info = get_class($Service) ?? null;
            throw new ServiceEntryPointIsNotAFacade(['service' => $info, 'controller' => $controllerinfo]);
        }
        $ruleArr = $this->requestRules();
        $model->UpdateModel(__METHOD__ . '@' . get_class($this), $data, $ruleArr);
        $globalTimer = $this->getGlobalTimer();
        $responseRules = $this::responseRules();
        $this->ConnectToService($Service, $model, $responseRules, $globalTimer);
        return $model;
    }

    protected function ConnectToService(PICOServiceFacade $Service, MainModelsModel $model, array $responseRules, $globalTimer)
    {
        $Service::get($model, $responseRules, $globalTimer);
    }

}
