<?php

namespace LayerEntities;

class ResultQuery {

    private $resultsNumber;
    private $ResultsURL;
    private $query;
    private $title;
    private $fields = array('','ti', 'tw', 'mh');
    private $isGlobal;

    public function __construct($ArrayObj, $title, $isGlobal) {
        $this->BuildQuery($ArrayObj);
        $this->title = $title;
        $this->isGlobal = $isGlobal;
    }

    public function isGlobal() {
        return $this->isGlobal;
    }

    public function getTitle() {
        return $this->title;
    }

    private function SetSearchField($item) {
        $fieldnum = $item['field'];
        if ($fieldnum == -1) {
            $field = '';
        } else {
            $field = $this->fields[$fieldnum];
        }

        if (strlen($field) > 0) {
            $msg = $field . ':(' . $item['query'] . ')';
        } else {
            $msg = $item['query'];
        }
        return $msg;
    }

    private function BuildQuery($ArrayObj) {
        $query = '';
        $count = 0;
        $total = count($ArrayObj) - 1;
        foreach ($ArrayObj as $key => $item) {
            $msg = '';
            if ($count > 0) {
                $msg = $msg . ' AND ';
            }

            if (strlen($item['query']) == 0) {
                continue;
            }
            $msg = $msg . $this->SetSearchField($item);
            $query = $query . $msg;
            $count++;
        }
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
        return array('ResultsNumber' => $this->resultsNumber, 'ResultsURL' => $this->ResultsURL);
    }

}

?>