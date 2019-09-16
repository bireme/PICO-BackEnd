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
    protected $conTimers = [];

    /**
     * @var AdvancedTimer
     */
    protected $operationTimer;
    protected $monitoredObject;


    /**
     * @return AdvancedTimer
     */
    public function newConnectionTimer(string $name)
    {
        $timer = $this->Timer('connection-' . (count($this->conTimers)+1) . ':' . $name);
        array_push($this->conTimers, $timer);
        return $timer;
    }

    public function ServicePerformanceStart(DataTransferObject $DTO, string $monitoredObject)
    {
        $this->operationTimer = $this->Timer(get_class($this) . ':Operation');
        $this->DTO = $DTO;
        $this->monitoredObject = $monitoredObject;
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
        $Adecuability = $this->ValidateInterfaces($InputOutput);
        $this->operationTimer->Stop();
        $Timers = $this->getTimers();
        $data = [
            'Success' => $wasSuccess,
            'Timers' => $Timers,
            'InputOutput' => $InputOutput,
            'Adecuability' => $Adecuability,
        ];
        $title = $info . ': ' . $this->monitoredObject;
        $this->LogMessage('Performance', $level, $title, $info, $data, null);
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

}
