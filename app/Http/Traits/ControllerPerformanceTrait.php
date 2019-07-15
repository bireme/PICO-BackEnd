<?php


namespace PICOExplorer\Http\Traits;

use PICOExplorer\Services\TimerService\Timer;
use PICOExplorer\Services\TimerService\TimerService;

trait ControllerPerformanceTrait
{
    /**
     * @var TimerService
     */
    protected $TimerService;

    public function ControllerPerformanceStart(Timer $ParentTimer=null){
        $this->TimerService = new TimerService($ParentTimer);
    }

    public function CreateConnectionTimer(){
        return $this->TimerService->AddConnectionTimer();
    }

    public function AddParentTimerService(TimerService $timer)
    {
        $this->TimerService->AddParentTimerService($timer);
    }

    public function ControllerSummary(){
        $this->TimerService->Summary($this);
    }


    /**
     * @return Timer
     */
    public function getGlobalTimer(){
        return $this->TimerService->getGlobalTimer();
    }

}
