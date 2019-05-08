<?php

namespace LayerEntities;

require_once('ObjectResult.php');
require_once(realpath(dirname(__FILE__)) . '/../SimpleLife/SimpleLifeMessages.php');

use SimpleLife\SimpleLifeMessage;
use LayerEntities\ObjectResult;

class ObjectResultList {

    private $EqNum;
    private $ObjectResult;
    private $PICOname;

    public function getConnectionTimeSum() {
        $res = 0;
        foreach ($this->ObjectResult as $ResultObj) {
            $res += $ResultObj->getConnectionTime();
        }
        return $res;
    }

    public function __construct($EqNum, $resultsData, $PICOname) {
        $this->EqNum = $EqNum;
        $this->PICOname = $PICOname;
        $this->setResultsData($EqNum, $resultsData, $PICOname);
    }

    public function getTitle() {
        return $this->PICOname;
    }

    private function setResultsData($EqNum, $resultsData, $PICOname) {
        $mes = new SimpleLifeMessage(json_encode($resultsData));
        $mes->SendAsLog();
        $local = array();
        $global = array();
        $EqNumCopy = $EqNum;
        if ($EqNumCopy == 6) {
            $EqNumCopy = 5;
        }
        $i = 1;
        while ($i <= $EqNumCopy) {
            $key = 'PICO' . $i;
            $obj = $resultsData[$key];
            if ($i == $EqNumCopy && $EqNum!=6) {
                $local[$key] = $obj;
            }
            $global[$key] = $obj;
            $i++;
        }
        $resultQueryList = array();
        if (count($local) > 0) {
            $resultQueryList['local'] = new ResultQuery($local, $PICOname . ' - Local', false);
        }
        if (count($global) > 0) {
            $resultQueryList['global'] = new ResultQuery($global, $PICOname . ' - Global', true);
        }
        $this->ObjectResult = new ObjectResult($resultQueryList, $PICOname);
    }

    public function ObjectResultInfo($SimpleLifeMessage) {
        $SimpleLifeMessage->AddAsNewLine('Summary of Results Number by Query: ');
        foreach ($this->ObjectResult as $ResultObj) {
            $Title = $ResultObj->getTitle();
            $ResultsNumber = $ResultObj->getResultsNumber();
            $ResultsURL = $ResultObj->getResultsURL();
            $Query = $ResultObj->getQuery();
            $SimpleLifeMessage->AddEmptyLine();
            $SimpleLifeMessage->AddAsNewLine($Title . ' (' . $ResultsNumber . ' results)');
            $SimpleLifeMessage->AddAsNewLine('-->Query: ' . $Query);
            $SimpleLifeMessage->AddAsNewLine('-->ResultsURL: ' . $ResultsURL);
        }
    }

    public function getResults() {
        $result = $this->ObjectResult->getResults();
        $result['PICOnum'] = $this->EqNum;
        return $result;
    }

    public function getObjectResult() {
        return $this->ObjectResult;
    }

}

?>