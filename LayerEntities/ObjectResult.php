<?php

namespace LayerEntities;

require_once('ObjectKeyword.php');

use LayerEntities\ObjectKeyword;

class ObjectResult {

    private $resultsNumber;
    private $query;
    private $ResultsURL;
    private $title;

    function getTitle() {
        return $this->title;
    }

    public function getResultsNumber() {
        return $this->resultsNumber;
    }

    public function getResultsURL() {
        return $this->ResultsURL;
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
        return array('query'=>$this->query ,'ResultsNumber'=>$this->resultsNumber ,'ResultsURL'=>$this->ResultsURL);
    }

    public function __construct($query, $title) {
        $this->query = $query;
        $this->title = $title;
    }

}

?>