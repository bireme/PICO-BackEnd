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

    private $ObjectResultList;
    private $BaseURL = 'http://pesquisa.bvsalud.org/portal/?count=20&q=';

    public function InnerObtain($params) {
        try {
            if (!(array_key_exists('ObjectResultList', $params))) {
                throw new SimpleLifeException(new \SimpleLife\ObjectResultListNotMatch());
            }
            $this->timeSum = $params['timeSum'];
            $this->ObjectResultList = $params['ObjectResultList'];
            if (is_object($this->ObjectResultList) == false) {
                throw new SimpleLifeException(new \SimpleLife\ObjectResultListNotMatch());
            }
            if (get_class($this->ObjectResultList) != 'LayerEntities\ObjectResultList') {
                throw new SimpleLifeException(new \SimpleLife\ObjectResultListNotMatch());
            }
            $this->SimpleLifeMessage = new SimpleLifeMessage('[Retrieveing Results] ' . $this->ObjectResultList->getPICOsElement());
            $this->SimpleLifeMessage->AddEmptyLine();

            foreach ($this->ObjectResultList->getResultList() as $ObjectResult) {
                $fun = $this->ExploreEquation($ObjectResult);
                if ($fun) {
                    return $fun;
                }
            }
            $this->SimpleLifeMessage->SendAsLog();
        } catch (SimpleLifeException $exc) {
            return $exc->PreviousUserErrorCode();
        }
    }

    private function ExploreEquation($ObjectResult) {
        $fun = $this->CheckObjectResult($ObjectResult);
        if ($fun) {
            return $fun;
        }
        $query = $ObjectResult->getQuery();
        $ResultsURL = $this->BaseURL . urlencode($query);
        $ObjectResult->setResultsURL($ResultsURL);
        $XMLObject = new ProxyReceiveResultsNumber(urlencode($query),$this->timeSum);
        $fun = $XMLObject->POSTRequest();
        if ($fun) {
            return $fun;
        }
        $preresult = $XMLObject->getResultdata();
        $result = $preresult['result'];
        $this->setXMLObject($result);
        $fun = $this->ExtractFromXML($ObjectResult);
        if ($fun) {
            return $fun;
        }
        $timer = $preresult['timer'];
        $this->addTimer($timer);
        $this->ObjectResultList->setConnectionTime($timer);
    }

    private function CheckObjectResult($ObjectResult) {
        try {
            $query = $ObjectResult->getQuery();
            $MaximumQuerySize = 5000;
            if (strlen($query) == 0) {
                throw new SimpleLifeException(new \SimpleLife\EmptyQuery());
            }
            if (strlen($query) > $MaximumQuerySize) {
                throw new SimpleLifeException(new \SimpleLife\QueryTooLarge(strlen($ObjectResult->getQuery()), $MaximumQuerySize));
            }
        } catch (SimpleLifeException $Ex) {
            return $Ex->PreviousUserErrorCode();
        }
    }

    private function ExtractFromXML($ObjectResult) {
        $dom = new DOMDocument();
        $xml = $this->getXMLObject();
        try {
            if (is_array($xml)) {
                if (array_key_exists('Error', $xml)) {
                    throw new SimpleLifeException(new \SimpleLife\ErrorTagInXML($ObjectResult->getQuery()));
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
                throw new SimpleLifeException(new \SimpleLife\NoResultsInXMLException($query));
            }
            $ResultsNumber = $result->item(0)->getAttribute('numFound');
            $ObjectResult->setResultsNumber($ResultsNumber);
        } catch (SimpleLifeException $Ex) {
            return $Ex->PreviousUserErrorCode;
        }
    }

}

?>