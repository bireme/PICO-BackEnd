<?php

namespace PICOExplorer\Services\AdvancedLogger\Services;

class AdvancedTimer
{

    protected $time;
    protected $locked = false;
    protected $name;
    protected $finaltime;

    public function Start(String $name)
    {
        $this->time = microtime(true);
        $this->name = $name;
        return $this;
    }

    /**
     * @return int
     */
    public function Stop()
    {
        if (!($this->locked)) {
            $this->finaltime = (int)((microtime(true) - $this->time) * 1000);
            $this->locked = true;
        }
        return $this->finaltime;
    }

    public function get()
    {
        return $this->finaltime;
    }

    public function name()
    {
        return $this->name;
    }

}
