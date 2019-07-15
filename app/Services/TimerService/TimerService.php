<?php

namespace PICOExplorer\Services\TimerService;

use PICOExplorer\Http\Traits\AdvancedLoggerTrait;

class TimerService
{
    use AdvancedLoggerTrait;

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

    /**
     * TimerService constructor.
     * @param Timer|null $ParentTimer
     */
    public function __construct(Timer $ParentTimer=null)
    {
        $this->ParentTimer= $ParentTimer;
        $this->OperationTimer = new Timer();
    }

    /**
     * @param $instance
     */
    public function Summary($instance)
    {
        if ($this->finished == true) {
            return;
        }
        $globalTime = $this->OperationTimer->Stop();
        $connectionsTime = $this->getTimeSpentInConnections();
        $operationalTime = $globalTime - $connectionsTime;
        $controller =  get_class($instance);

        $dataArray=[
            $controller =>[
                '$operationalTime' => $operationalTime,
            ]
        ];
        if (count($this->LocalTimers) > 0) {
            $dataArray[$controller]['connectionsTime'] = $connectionsTime;
        }

        $this->AdvancedLog('Performance','info','Controller Performance',null,$dataArray,null);
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
        $this->ParentTimer= $timer;
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
        if ($this->ParentTimer) {
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
