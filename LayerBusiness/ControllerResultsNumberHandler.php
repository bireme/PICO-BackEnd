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
        $this->SimpleLifeMessage = new SimpleLifeMessage('Results Manager');
    }

    public function BuildResultsNumber() {
        $strategyContextB = new StrategyContext('ResultsNumber');
        try {
            $info = '[Extracting ResultsNumber] ' . $this->ObjectResultList->getTitle();
            $fun = $strategyContextB->obtainInfo(array('ObjectResult' => $this->ObjectResultList->getObjectResult(), 'info' => $info));
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

    private function CheckObjectResult() {
        try {
            $query = $this->ObjectResult->getQuery();
            $MaximumQuerySize = 5000;
            if (strlen($query) == 0) {
                throw new SimpleLifeException(new \SimpleLife\EmptyQuery());
            }
            if (strlen($query) > $MaximumQuerySize) {
                throw new SimpleLifeException(new \SimpleLife\QueryTooLarge(strlen($this->ObjectResult->getQuery()), $MaximumQuerySize));
            }
        } catch (SimpleLifeException $Ex) {
            return $Ex->PreviousUserErrorCode();
        }
    }

}

?>