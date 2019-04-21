<?php

namespace LayerIntegration;

require_once(realpath(dirname(__FILE__)) . '/../InterfaceIntegration.php');

use InterfaceIntegration;

abstract class ControllerImportModel {

    private $ResultVar;
    private $XMLObject;
    protected $connectionTimer;

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
        try {
            $this->InnerObtain($Params);
            return $this->getResultVar();
        } catch (IntegrationExceptions $exc) {
            if ($exc->HandleError()) {
                $Ex = new JustReturnException();
                throw new IntegrationExceptions($Ex->build(), $exc->PreviousUserErrorCode());
            }
        }
    }

    public function getTimer() {
        return $this->connectionTimer;
    }

    protected function addTimer($time) {
        $this->connectionTimer += $time;
    }

    public function startTimer() {
        $this->connectionTimer = 0;
    }

}

?>