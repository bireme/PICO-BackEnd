<?php

namespace LayerBusiness;

require_once(realpath(dirname(__FILE__)) . '/../IntegrationStrategyContext.php');
require_once(realpath(dirname(__FILE__)) . '/../SimpleLife/ManualExceptions.php');

use StrategyContext;
use SimpleLife\SimpleLifeException;

class ControllerDeCSHandler {

    private $ObjectKeyWord;

    public function __construct($ObjectKeyWord) {
        $this->ObjectKeyWord = $ObjectKeyWord;
    }

    public function getDeCS() {
        $this->checkKeywords();
        $strategyContextA = new StrategyContext('DeCS');
        $info = '[Extracting DeCS] Keyword= ' . $this->ObjectKeyWord['value']->getKeyword() . ' Langs=' . json_encode($this->ObjectKeyWord['value']->getLang());
        try {
            $fun = $strategyContextA->obtainInfo(array('ObjectKeyword' => $this->ObjectKeyWord['value'], 'info' => $info));
            if ($fun) {
                throw new SimpleLifeException(new \SimpleLife\PreviousIntegrationException($fun));
            }
        } catch (SimpleLifeException $Ex) {
            return $Ex->PreviousUserErrorCode();
        }
    }

    private function checkKeywords() {
        try {
            if (strlen($this->ObjectKeyWord['value']->getKeyword()) == 0) {
                throw new SimpleLifeException(new \SimpleLife\EmptyKeyword());
            }
            $MaximumQuerySize = 5000;
            if (strlen($this->ObjectKeyWord['value']->getKeyword()) > $MaximumQuerySize) {
                throw new SimpleLifeException(new \SimpleLife\KeywordTooLarge(strlen($keyword), $MaximumQuerySize));
            }
        } catch (SimpleLifeException $Ex) {
            return $Ex->PreviousUserErrorCode();
        }
    }

}

?>