<?php

namespace LayerEntities;

class ObjectQuery {

    private $previousresults;
    private $SelectedDescriptors;
    private $ImproveSearchArr;
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

    public function __construct($results, $SelectedDescriptors, $ImproveSearchArr, $EqName, $QuerySplit) {
        $this->previousresults = $results;
        $this->QuerySplit = $QuerySplit;
        $this->SelectedDescriptors = $SelectedDescriptors;
        $this->ImproveSearchArr = $ImproveSearchArr;
        $this->EqName = $EqName;
    }

    public function getPreviousResults() {
        return $this->previousresults;
    }

    function getQuerySplit() {
        return $this->QuerySplit;
    }

    function getImproveSearchArr() {
        return $this->ImproveSearchArr;
    }

    public function getResults() {
        return array(
            'newQuery' => $this->query
        );
    }

}

?>