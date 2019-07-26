<?php

namespace PICOExplorer\Services\AdvancedLogger\Services;

class Timer
{

    protected $time;
    protected $locked = false;

    public function __construct()
    {
        $this->time = microtime(true);
    }

    /**
     * @return int
     */
    public function Stop()
    {
        if (!($this->locked)) {
            $this->time = (int)((microtime(true) - $this->time) * 1000);
            $this->locked = true;
        }
        return $this->time;
    }

}
