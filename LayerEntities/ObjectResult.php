<?php

namespace LayerEntities;

require_once('ResultQuery.php');

use LayerEntities\ResultQuery;

class ObjectResult {

    private $ConnectionTime;
    private $resultQueryList;

    public function setConnectionTime(int $time) {
        $this->ConnectionTime += $time;
    }

    public function getConnectionTime() {
        return $this->ConnectionTime;
    }

    public function AddResultQueryObject($Object, $key) {
        $this->resultQueryList[$key] = $Object;
    }

    public function getResultQueryList() {
        return $this->resultQueryList;
    }

    public function __construct() {
        $this->ConnectionTime = 0;
    }

    public function getResults() {
        $result = array();
        foreach ($this->resultQueryList as $key => $ResultQuery) {
            $result[$key] = $ResultQuery->getResults();
        }
        return $result;
    }

}

?>