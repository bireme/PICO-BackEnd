<?php

namespace PICOExplorer\Services\ResultsNumberIntegration;

use PICOExplorer\Services\ServiceModels\PICOServiceEntryPoint;

class ResultsNumberBIREME extends ResultsNumberImporter implements PICOServiceEntryPoint
{

    final public function Process()
    {
        $results = array();
        foreach ($this->DTO->getInitialData() as $key => $query) {
            $results[$key] = $this->Explore($query);
        }
        dd('xxx');
        return $results;
    }

    final public function Explore(string $queryString)
    {
        $data = [
            'output' => 'xml',
            'count' => 20,
            'q' => $queryString
        ];
        return $this->ImportResultsNumber($data);
    }

}
