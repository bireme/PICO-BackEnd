<?php

namespace LayerEntities;

class ResultQuery {

    private $resultsNumber;
    private $ResultsURL;
    private $query;
    private $baseURL = 'http://pesquisa.bvsalud.org/portal/?q=';

    public function __construct($query) {
        $this->query = $query;
        $this->ResultsURL = $this->baseURL . $query;
    }

    public function setResultsNumber($resultsNumber) {
        $this->resultsNumber = $resultsNumber;
    }

    public function setIntegrationResults($resultsURL, $resultsNumber) {
        $this->resultsNumber = $resultsNumber;
        $this->ResultsURL = $resultsURL;
    }

    public function getQuery() {
        return $this->query;
    }

    public function getResults() {
        return array('resultsNumber' => $this->resultsNumber, 'resultsURL' => $this->ResultsURL,'query' => $this->query);
    }

}

?>