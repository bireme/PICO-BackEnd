<?php

namespace LayerBusiness;

require_once(realpath(dirname(__FILE__)) . '/../IntegrationStrategyContext.php');
require_once(realpath(dirname(__FILE__)) . '/../SimpleLife/ManualExceptions.php');
require_once(realpath(dirname(__FILE__)) . '/../SimpleLife/SimpleLifeMessages.php');

use SimpleLife\SimpleLifeMessage;
use StrategyContext;
use SimpleLife\SimpleLifeException;

class ControllerResultsNumberHandler {

    private $ObjectResultList;
    private $SimpleLifeMessage;

    public function __construct($ObjectResultList) {
        $this->ObjectResultList = $ObjectResultList;
        $this->SimpleLifeMessage = new SimpleLifeMessage('DeCS Manager');
    }

    public function getResultsNumber() {
        $strategyContextB = new StrategyContext('ResultsNumber');
        $info = '[Extracting ResultsNumber] ' . $this->ObjectResultList->getTitlesAsString();
        try {
            $fun = $strategyContextB->obtainInfo(array('ObjectResultList' => $this->ObjectResultList, 'info' => $info));
            if ($fun) {
                throw new SimpleLifeException(new \SimpleLife\PreviousIntegrationException($fun));
            }
            $this->ReportMessage();
        } catch (SimpleLifeException $Ex) {
            return $Ex->PreviousUserErrorCode();
        }
    }

    private function ReportMessage() {
        $this->ObjectResultList->ObjectResultInfo($this->SimpleLifeMessage);
        $this->SimpleLifeMessage->SendAsLog();
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

}

?>