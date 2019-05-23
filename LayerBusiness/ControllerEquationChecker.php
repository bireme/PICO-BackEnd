<?php

namespace LayerBusiness;

require_once(realpath(dirname(__FILE__)) . '/../SimpleLife/SimpleLifeMessages.php');
require_once(realpath(dirname(__FILE__)) . '/../LayerEntities/ObjectEquation.php');

use SimpleLife\SimpleLifeException;

class ControllerEquationChecker {
    
    private $EquationOwnerObj;

    public function __construct($EquationOwnerObj) {
        $this->EquationOwnerObj=$EquationOwnerObj;
    }

    public function CheckEquation($EqName) {
        $query = $this->EquationOwnerObj->getQuery();
        $fun = $this->CheckEquationParameters($query,$EqName);
        if ($fun) {
            return $fun;
        }
        $query=$this->FixEquation($query);
        $this->EquationOwnerObj->setQuery($query);
    }

    public function FixEquation($Equation) {
        $Equation = trim($Equation, ' ');
        $Equation = str_replace('  ', ' ', $Equation);
        $Equation = str_replace('[', '(', $Equation);
        $Equation = str_replace(']', ')', $Equation);
        $Equation = str_replace('{', '(', $Equation);
        $Equation = str_replace('}', ')', $Equation);
        $Equation = str_replace('()', '', $Equation);
        $Equation = str_replace('( )', '', $Equation);
        $Equation = str_replace(')(', ') OR (', $Equation);
        $Equation = str_replace(') (', ') OR (', $Equation);

        $oriEq = '';
        while ($Equation != $oriEq) {
            $oriEq = $Equation;
            $Equation = preg_replace_callback('/((?<!NOT )[(][A-Z0-9][)])/', function($matches) {
                $matches[1] = substr($matches[1], 1, -1);
                return $matches[1];
            }, $Equation);
        }
        $Equation = trim($Equation, ' ');
        $Equation = preg_replace_callback('/((?<=|[() ])[A-Z0-9][ ](?=[)]))/', function($matches) {
            $matches[1] = substr($matches[1], 0, 1);

            return $matches[1];
        }, $Equation);


        return $Equation;
    }
    
    private function CheckEquationParameters($Equation,$EqName) {
        try {
            if (!is_string($Equation) == true) {
                throw new SimpleLifeException(new \SimpleLife\EquationMustBeString($EqName));
            }
            if (substr_count($Equation, '(') != substr_count($Equation, ')')) {
                throw new SimpleLifeException(new \SimpleLife\ParenthesesNumberNotMatch($EqName,$Equation));
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