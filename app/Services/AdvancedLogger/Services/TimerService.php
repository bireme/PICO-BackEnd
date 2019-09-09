<?php

namespace PICOExplorer\Services\AdvancedLogger\Services;

class TimerService
{

    protected $time;
    protected $locked = false;
    protected $name;

    public function Start($name)
    {
        $this->name = $name;
        $this->time = microtime(true);
        return $this;
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

    public function get()
    {
        if (!($this->locked)) {
            return $this->Stop();
        }
        return $this->time;
    }

    public function name()
    {
        return $this->name;
    }

}
