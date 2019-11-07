<?php

namespace PICOExplorer\Services\AdvancedLogger\Services;

use PICOExplorer\Exceptions\Exceptions\AppError\ErrorInServicePerformanceService;
use PICOExplorer\Facades\AdvancedLoggerFacade;
use PICOExplorer\Facades\SpecialValidatorFacade;
use PICOExplorer\Models\DataTransferObject;
use Throwable;

class ServicePerformance
{

    /**
     * @var DataTransferObject
     */

    protected $DTO;
    protected $conTimers = [];

    /**
     * @var AdvancedTimer
     */
    protected $operationTimer;
    protected $monitoredObject;


    public function newConnectionTimer(string $name)
    {
        try {
            $timer = $this->Timer('connection-' . (count($this->conTimers) + 1) . ':' . $name);
            array_push($this->conTimers, $timer);
            return $timer;
        } catch (Throwable $ex) {
            throw new ErrorInServicePerformanceService(['errors' => $ex->getMessage()], $ex);
        }
    }

    public function ServicePerformanceStart(DataTransferObject $DTO, string $monitoredObject)
    {
        try {
            if (!($this->isInProduction())) {
                $this->operationTimer = $this->Timer(get_class($this) . ':Operation');
            }
            $this->DTO = $DTO;
            $this->monitoredObject = $monitoredObject;
            return $this;
        } catch (Throwable $ex) {
            throw new ErrorInServicePerformanceService(['errors' => $ex->getMessage()], $ex);
        }
    }

    public function ServicePerformanceSummary(bool $wasSuccess)
    {
        try {
            if ((!($this->isInProduction())) || $wasSuccess == false) {
                if ($wasSuccess) {
                    $info = 'Success ';
                    $level = 'info';
                } else {
                    $info = 'Error ';
                    $level = 'error';
                }
                $InputOutput = [
                    'Input' => $this->DTO->getInitialData(null, true),
                    'Output' => $this->DTO->getResults(null, true),
                ];
                $data = [
                    'Success' => $wasSuccess,
                    'InputOutput' => $InputOutput,

                ];

                if (!($this->isInProduction())) {
                    $Adecuability = $this->ValidateInterfaces($InputOutput);
                    $data['Adecuability'] = $Adecuability;
                }
                if (!($this->isInProduction())) {
                    $this->operationTimer->Stop();
                    $Timers = $this->getTimers();
                    $data['Timers'] = $Timers;
                }
                $title = $info . ': ' . $this->monitoredObject;
                $this->LogMessage('Performance', $level, $title, $info, $data, null);
            }
        } catch (Throwable $ex) {
            throw new ErrorInServicePerformanceService(['errors' => $ex->getMessage()], $ex);
        }
    }

    ///////////////////////////////////////////////////////////
    /// PRIVATE FUNCTIONS
    ////////////////////////////////////////////////////////

    private function getTimers()
    {
        $total = $this->operationTimer->get();
        unset($this->operationTimer);
        $ConsTime = 0;
        $PerConTime = [];
        foreach ($this->conTimers as $timer) {
            $time = $timer->get();
            $PerConTime[$timer->name()] = $time;
            unset($timer);
            $ConsTime += $time;
        }
        $OpTime = $total - $ConsTime;
        return [
            'Total' => $total,
            'Operational' => $OpTime,
            'Connections' => $ConsTime,
            'PerConnection' => $PerConTime,
        ];
    }

    private function Validator(array $ruleArr, string $info, $data = null)
    {
        $validateStatus = false;
        try {
            if (!($data)) {
                return false;
            }
            if ($data === 'Unset') {
                return false;
            }
            SpecialValidatorFacade::SpecialValidate($data, $ruleArr, $info . '@ValidateInterfaces@' . get_class($this), get_class($this));
            $validateStatus = true;
        } catch (\Throwable $ex) {
            $validateStatus = $ex->getMessage();
        }
        return $validateStatus;
    }

    private function ValidateInterfaces(array $InputOutput)
    {
        $requestVal = $this->Validator($this->DTO->requestRules(), 'Request', $InputOutput['Input']);
        $responseVal = $this->Validator($this->DTO->responseRules(), 'Response', $InputOutput['Output']);
        return [
            'request' => $requestVal,
            'response' => $responseVal,
        ];
    }

    private function LogMessage($channel, $level, $title, $info, $data, $stack)
    {
        $MainData = ['title' => $title,
            'info' => $info,
            'data' => $data,
            'stack' => $stack,
        ];
        AdvancedLoggerFacade::AdvancedLog($channel, $level, $MainData);
    }


    /**
     * @return AdvancedTimer
     */
    private function Timer($TimerName)
    {
        $timer = new AdvancedTimer();
        $timer->Start($TimerName);
        return $timer;
    }

    private function isInProduction()
    {
        if (app()->environment() === 'production') {
            return true;
        }
    }

}
