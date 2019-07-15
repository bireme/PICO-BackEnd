<?php

namespace PICOExplorer\Services\DeCS;

class ControllerEquationChecker {

    private $AnyObjectWithQuerySetterGetter;

    public function __construct($AnyObjectWithQuerySetterGetter) {
        $this->AnyObjectWithQuerySetterGetter = $AnyObjectWithQuerySetterGetter;
    }

    ///////////////////////////////////////////////////////////////////
    //PUBLIC FUNCTIONS
    ///////////////////////////////////////////////////////////////////

    public function CheckAndFixEquation() {
        return $this->CheckEquationParameters() ||
                $this->FixEquation();
    }

    ///////////////////////////////////////////////////////////////////
    //INNER FUNCTIONS
    /////////////////////////////////////////////////////////////////// 

    private function FixEquation() {
        $Equation = $this->AnyObjectWithQuerySetterGetter->getQuery();
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
            $Equation = preg_replace_callback('/((?<!NOT )[(][A-Z0-9\"][)])/', function($matches) {
                $matches[1] = substr($matches[1], 1, -1);
                return $matches[1];
            }, $Equation);
        }
        $Equation = trim($Equation, ' ');
        $Equation = preg_replace_callback('/((?<=|[() ])[A-Z0-9\"][ ](?=[)]))/', function($matches) {
            $matches[1] = substr($matches[1], 0, 1);

            return $matches[1];
        }, $Equation);

        $this->AnyObjectWithQuerySetterGetter->setQuery($Equation);
    }

    private function CheckEquationParameters() {
        $Equation = $this->AnyObjectWithQuerySetterGetter->getQuery();
        $title = $this->AnyObjectWithQuerySetterGetter->getTitle();
        try {
            if (!is_string($Equation) == true) {
                throw new SimpleLifeException(new \SimpleLife\EquationMustBeString($title));
            }
            if (substr_count($Equation, '(') != substr_count($Equation, ')')) {
                throw new SimpleLifeException(new \SimpleLife\ParenthesesNumberNotMatch($title, $Equation));
            }
            //$InvalidChars = preg_replace("/[a-zA-Z0-9 ()/-\"]/", "", $Equation);
            //$InvalidChars = preg_replace("/(.)\\1+/", "$1", $InvalidChars);
            //if (strlen($InvalidChars) > 0) {
            //  throw new SimpleLifeException(new \SimpleLife\EqInvalidChars($EqName, $InvalidChars));
            //}
        } catch (SimpleLifeException $exc) {
            return $exc->PreviousUserErrorCode();
        }
    }

}

?>
