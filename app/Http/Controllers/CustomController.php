<?php

namespace PICOExplorer\Http\Controllers;

use PICOExplorer\Services\AdvancedLogger\Services\Timer;
use PICOExplorer\Services\AdvancedLogger\Services\TimerService;
use PICOExplorer\Facades\TimerServiceFacade;

class CustomController
{
    /**
     * @var TimerServiceFacade
     */
    protected $TimerService=null;

    public function ControllerPerformanceStart(array $InitialData,Timer $ParentTimer=null){
        $this->TimerService = TimerServiceFacade::build(get_class($this),$InitialData,$ParentTimer);
    }

    public function CreateConnectionTimer(){
        return $this->TimerService->AddConnectionTimer();
    }

    public function AddParentTimerService(TimerService $timer)
    {
        $this->TimerService->AddParentTimerService($timer);
    }

    public function ControllerSummary($results,$wasSucess){
        $this->TimerService->Summary($this,$results,$wasSucess);
    }


    /**
     * @return Timer
     */
    public function getGlobalTimer(){
        if($this->TimerService){
            return $this->TimerService->getGlobalTimer();
        }else{
            return null;
        }
    }
}
