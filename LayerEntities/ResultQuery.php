<?php

namespace LayerEntities;

class ResultQuery {

    private $resultsNumber;
    private $ResultsURL;
    private $query;  

    public function __construct($query) {
        $this->query = $query;
    }

    public function setResultsNumber($resultsNumber) {
        $this->resultsNumber = $resultsNumber;
    }

    public function setResultsURL($ResultsURL) {
        $this->ResultsURL = $ResultsURL;
    }

    public function getQuery() {
        return $this->query;
    }

    public function getResults() {
        return array('resultsNumber' => $this->resultsNumber, 'resultsURL' => $this->ResultsURL);
    }

}

?>