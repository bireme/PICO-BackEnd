<?php

namespace PICOExplorer\Services\AdvancedLogger\Services;

use PICOExplorer\Facades\AdvancedLoggerFacade;

class TimerService
{
    /**
     * @var Timer
     */
    protected $OperationTimer;
    /**
     * @var array|Timer
     */
    protected $LocalTimers = [];
    /**
     * @var int
     */
    protected $connectionTime = 0;


    /**
     * @var TimerService
     */
    protected $ParentTimer;

    /**
     * @var bool
     */
    protected $finished;



    public function build(string $controllername,array $InitialData,Timer $ParentTimer = null)
    {
        $title = 'Starting '.$controllername;
        $MainData = ['title' => $title,
            'info' => null,
            'data' => $InitialData,
            'stack' => null,
        ];
        AdvancedLoggerFacade::AdvancedLog('Operations', 'info', $MainData);
        $this->ParentTimer = $ParentTimer;
        $this->OperationTimer = new Timer();
        return $this;
    }

    /**
     * @param $instance
     */
    public function Summary($instance,$results,$wasSucess)
    {
        if ($this->finished == true) {
            return;
        }
        $globalTime = $this->OperationTimer->Stop();
        $connectionsTime = $this->getTimeSpentInConnections();
        $operationalTime = $globalTime - $connectionsTime;
        $controller = get_class($instance);
        $data=[];
        $data['$operationalTime'] = $operationalTime;
        if (count($this->LocalTimers) > 0) {
            $data['connectionsTime'] = $connectionsTime;
        }
        $data['results']=$results;
        $pretitle='[Error] ';
        if($wasSucess){
            $pretitle='[Success] ';
        }
        $title = $pretitle.'Performance: ' . $controller;
        $MainData = ['title' => $title,
            'info' => null,
            'data' => $data,
            'stack' => null,
        ];
        AdvancedLoggerFacade::AdvancedLog('Operations', 'info', $MainData);
        $this->finished = true;
    }

    /**
     * @return Timer
     */
    public function AddConnectionTimer()
    {
        $timer = new Timer();
        array_push($this->LocalTimers, $timer);
        return $timer;
    }

    /**
     * @param TimerService $timer
     */
    public function AddParentTimerService(TimerService $timer)
    {
        $this->ParentTimer = $timer;
    }

    /**
     * @return int
     */
    public function getTimeSpentInConnections()
    {
        $time = 0;
        foreach ($this->LocalTimers as $localTimer) {
            $time = $time + $localTimer->Stop();
        }
        $this->AddConnectionTimeToParent($time);
        return $time;
    }

    public function AddTimeSpentInConnections(int $time)
    {
        $this->connectionTime = $this->connectionTime + $time;
        $this->AddConnectionTimeToParent($time);
    }


    private function AddConnectionTimeToParent(int $time)
    {
        if (is_a($this->ParentTimer, 'TimerServiceFacade')) {
            $this->ParentTimer->AddTimeSpentInConnections($time);
        }
    }

    /**
     * @return Timer
     */
    public function getGlobalTimer()
    {
        return $this->OperationTimer;
    }

}
