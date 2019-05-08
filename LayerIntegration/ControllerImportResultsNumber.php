<?php

namespace LayerIntegration;

require_once(realpath(dirname(__FILE__)) . '/../LayerIntegration/ProxyReceiveResultsNumber.php');
require_once(realpath(dirname(__FILE__)) . '/../LayerIntegration/ControllerImportModel.php');
require_once(realpath(dirname(__FILE__)) . '/../SimpleLife/SimpleLifeMessages.php');
require_once(realpath(dirname(__FILE__)) . '/../SimpleLife/ManualExceptions.php');

use SimpleLife\SimpleLifeException;
use SimpleLife\SimpleLifeMessage;
use LayerIntegration\ProxyReceiveResultsNumber;
use LayerIntegration\ControllerImportModel;
use \DOMDocument;

class ControllerImportResultsNumber extends ControllerImportModel {

    private $ObjectResult;
    private $BaseURL = 'http://pesquisa.bvsalud.org/portal/?count=20&q=';

    public function InnerObtain($params) {
        try {
            if (!(array_key_exists('ObjectResult', $params))) {
                throw new SimpleLifeException(new \SimpleLife\ObjectResultNotMatch());
            }
            $this->timeSum = $params['timeSum'];
            $this->ObjectResult = $params['ObjectResult'];
            if (is_object($this->ObjectResult) == false) {
                throw new SimpleLifeException(new \SimpleLife\ObjectResultNotMatch());
            }
            if (get_class($this->ObjectResult) != 'LayerEntities\ObjectResult') {
                throw new SimpleLifeException(new \SimpleLife\ObjectResultNotMatch());
            }
            $this->SimpleLifeMessage = new SimpleLifeMessage('[Retrieveing Results] ' . $this->ObjectResult->getTitle());
            $this->SimpleLifeMessage->AddEmptyLine();

            foreach ($this->ObjectResult->getResultQueryList() as $ResultQuery) {
                $fun = $this->ExploreEquation($ResultQuery);
                if ($fun) {
                    return $fun;
                }
            }
            $this->SimpleLifeMessage->SendAsLog();
        } catch (SimpleLifeException $exc) {
            return $exc->PreviousUserErrorCode();
        }
    }

    private function ExploreEquation($ResultQuery) {
        $fun = $this->CheckObjectResult($ResultQuery);
        if ($fun) {
            return $fun;
        }
        $query = $ResultQuery->getQuery();
        $ResultsURL = $this->BaseURL . $query;
        $ResultQuery->setResultsURL($ResultsURL);
        $XMLObject = new ProxyReceiveResultsNumber($query,$this->timeSum);
        $fun = $XMLObject->POSTRequest();
        if ($fun) {
            return $fun;
        }
        $preresult = $XMLObject->getResultdata();
        $result = $preresult['result'];
        $this->setXMLObject($result);
        $fun = $this->ExtractFromXML($ResultQuery);
        if ($fun) {
            return $fun;
        }
        $timer = $preresult['timer'];
        $this->addTimer($timer);
        $this->ObjectResult->setConnectionTime($timer);
    }

    private function CheckObjectResult($ResultQuery) {
        try {
            $query = $ResultQuery->getQuery();
            $MaximumQuerySize = 5000;
            if (strlen($query) == 0) {
                throw new SimpleLifeException(new \SimpleLife\EmptyQuery());
            }
            if (strlen($query) > $MaximumQuerySize) {
                throw new SimpleLifeException(new \SimpleLife\QueryTooLarge(strlen($ResultQuery->getQuery()), $MaximumQuerySize));
            }
        } catch (SimpleLifeException $Ex) {
            return $Ex->PreviousUserErrorCode();
        }
    }

    private function ExtractFromXML($ResultQuery) {
        $dom = new DOMDocument();
        $xml = $this->getXMLObject();
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