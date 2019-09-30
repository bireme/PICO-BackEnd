<?php

namespace PICOExplorer\Services\AdvancedLogger\Services;

use PICOExplorer\Exceptions\Exceptions\AppError\ErrorInAdvancerTimerService;
use Throwable;

class AdvancedTimer
{

    protected $time;
    protected $locked = false;
    protected $name;
    protected $finaltime;

    public function Start(String $name)
    {
        try {
            $this->time = microtime(true);
            $this->name = $name;
            return $this;
        } catch (Throwable $ex) {
            throw new ErrorInAdvancerTimerService(['errors' => $ex->getMessage()], $ex);
        }
    }

    /**
     * @return int
     */
    public function Stop()
    {
        try {
            if (!($this->locked)) {
                $this->finaltime = (int)((microtime(true) - $this->time) * 1000);
                $this->locked = true;
            }
            return $this->finaltime;
        } catch (Throwable $ex) {
            throw new ErrorInAdvancerTimerService(['errors' => $ex->getMessage()], $ex);
        }
    }

    public function get()
    {
        try {
            return $this->finaltime;
        } catch (Throwable $ex) {
            throw new ErrorInAdvancerTimerService(['errors' => $ex->getMessage()], $ex);
        }
    }

    public function name()
    {
        try {
            return $this->name;
        } catch (Throwable $ex) {
            throw new ErrorInAdvancerTimerService(['errors' => $ex->getMessage()], $ex);
        }
    }

}
