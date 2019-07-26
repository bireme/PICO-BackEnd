<?php

namespace PICOExplorer\Services\ResultsNumber;

use PICOExplorer\Services\ServiceModels\PICOServiceEntryPoint;

class ResultsNumberProcess extends ResultsNumberInternalConnector implements PICOServiceEntryPoint
{

    /**
     * @var array
     */
    protected $fields = ['', 'ti', 'tw', 'mh'];


    final public function Process()
    {
        $ResultsClusters = $this->buildGlobalAndLocalArrays();
        $ProcessedQueries = [];
        foreach ($ResultsClusters as $key => $ResultsCluster) {
            $ProcessedQueries[$key] = $this->BuildQuery($ResultsCluster);
        }
        $ruleArr = [
            'ProcessedQueries' => 'required|array|min:1',
            'ProcessedQueries.*' => 'required|string|distinct|min:1',
            'ProcessedQueries.*.*' => 'required|string|distinct|min:1',
        ];
        $this->UpdateModel(__METHOD__ . '@' . get_class($this), ['ProcessedQueries' => $ProcessedQueries], $ruleArr);
        $results = $this->ConnectToIntegration($ProcessedQueries);
        $this->setResults(__METHOD__ . '@' . get_class($this), $results);
    }

    protected function Explore(array $arguments = null)
    {
    }

    ///////////////////////////////////////////////////////////////////
    //INNER FUNCTIONS
    ///////////////////////////////////////////////////////////////////

    private function buildGlobalAndLocalArrays()
    {
        $local = [];
        $global = [];
        $EqNum = $this->model->InitialData['PICOnum'];
        $EqNumCopy = ($EqNum == 6) ? 5 : $EqNum;
        $i = 1;
        while ($i <= $EqNumCopy) {
            $PICOEquationId = 'PICO' . $i;
            $PICOEquationArray = $this->model->InitialData['queryobject'][$PICOEquationId];
            if ($i == $EqNumCopy && $EqNum != 6) {
                $local[$PICOEquationId] = $PICOEquationArray;
            }
            $global[$PICOEquationId] = $PICOEquationArray;
            $i++;
        }
        return ['global' => $global, 'local' => $local];
    }

    private function BuildQuery(array $ResultsCluster)
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
