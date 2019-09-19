<?php

namespace PICOExplorer\Services\ServiceModels;

trait EquationCheckerTrait {

    public function FixEquation(string $Equation) {
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

        return $Equation;
    }

}
