<?php

namespace LayerBusiness;

require_once(realpath(dirname(__FILE__)) . '/../SimpleLife/SimpleLifeMessages.php');
require_once(realpath(dirname(__FILE__)) . '/../LayerEntities/ObjectEquation.php');

use SimpleLife\SimpleLifeException;

class ControllerEquationChecker {

    private $ObjectEquation;

    public function __construct($ObjectEquation) {
        $this->ObjectEquation = $ObjectEquation;
    }

    public function CheckEquation() {
        $fun = $this->CheckEquationParameters();
        if ($fun) {
            return $fun;
        }
    }

    public function FixEquation() {
        try {
            $Equation = $this->ObjectEquation->getEquation();
            $Equation = trim(Equation, ' ');
            $Equation = str_replace('  ', ' ', $Equation);
            $Equation = str_replace('[', '(', $Equation);
            $Equation = str_replace(']', ')', $Equation);
            $Equation = str_replace('{', '(', $Equation);
            $Equation = str_replace('}', ')', $Equation);
            $Equation = str_replace('()', '', $Equation);
            $Equation = str_replace('( )', '', $Equation);
            $Equation = str_replace(')(', ') OR (', $Equation);
            $Equation = str_replace(') (', ') OR (', $Equation);
            $this->ObjectEquation->setNewEquation($Equation);
        } catch (Exception $ex) {
            return 350;
        }
    }

    private function CheckEquationParameters() {
        try {
            $Equation = $this->ObjectEquation->getEquation();
            $EqName = $this->ObjectEquation->getName();
            if (!is_string($Equation) == true) {
                throw new SimpleLifeException(new \SimpleLife\EquationMustBeString($EqName));
            }
            if (substr_count($Equation, '(') == substr_count($Equation, ')')) {
                throw new SimpleLifeException(new \SimpleLife\ParenthesesNumberNotMatch($EqName));
            }
            $InvalidChars = preg_replace("/[a-zA-Z0-9 ()]/", "", $Equation);
            $InvalidChars = preg_replace("/(.)\\1+/", "$1", $InvalidChars);
            if (strlen($InvalidChars) > 0) {
                throw new SimpleLifeException(new \SimpleLife\EqInvalidChars($EqName, $InvalidChars));
            }
        } catch (SimpleLifeException $exc) {
            return $exc->PreviousUserErrorCode();
        }
    }

}

?>