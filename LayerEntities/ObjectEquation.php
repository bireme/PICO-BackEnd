<?php

namespace LayerEntities;

class ObjectEquation {

    private $Equation;
    private $Name;
    private $NewEquation;

    public function __construct($Equation, $Name) {
        $this->Equation = $Equation;
        $this->Name = $Name;
        $this->NewEquation = $Equation;
    }

    public function setNewEquation($Equation) {
        $this->NewEquation = $Equation;
    }

    public function getEquation() {
        return $this->Equation;
    }

    public function getNewEquation() {
        return $this->NewEquation;
    }

    public function getName() {
        return $this->Name;
    }

}

?>