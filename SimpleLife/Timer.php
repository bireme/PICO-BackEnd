<?php

////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////
///COPYRIGHT DANIEL NIETO-GONZALEZ--- USO LIBRE, CON MODIFICACIONES PERMITIDAS//
////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////

namespace SimpleLife;

class Timer {

    private $start_time;

    public function __construct() {
        $this->start_time = microtime(true);
    }

    public function Stop() {
        $time_elapsed_secs = (int) ((microtime(true) - $this->start_time) * 1000);
        return $time_elapsed_secs;
    }

}

class TimeSum {

    private $count;

    public function __construct() {
        $this->count = 0;
    }

    public function AddTime(int $time) {
        $this->count += $time;
    }

    public function Stop() {
        Return $this->count;
    }

}

?>