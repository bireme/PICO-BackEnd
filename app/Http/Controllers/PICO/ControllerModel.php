<?php

namespace PICOExplorer\Http\Controllers\PICO;

use PICOExplorer\Exceptions\Exceptions\AppError\ContactedComponentReturnedNull;
use PICOExplorer\Exceptions\Exceptions\AppError\ControllerResultsIsNull;
use PICOExplorer\Exceptions\Exceptions\AppError\DataIntroducedInControllerIsNull;
use PICOExplorer\Exceptions\Exceptions\AppError\ExceptionInsideService;
use PICOExplorer\Exceptions\Exceptions\AppError\TheServiceIsNull;
use PICOExplorer\Exceptions\Exceptions\AppError\TheServicelIsNotInstanceOfAdvancedFacade;
use PICOExplorer\Exceptions\Exceptions\ClientError\TheDataSentWasNotJSONEncoded;
use PICOExplorer\Facades\AdvancedFacade;
use PICOExplorer\Facades\ExceptionLoggerFacade;
use PICOExplorer\Services\AdvancedLogger\Exceptions\Models\ClientError;
use PICOExplorer\Services\AdvancedLogger\Traits\TranslatedMessageTrait;
use PICOExplorer\Models\DataTransferObject;
use PICOExplorer\Models\MainModelsModel;
use PICOExplorer\Services\AdvancedLogger\Exceptions\DontCatchException;
use PICOExplorer\Services\AdvancedLogger\Exceptions\Models\CustomException;
use Response;
use Throwable;
use Request;
use Exception;

abstract class ControllerModel
{

    use TranslatedMessageTrait;

    protected $ServicePerformance;
    protected $referer;
    protected $origin;
    protected $Service;


    /**
     * @var DataTransferObject
     */
    protected $DTO;

    public function index(Request $request)
    {
        $this->setOriginRefererAndDTO('index');
        $JSONdata = $request::getContent();
        return $this->RequestCore($JSONdata);
    }

    public function inputtest(string $JSONdata)
    {
        $this->setOriginRefererAndDTO('index');
        return [
            'response' => $this->RequestCore($JSONdata),
            'modeldata' => $this->DTO->getAllDebug(),
        ];
    }

    public function info()
    {
        $this->setOriginRefererAndDTO('info');
        $JSONdata = $this->DTO->getTestData('main');
        return $this->RequestCore($JSONdata);
    }

    public function outerBind(array $data)
    {
        $this->setOriginRefererAndDTO('index');
        return $this->outerCore($data);
    }

    protected function setOriginRefererAndDTO(string $origin)
    {
        $mainModel = $this->getMainModel();
        $this->DTO = new DataTransferObject(get_class($this), $mainModel);
        $tmp = new \ServicePerformanceSV();
        $this->ServicePerformance = $tmp->ServicePerformanceStart($this->DTO, get_class($this));
        $this->origin = $origin;
        $this->referer = $origin . '@' . get_class($this);
    }

    protected function RequestCore(string $JSONdata = null)
    {
        $response = null;
        $wasSuccess = false;
        $res = null;
        try {
            $data = $this->decodeRequestData($JSONdata);
            $res = $this->CoreFunction($data);
            $response = $this->HandleResponse($res);
            $wasSuccess = true;
        } catch (Throwable $ex) {
            $response = $this->HandleFinalException($ex, $res);
        } finally {
            $this->ServicePerformance->ServicePerformanceSummary($wasSuccess);
            return $response;
        }
    }

    protected function HandleFinalException(Throwable $ex, $data)
    {
        $code = $ex->getCode();
        if ($code < 100) {
            $code = 500;
        }
        ExceptionLoggerFacade::ReportException($ex);
        if ($code === 500) {
            $title = 'Server Error';
            $message = 'Internal Server Error';
        } else {
            $message = get_class($ex);
            $title = 'Client Error';
            $dbtext = $this->getTranslatedMessage($message, $ex instanceof CustomException);
            if ($dbtext) {
                $message = $dbtext;
            }
        }
        return Response::json(['Error' => $title . PHP_EOL . PHP_EOL . $message, 'Data' => $data], 200);
    }

    protected function outerCore(array $data = null)
    {
        $wasSuccess = false;
        $res = null;
        try {
            $res = $this->CoreFunction($data);
            $wasSuccess = true;
        } catch (DontCatchException $ex) {
            //
        } finally {
            $this->ServicePerformance->ServicePerformanceSummary($wasSuccess);
        }
        return $res;
    }

    protected function CoreFunction(array $data = null)
    {
        if (!($data)) {
            throw new DataIntroducedInControllerIsNull(['referer' => $this->referer]);
        }
        $this->getService();
        $res = $this->connectToService($data, $this->DTO, $this->referer);
        return $res;
    }

    protected function decodeRequestData(string $JSONEncodedData)
    {
        $data = null;
        try {
            $data = json_decode($JSONEncodedData, true);
        } catch (Exception $ex) {
            throw new TheDataSentWasNotJSONEncoded(['referer' => $this->referer, 'Data' => $JSONEncodedData]);
        }
        return $data;
    }

    protected function connectToService(array $data, DataTransferObject $DTO, string $referer)
    {
        $DTO->setInitialData(__METHOD__ . '@' . get_class($this), $data, 'main');
        $results = null;
        $connectionTimer = null;
        $wasSuccessful = false;
        try {
            $connectionTimer = $this->ServicePerformance->newConnectionTimer(get_class($this) . 'toservice-' . get_class($this) . '-timer');
            $this->Service::get($DTO, null);
            $results = $DTO->getResults('main');
            $wasSuccessful = true;
        } catch (Exception $ex) {
            if ($ex instanceof ClientError) {
                throw $ex;
            } else {
                ExceptionLoggerFacade::ReportException($ex);
                throw new ExceptionInsideService(['caller' => get_class($this), 'target' => get_class($this->Service), 'ErrorInTarget' => $ex->getMessage()], $ex);
            }
        } finally {
            $info = 'Connection Failed';
            if ($wasSuccessful) {
                $info = 'Connection Success. result.size=' . strlen(json_encode($results));
            }
            $connectionTimer->Stop($info);
            if ($wasSuccessful) {
                if (!($results)) {
                    throw new ControllerResultsIsNull(['referer' => $referer]);
                }
            }
        }
        return $results;
    }

    protected function getService()
    {
        $Service = $this->ServiceBind();
        if (!($Service)) {
            throw new TheServiceIsNull(['service' => null, 'controller' => $this->referer]);
        }
        if (!($Service instanceof AdvancedFacade)) {
            $info = get_class($Service) ?? null;
            throw new TheServicelIsNotInstanceOfAdvancedFacade(['service' => $info, 'controller' => $this->referer]);
        }
        $this->Service = $Service;
    }

    protected function getWarnings()
    {
        return null;
    }

    protected function HandleResponse($res = null)
    {
        $response = [];
        $response['Data'] = $res;
        $warnings = $this->getWarnings();
        if ($warnings) {
            $response['warnings'] = $warnings;
        }
        return Response::json($response, 200);
    }


    /**
     * @return PICOServiceFacade
     */
    abstract protected function ServiceBind();

    /**
     * @return MainModelsModel
     */
    abstract public function getMainModel();

}
