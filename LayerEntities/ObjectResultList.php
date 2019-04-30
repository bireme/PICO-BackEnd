<?php

namespace LayerEntities;

require_once('ObjectResult.php');
require_once(realpath(dirname(__FILE__)) . '/../SimpleLife/SimpleLifeMessages.php');

use SimpleLife\SimpleLifeMessage;
use LayerEntities\ObjectResult;

class ObjectResultList {

    private $PICOsElement;
    private $ResultsList;
    private $SearchField;
    private $ConnectionTime;

    public function setConnectionTime(int $time) {
        $this->ConnectionTime += $time;
    }

    public function getConnectionTime() {
        return $this->ConnectionTime;
    }

    public function getConnectionTimeSum() { //temp function
        return $this->ConnectionTime;
    }

    public function __construct($PICOsElement, $SearchField) {
        $this->ConnectionTime = 0;
        $this->PICOsElement = $PICOsElement;
        $this->ResultsList = array();
        $this->SearchField = $SearchField;
    }

    public function getPICOsElement() {
        return $this->PICOsElement;
    }

    //------------------------------------------------------------
    //------------------------------------------------------------
    //-THIS SECTION CONTAINS ADVANCED FUNCTIONS----------------------
    //------------------------------------------------------------
    //------------------------------------------------------------

    public function getTitlesAsString() {
        return 'Items: [' . join($this->getTitles(), ', ') . ']';
    }

    public function getTitles() {
        $result = array();
        foreach ($this->ResultsList as $ResultObj) {
            array_push($result, $ResultObj->getTitle());
        }
        return $result;
    }

    public function AddObjectResults($QueryList) {
        foreach ($QueryList as $QueryTask) {
            $NewResultObj = new ObjectResult($QueryTask['Query'], $QueryTask['Title']);
            array_push($this->ResultsList, $NewResultObj);
        }
    }

    public function getResultList() {
        return $this->ResultsList;
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
        foreach ($this->ResultsList as $ResultObj) {
            $result[$ResultObj->getTitle()] = $ResultObj->getResults();
        }
        return $result;
    }

}

?>