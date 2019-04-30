<?php

namespace LayerIntegration;

require_once(realpath(dirname(__FILE__)) . '/../InterfaceIntegration.php');

abstract class ControllerImportModel {

    private $XMLObject;
    protected $connectionTimer;
    protected $SimpleLifeMessage;
    protected $timeSum;

    protected function getXMLObject() {
        return $this->XMLObject;
    }

    protected function setXMLObject($obj) {
        $this->XMLObject = $obj;
    }

    public function obtainInfo($Params) {
        return $this->InnerObtain($Params);
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