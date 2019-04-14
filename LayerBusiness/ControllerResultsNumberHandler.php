<?php

namespace LayerBusiness;

require_once(realpath(dirname(__FILE__)) . '/../IntegrationStrategyContext.php');

use StrategyContext;

/**
 * @access public
 * @author Daniel Nieto
 * @package LayerBusiness
 */
class ControllerResultsNumberHandler {

    public function __construct($query) {
        $ResultsNumber = $this->getResultsNumber($query);
        $this->summary($query, $ResultsNumber);
    }

    private function getResultsNumber($query) {
        $strategyContextC = new StrategyContext('ResultsNumber');
        return $strategyContextC->showResults(array('query' => $query));
    }

    private function summary($query, $ResultsNumber) {
        echo $ResultsNumber . " results found for this query:</br>";
        echo $query;
    }

}

?>