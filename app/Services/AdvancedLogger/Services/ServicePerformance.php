<?php

namespace PICOExplorer\Services\AdvancedLogger\Services;

use PICOExplorer\Facades\AdvancedLoggerFacade;
use PICOExplorer\Facades\SpecialValidatorFacade;
use PICOExplorer\Facades\TimerServiceFacade;
use PICOExplorer\Models\DataTransferObject;

class ServicePerformance
{

    /**
     * @var DataTransferObject
     */

    protected $DTO;
    protected $timers = [];
    protected $conTimers = [];
    protected $operationTimer;
    protected $monitoredObject;


    /**
     * @return TimerServiceFacade
     */
    public function newConnectionTimer(string $name)
    {
        $timer = $this->Timer('connection-' . count($this->conTimers) . ':' . $name);
        array_push($this->conTimers, $timer);
        return $timer;
    }

    public function ServicePerformanceStart(DataTransferObject $DTO, string $monitoredObject)
    {
        $this->DTO = $DTO;
        $this->monitoredObject = $monitoredObject;
        $this->BuildOperationTimer();
        return $this;
    }

    public function ServicePerformanceSummary(bool $wasSuccess)
    {
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
        $Timers = $this->getTimers();
        $Adecuability = $this->ValidateInterfaces($InputOutput);
        $data = [
            'Success' => $wasSuccess,
            'Timers' => $Timers,
            'InputOutput' => $InputOutput,
            'Adecuability' => $Adecuability,
        ];
        $title = $info . ': ' . $this->monitoredObject;
        $this->LogMessage('Performance', $level, $title, $info, $data, null);
        $this->ClearTimers();
    }

    ///////////////////////////////////////////////////////////
    /// PRIVATE FUNCTIONS
    ////////////////////////////////////////////////////////

    private function getTimers()
    {
        $OpTime = $this->operationTimer->get();
        $ConsTime = 0;
        $PerConTime = [];
        foreach ($this->conTimers as $timer) {
            $time = $timer->get();
            $ConsTime += $time;
            $PerConTime[$timer->name()] = $time;
        }
        return [
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
     * @return TimerServiceFacade
     */
    private function Timer($TimerName)
    {
        $Timer = TimerServiceFacade::Start($TimerName);
        array_push($this->timers, $Timer);
        return $Timer;
    }

    private function BuildOperationTimer()
    {
        $timer = $this->Timer(get_class($this) . ':Operation');
        $this->operationTimer = $timer;
    }

    private function ClearTimers()
    {
        foreach ($this->timers as $timer) {
            unset($timer);
        }
    }

}
