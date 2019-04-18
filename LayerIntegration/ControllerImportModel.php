<?php

namespace LayerIntegration;

require_once(realpath(dirname(__FILE__)) . '/../InterfaceIntegration.php');

use InterfaceIntegration;

/**
 * @access public
 * @author Daniel Nieto
 * @package LayerIntegration
 */
abstract class ControllerImportModel{

    private $ResultVar;
    private $XMLObject;

    
    
    public function getResultVar() {
        return $this->ResultVar;
    }

    protected function setResultVar($ResultVar) {
        $this->ResultVar = $ResultVar;
    }

    protected function getXMLObject() {
        return $this->XMLObject;
    }

    protected function setXMLObject($obj) {
        $this->XMLObject = $obj;
    }

    public function obtainInfo($Params) {
        $this->InnerObtain($Params);
        return $this->getResultVar();
    }

}

?>