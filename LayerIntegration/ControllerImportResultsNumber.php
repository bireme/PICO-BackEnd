<?php

namespace LayerIntegration;

require_once(realpath(dirname(__FILE__)) . '/../LayerIntegration/ProxyReceiveResultsNumber.php');
require_once(realpath(dirname(__FILE__)) . '/../SimpleLife/SimpleLifeMessages.php');
require_once(realpath(dirname(__FILE__)) . '/../SimpleLife/ManualExceptions.php');

use SimpleLife\SimpleLifeException;
use SimpleLife\SimpleLifeMessage;
use LayerIntegration\ProxyReceiveResultsNumber;
use \DOMDocument;

class ControllerImportResultsNumber {

    private $ObjectResult;
    private $BaseURL = 'http://pesquisa.bvsalud.org/portal/?count=20&q=';
    private $connectionTimer;
    private $timeSum;
    private $SimpleLifeMessage;

    public function getTimer() {
        return $this->connectionTimer;
    }
    
    public function getBaseURL() {
        return $this->BaseURL;
    }

    public function addTimer($time) {
        $this->connectionTimer += $time;
    }

    public function startTimer() {
        $this->connectionTimer = 0;
    }

    public function __construct($ObjectResult, $timeSum, $SimpleLifeMessage) {
        $this->ObjectResult=$ObjectResult;
        $this->timeSum=$timeSum;
        $this->SimpleLifeMessage=$SimpleLifeMessage;
    }

    public function InnerObtain() {
        foreach ($this->ObjectResult->getResultQueryList() as $ResultQuery) {
            if ($fun = $this->ExploreEquation($ResultQuery)) {
                return $fun;
            }
        }
    }

    private function ExploreEquation($ResultQuery) {
        $query = $ResultQuery->getQuery();
        $ResultsURL = $this->getBaseURL() . $query;
        $ResultQuery->setResultsURL($ResultsURL);
        $XMLObject = new ProxyReceiveResultsNumber($query, $this->timeSum);
        $preresult=NULL;
        if ($fun = $XMLObject->POSTRequest($preresult)) {
            return $fun;
        }
        $xml = $preresult['data'];
        if ($fun = $this->ExtractFromXML($xml, $ResultQuery)) {
            return $fun;
        }
        $timer = $preresult['timer'];
        $this->addTimer($timer);
        $this->ObjectResult->setConnectionTime($timer);
    }

    private function ExtractFromXML($xml, $ResultQuery) {
        $dom = new DOMDocument();
        try {
            if (is_array($xml)) {
                if (array_key_exists('Error', $xml)) {
                    throw new SimpleLifeException(new \SimpleLife\ErrorTagInXML($ResultQuery->getQuery()));
                }
            }

            try {
                $dom->loadxml($xml);
            } catch (Exception $exc) {
                throw new SimpleLifeException(new \SimpleLife\XMLLoadException($exc->ToString()));
            }
            if ($dom->getElementsByTagName('response')->length == 0) {
                throw new SimpleLifeException(new \SimpleLife\XMLNotBiremeType());
            }

            $result = $dom->getElementsByTagName('result');
            if ($result->length == 0) {
                throw new SimpleLifeException(new \SimpleLife\NoResultsInXMLException($ResultQuery->getQuery()));
            }
            $ResultsNumber = $result->item(0)->getAttribute('numFound');
            $ResultQuery->setResultsNumber($ResultsNumber);
        } catch (SimpleLifeException $Ex) {
            return $Ex->PreviousUserErrorCode();
        }
    }

}

?>