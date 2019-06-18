<?php

namespace LayerEntities;

class ObjectQuery {

    private $previousresults;
    private $SelectedDescriptors;
    private $ImproveSearchQuery;
    private $EqName;
    private $EquationNoImprovement;
    private $query;
    private $QuerySplit;

    public function getSelectedDescriptors() {
        return $this->SelectedDescriptors;
    }

    public function getEquationNoImprovement() {
        return $this->EquationNoImprovement;
    }

    public function setEquationNoImprovement($EquationNoImprovement) {
        $this->EquationNoImprovement = $EquationNoImprovement;
    }

    public function getQuery() {
        if ($this->query) {
            return $this->query;
        }
        return $this->getEquationNoImprovement();
    }

    public function setQuery($query) {
        $this->query = $query;
    }

    public function __construct($results, $SelectedDescriptors, $ImproveSearchQuery, $EqName, $QuerySplit) {
        $this->previousresults = $results;
        $this->QuerySplit = $QuerySplit;
        $this->SelectedDescriptors = $SelectedDescriptors;
        $this->ImproveSearchQuery = $ImproveSearchQuery;
        $this->EqName = $EqName;
    }

    public function getPreviousResults() {
        return $this->previousresults;
    }

    function getQuerySplit() {
        return $this->QuerySplit;
    }

    function getImproveSearchQuery() {
        return $this->ImproveSearchQuery;
    }

    public function getResults() {
        return array(
            'newQuery' => $this->query
        );
    }

}

?>