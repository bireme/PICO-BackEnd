<?php

namespace PICOExplorer\Services\ResultsNumber;

use PICOExplorer\Services\ServiceModels\PICOQueryProcessorTrait;
use PICOExplorer\Services\ServiceModels\ServiceEntryPoint;

abstract class ResultsNumberSupport extends ServiceEntryPoint
{

    /**
     * @var array
     */
    private $fields = ['', 'ti', 'tw', 'mh'];

    use PICOQueryProcessorTrait;

    protected function buildGlobalAndLocalArrays($InitialData)
    {
        $local = [];
        $global = [];
        $EqNum = $InitialData['PICOnum'];
        $EqNumCopy = ($EqNum == 6) ? 5 : $EqNum;
        $i = 1;
        while ($i <= $EqNumCopy) {
            $PICOEquationId = 'PICO' . $i;
            $PICOEquationArray = $InitialData['queryobject'][$PICOEquationId];
            if ($i == $EqNumCopy && $EqNum != 6) {
                $local[$PICOEquationId] = $PICOEquationArray;
            }
            $global[$PICOEquationId] = $PICOEquationArray;
            $i++;
        }
        return ['global' => $global, 'local' => $local];
    }

    protected function BuildQuery(array $ResultsCluster)
    {
        $query = '';
        foreach ($ResultsCluster as $PICOEquationId => $PICOEquationData) {
            if (strlen($PICOEquationData['query']) > 0) {
                if (strlen($query)) {
                    $query = $query . ' AND ';
                }
                $query = $query . $this->SetSearchField($PICOEquationData);
            }
        }
        return $query;
    }

    ///////////////////////////////////////////////////////////////////
    //INNER FUNCTIONS
    ///////////////////////////////////////////////////////////////////

    private function SetSearchField(array $PICOEquationData)
    {
        $txt = $PICOEquationData['query'];
        $fieldnum = $PICOEquationData['field'];
        $field = ($fieldnum == -1) ? '' : $this->fields[$fieldnum];
        if ($field) {
            $txt = $field . ':(' . $txt . ')';
        }
        return $txt;
    }

}
