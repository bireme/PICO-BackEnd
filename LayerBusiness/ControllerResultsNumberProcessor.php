<?php

namespace LayerBusiness;

class ControllerResultsNumberProcessor {

    private $fields = array('', 'ti', 'tw', 'mh');
    private $ObjectResultList;

    public function __construct($ObjectResultList) {
        $this->ObjectResultList = $ObjectResultList;
    }

    ///////////////////////////////////////////////////////////////////
    //PUBLIC FUNCTIONS
    ///////////////////////////////////////////////////////////////////

    public function BuildQueries() {
        $Inidata = $this->getInitialData();
        $ResultsClusters = $this->buildGlobalAndLocalArrays($Inidata);
        $this->BuildQueriesAndCreateObject($ResultsClusters);
    }

    public function IntegrationResultsToObject($data) {
        return $this->IntegrationResultsToObjectInner($data);
    }

    ///////////////////////////////////////////////////////////////////
    //INNER FUNCTIONS
    /////////////////////////////////////////////////////////////////// 

    private function BuildQueriesAndCreateObject($ResultsClusters) {
        foreach ($ResultsClusters as $key => $ResultsCluster) {
            $query = $this->BuildQuery($ResultsCluster);
            $this->AddResultQuery($key, $query);
        }
    }

    private function BuildQuery($ResultsCluster) {
        $query = '';
        $count = 0;
        foreach ($ResultsCluster as $PICOEquationId => $PICOEquationObj) {
            $msg = '';
            if ($count > 0) {
                $msg = $msg . ' AND ';
            }

            if (strlen($PICOEquationObj['query']) == 0) {
                continue;
            }
            $msg = $msg . $this->SetSearchField($PICOEquationObj);
            $query = $query . $msg;
            $count++;
        }
        return $query;
    }

    private function SetSearchField($PICOEquationObj) {
        $fieldnum = $PICOEquationObj['field'];
        if ($fieldnum == -1) {
            $field = '';
        } else {
            $field = $this->fields[$fieldnum];
        }

        if (strlen($field) > 0) {
            $msg = $field . ':(' . $PICOEquationObj['query'] . ')';
        } else {
            $msg = $PICOEquationObj['query'];
        }
        return $msg;
    }

    private function buildGlobalAndLocalArrays($Inidata) {
        $local = array();
        $global = array();
        $EqNum = $this->ObjectResultList->getPICONum();
        $EqNumCopy = $EqNum;
        if ($EqNumCopy == 6) {
            $EqNumCopy = 5;
        }
        $i = 1;
        while ($i <= $EqNumCopy) {
            $PICOEquationId = 'PICO' . $i;
            $PICOEquationArray = $Inidata[$PICOEquationId];
            if ($i == $EqNumCopy && $EqNum != 6) {
                $local[$PICOEquationId] = $PICOEquationArray;
            }
            $global[$PICOEquationId] = $PICOEquationArray;
            $i++;
        }
        return array('global' => $global, 'local' => $local);
    }

    ///////////////////////////////////////////////
    //OPERATIONS WITH OBJECT RESULTS
    ///////////////////////////////////////////////

    private function getInitialData() {
        return $this->ObjectResultList->getInitialData();
    }

    private function AddResultQuery($key, $query) {
        $this->ObjectResultList->AddResultQuery($key, $query);
    }

    private function IntegrationResultsToObjectInner($data) {
        $keysdata = array_keys($data);
        $keysResultList = array_keys($this->ObjectResultList->getAllResultQueryObjects());
        $inter = array_intersect($keysdata, $keysResultList);
        if ((count($inter) < count($keysdata)) || (count($inter) < count($keysResultList))) {
            throw 'Incongruent search';
        }

        foreach ($data as $key => $resultsData) {
            $this->ObjectResultList->AddIntegrationResultsToResultQuery($key, $resultsData['resultsNumber'], $resultsData['resultsURL']);
        }
    }

}

?>