<?php

namespace LayerIntegration;

require_once(realpath(dirname(__FILE__)) . '/../InterfaceIntegration.php');

use InterfaceIntegration;

/**
 * @access public
 * @author Daniel Nieto
 * @package LayerIntegration
 */
abstract class ControllerImportModel implements InterfaceIntegration {

    private $ResultVar;
    private $XMLObject;

    private function getResultVar() {
        return $this->ResultVar;
    }

    protected function setResultVar($ResultVar) {
        $this->ResultVar = $ResultVar;
    }

    protected function getXMLObject() {
        return $this->XMLObject;
    }

    protected function setXMLObject($obj) {
        $this->XMLObject = strval($obj);
    }

    public function obtainInfo($Params) {
        $this->InnerObtain($Params);
        return $this->getResultVar();
    }

}

?>