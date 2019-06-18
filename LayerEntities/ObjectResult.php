<?php

namespace LayerEntities;

require_once('ResultQuery.php');

use LayerEntities\ResultQuery;

class ObjectResult {

    private $resultQueryList;

    public function __construct($ResultQueryRequest) {
        $this->resultQueryList = array();
        foreach ($ResultQueryRequest as $key => $ResultQueryString) {
            $this->CreateResultQueryObject($ResultQueryString, $key);
        }
    }

    public function CreateResultQueryObject($query, $key) {
        $ResultQueryObject = new ResultQuery($query);
        $this->resultQueryList[$key] = $ResultQueryObject;
    }

    public function getResultQueryList() {
        return $this->resultQueryList;
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