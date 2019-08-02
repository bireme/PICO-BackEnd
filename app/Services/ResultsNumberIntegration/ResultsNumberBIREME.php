<?php

namespace PICOExplorer\Services\ResultsNumberIntegration;

use PICOExplorer\Services\ServiceModels\PICOServiceEntryPoint;

class ResultsNumberBIREME extends ResultsNumberImporter implements PICOServiceEntryPoint
{

    final public function Process()
    {
        $results = array();
        foreach ($this->model->InitialData as $key => $query) {
            $results[$key] = $this->Explore($query);
        }
        $this->setResults(__METHOD__ . '@' . get_class($this),$results);
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
