<?php

namespace LayerBusiness;

require_once(realpath(dirname(__FILE__)) . '/../IntegrationStrategyContext.php');
require_once(realpath(dirname(__FILE__)) . '/../LayerEntities/ObjectDeCS.php');

use StrategyContext;
use LayerEntities\ObjectDeCS;

class ControllerDeCSHandler {

    private $DeCSObject;

    public function __construct($keyword, $langs) {
        $obj = new ObjectDeCS();
        $this->DeCSObject = $obj;
        $this->getDeCS($keyword, $langs);


        if ($this->DeCSObject->isDeCSSet()) {
            $this->DeCSObject->ShowSummary();
        }
    }

    private function getDeCS($keyword, $langs) {
        $strategyContextA = new StrategyContext('DeCS');
        $strategyContextA->obtainInfo(array('keyword' => $keyword, 'langs' => $langs, 'DeCSObject' => $this->DeCSObject));
    }

    private function buildPositionArr($positions) {
        $Arr = explode(",", $positions);
        return $Arr;
    }

    private function obtainInfo() {
        $this->DeCSObject->obtainInfo();
    }

}

?>