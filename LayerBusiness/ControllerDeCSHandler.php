<?php

namespace LayerBusiness;

require_once(realpath(dirname(__FILE__)) . '/../IntegrationStrategyContext.php');
require_once(realpath(dirname(__FILE__)) . '/../LayerEntities/ObjectDeCS.php');

use StrategyContext;
use LayerEntities\ObjectDeCS;

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
    private $DeCSObject;
    
    public function __construct($keyword, $langs) {
        $obj =new ObjectDeCS();
        $this->DeCSObject= $obj;
        $this->getDeCS($keyword, $langs);
        $this->ShowSummary(); 
    }

    private function getDeCS($keyword,$langs) {
        $strategyContextA = new StrategyContext('DeCS');
        $strategyContextA->obtainInfo(array('keyword' => $keyword,'langs'=>$langs,'DeCSObject' => $this->DeCSObject));
    }
   
    private function buildPositionArr($positions) {
        $Arr=explode(",",$positions);
        return $Arr;
    }

    private function obtainInfo() {
        $this->DeCSObject->obtainInfo();
    }

    private function ShowSummary() {
        $this->DeCSObject->ShowSummary();
    }
    
}

?>