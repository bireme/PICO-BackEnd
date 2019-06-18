<?php

namespace LayerBusiness;

class ControllerQueryBuilder {

    private $ObjectQuery;
    private $SimpleLifeMessage;
    private $DeCSqueryProcessor;

    public function __construct($ObjectQuery, $DeCSqueryProcessor) {
        $this->ObjectQuery = $ObjectQuery;
        $this->DeCSqueryProcessor = $DeCSqueryProcessor;
    }

    ///////////////////////////////////////////////////////////////////
    //PUBLIC FUNCTIONS
    ///////////////////////////////////////////////////////////////////

    public function BuildnewQuery() {
        $results = $this->ObjectQuery->getPreviousResults();
        $QuerySplit = $this->ObjectQuery->getQuerySplit();
        $SelectedDescriptors = $this->ObjectQuery->getSelectedDescriptors();
        return $this->BuildEquationNoImprovement($results, $SelectedDescriptors, $QuerySplit);
    }

    public function ImproveSearch() {
        $EquationNoImprovement = $this->ObjectQuery->getEquationNoImprovement();
        $ImproveSearchQuery = $this->ObjectQuery->getImproveSearchQuery();
        $QueryProcessed=NULL;
        $this->DeCSqueryProcessor->ProcessQuery($ImproveSearchQuery,$QueryProcessed);
        $this->ImproveBasicEquation($EquationNoImprovement, $ImproveSearchQuery, $QueryProcessed);
    }

    ///////////////////////////////////////////////////////////////////
    //INNER FUNCTIONS
    /////////////////////////////////////////////////////////////////// 

    private function ImproveBasicEquation($EquationNoImprovement, $ImproveSearchQuery, $ImproveSearchArrAnalyzed) {
        $this->ObjectQuery->setQuery($EquationNoImprovement);
    }

    private function BuildEquationNoImprovement($results, $SelectedDescriptors, $QuerySplit) {
        $EquationNoImprovement = '';
        $Ops = array('or', 'and', 'not');
        $Seps = array('(', ')', ' ', ':');
        foreach ($QuerySplit as $QuerySplitItem) {
            $type = $QuerySplitItem['type'];
            $value = $QuerySplitItem['value'];
            if ($type == 'key' || $type == 'keyrep' || $type == 'keyexplored') {
                $EquationNoImprovement = $EquationNoImprovement . $this->BuildKeyWordEquation($value, $SelectedDescriptors);
            } else {
                if (in_array($value, $Ops)) {
                    $value = strtoupper($value);
                } else {
                    if (!(in_array($value, $Seps))) {
                        $value = ucfirst($value);
                        if (strpos($value, ' ') !== false) {
                            $value = '"' . $value . '"';
                        }
                    }
                }
                $EquationNoImprovement = $EquationNoImprovement . $value;
            }
        }
        if (strlen($EquationNoImprovement) != 0) {
            $EquationNoImprovement = '(' . $EquationNoImprovement . ')';
        }
        $this->ObjectQuery->setEquationNoImprovement($EquationNoImprovement);
    }

    private function BuildKeyWordEquation($keyword, $SelectedDescriptors) {
        $keywordEquation = ucfirst($keyword);
        if (!(array_key_exists($keyword, $SelectedDescriptors))) {
            throw new SimpleLifeException(new \SimpleLife\KeywordNotExistsInSelected($keyword, $SelectedDescriptors));
        }
        $KeywordObj = $SelectedDescriptors[$keyword];
        foreach ($KeywordObj as $termObj) {

            if (strlen($keywordEquation) != 0) {
                $keywordEquation = $keywordEquation . ' OR ';
            }
            $keywordEquation = $keywordEquation . $this->BuildTermEquation($termObj);
        }
        if (strlen($keywordEquation) != 0) {
            $keywordEquation = '(' . $keywordEquation . ')';
        }
        return $keywordEquation;
    }

    private function BuildTermEquation($termObj) {
        $TermEquation = '';
        foreach ($termObj as $DeCS) {
            if (strpos($DeCS, ' ') !== false) {
                $DeCS = '"' . $DeCS . '"';
            }
            if (strlen($TermEquation) > 0) {
                $TermEquation = $TermEquation . ' OR ';
            }
            $TermEquation = $TermEquation . ucfirst($DeCS);
        }
        if (strlen($TermEquation) > 0) {
            $TermEquation = '(' . $TermEquation . ')';
        }
        return $TermEquation;
    }

}

?>