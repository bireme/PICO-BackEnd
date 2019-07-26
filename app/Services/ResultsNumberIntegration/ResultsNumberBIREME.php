<?php

namespace PICOExplorer\Services\ResultsNumberIntegration;


use PICOExplorer\Services\AdvancedLogger\AdvancedLogger;
use PICOExplorer\Services\ServiceModels\PICOServiceEntryPoint;

class ResultsNumberBIREME extends ResultsNumberImporter implements PICOServiceEntryPoint
{

    final public function Process()
    {
        $log = new AdvancedLogger();
        $log->LogTest(get_class($this).'Process1');
        $results = array();
        foreach ($this->model->InitialData as $key => $query) {
            $results[$key] = $this->Explore($query);
        }
        $log->LogTest(get_class($this).'Process2');
        $this->setResults(__METHOD__ . '@' . get_class($this),$results);
        $log->LogTest(get_class($this).'Processfin');
        parent::Process();
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
