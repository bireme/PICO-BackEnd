<?php

namespace PICOExplorer\Services\ServiceModels;

trait PICOQueryProcessorTrait
{

    protected function ProcessQuery(string $query)
    {
        $QuerySplitBySeparators = $this->splitQueryWithDelimiters($query);
        $QueryProcessed = $this->setArrayItemElements($QuerySplitBySeparators);
        return $QueryProcessed;
    }

    private function splitQueryWithDelimiters(string $query)
    {
        $query = str_replace('  ', ' ', $query);
        $query = strtolower($query);
        $pattern = '/([()": ])/';
        $QuerySplitBySeparators = preg_split($pattern, $query, -1, PREG_SPLIT_DELIM_CAPTURE | PREG_SPLIT_NO_EMPTY);
        return $QuerySplitBySeparators;
    }

    private function setArrayItemElements(array $QuerySplitBySeparators)
    {
        $FixedQuoted = $this->FixComposeParenthesesQuoted($QuerySplitBySeparators);
        $QueryProcessed = $this->FixComposeParentheses($FixedQuoted);
        return $QueryProcessed;
    }

    private function FixComposeParenthesesQuoted(array $QuerySplitBySeparators)
    {
        $FixedQuoted = [];
        $doublequote = 0;
        $NewComposedKeyword=null;
        foreach ($QuerySplitBySeparators as $QuerySplitBySepsElement) {
            if ($doublequote == 0) {
                if ($QuerySplitBySepsElement == '"') {
                    $doublequote = 1;
                    $NewComposedKeyword = '';
                } else {
                    array_push($FixedQuoted, $QuerySplitBySepsElement);
                }
            } else {
                if ($QuerySplitBySepsElement == '"') {
                    $doublequote = 0;
                    array_push($FixedQuoted, $NewComposedKeyword);
                } else {
                    $NewComposedKeyword = $NewComposedKeyword . $QuerySplitBySepsElement;
                }
            }
        }
        return $FixedQuoted;
    }

    private function FixComposeParentheses(array $FixedQuoted)
    {
        $index = count($FixedQuoted);
        $Ops = array('or', 'and', 'not');
        $Seps = array('(', ')', ' ', ':');
        $i = 0;
        $QueryProcessed = [];
        while ($i < $index) {
            if ((($i + 2 < $index))) {
                if (((!(in_array(strtolower($FixedQuoted[$i]), $Seps) || in_array(strtolower($FixedQuoted[$i]), $Ops))) && ($FixedQuoted[$i + 1] == ' ' && (!(in_array(strtolower($FixedQuoted[$i + 2]), $Seps) || in_array(strtolower($FixedQuoted[$i + 2]), $Ops)))))) {
                    $valx = $FixedQuoted[$i] . $FixedQuoted[$i + 1] . $FixedQuoted[$i + 2];
                    $i = $i + 3;
                } else {
                    $valx = $FixedQuoted[$i];
                    $i++;
                }
            } else {
                $valx = $FixedQuoted[$i];
                $i++;
            }
            $valx = ucfirst($valx);
            array_push($QueryProcessed, $valx);
        }
        return $QueryProcessed;
    }

}
