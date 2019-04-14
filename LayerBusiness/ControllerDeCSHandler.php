<?php

namespace LayerBusiness;

require_once(realpath(dirname(__FILE__)) . '/../IntegrationStrategyContext.php');

use StrategyContext;

/**
 * @access public
 * @author Daniel Nieto
 * @package LayerBusiness
 */
class ControllerDeCSHandler {

    /**
     * @access public
     * @param keyword
     * @param arrayPosition
     * @param langArr
     */
    public function __construct($keyword, $position, $langs) {

        $DeCSTitles = $this->getDeCSTitles($keyword);
        $tree_id = $DeCSTitles[(int) $position]["tree_id"];
        $DeCS = $this->getDeCS($tree_id, $langs);

        $this->summary($keyword, $position, $DeCSTitles, $DeCS, $tree_id);
    }

    private function getDeCSTitles($keyword) {
        $strategyContextA = new StrategyContext('DeCSTitles');
        return $strategyContextA->showResults(array('keyword' => $keyword));
    }

    private function getDeCS($tree_id, $langs) {
        $strategyContextA = new StrategyContext('DeCS');
        return $strategyContextA->showResults(array('tree_id' => $tree_id, 'langs' => $langs));
    }

    private function summary($keyword, $position, $DeCSTitles, $DeCS, $tree_id) {
        echo 'The DeCS descriptors for "' . $keyword . '" (' . $tree_id . ') are:</br>';
        foreach ($DeCSTitles as $inArr) {
            echo join($inArr, ' -> ') . '</br>';
        }
        echo '</br> The thesauri for "' . $DeCSTitles[$position]['term'] . '" (' . $tree_id . ') are:</br>';
        echo join($DeCS, '</br>');
    }

}

?>