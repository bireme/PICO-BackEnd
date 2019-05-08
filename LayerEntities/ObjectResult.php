<?php

namespace LayerEntities;

require_once('ResultQuery.php');
require_once(realpath(dirname(__FILE__)) . '/../SimpleLife/SimpleLifeMessages.php');

use SimpleLife\SimpleLifeMessage;
use LayerEntities\ResultQuery;

class ObjectResult {

    private $ConnectionTime;
    private $resultQueryList;
    private $title;

    public function setConnectionTime(int $time) {
        $this->ConnectionTime += $time;
    }

    public function getConnectionTime() {
        return $this->ConnectionTime;
    }

    public function getTitle() {
        return $this->title;
    }

    public function __construct($resultQueryList, $title) {
        $this->ConnectionTime = 0;
        $this->title = $title;
        $this->resultQueryList = $resultQueryList;
    }

    public function ObjectResultInfo($SimpleLifeMessage) {
        $SimpleLifeMessage->AddAsNewLine('Summary of Results Number by Query: ');
        foreach ($this->ResultsList as $ResultObj) {
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
        $result = array();
        foreach ($this->resultQueryList as $ResultQuery) {
            $tmp = $ResultQuery->getResults();
            if ($ResultQuery->isGlobal()) {
                $key = 'global';
            } else {
                $key = 'local';
            }
            if (isset($tmp)) {
                $result[$key] = $tmp;
            }
        }
        return $result;
    }

    public function getResultQueryList() {
        return $this->resultQueryList;
    }

}

?>