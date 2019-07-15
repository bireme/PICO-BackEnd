<?php

namespace PICOExplorer\Services\ResultsNumberIntegration;

use PICOExplorer\Exceptions\InternalErrors\ResultsItemDoesNotContainQueryURLAndNumber;
use PICOExplorer\Exceptions\InternalErrors\ErrorWhileUpdatingTheModel;

class ResultsNumberBIREME extends ExtractResultsNumberFromXML
{

    final public function Process()
    {
        $results = array();
        foreach ($this->model->ProcessedQueries as $key => $query) {
            $results[$key] = $this->Explore($query);
        }
        $ex=$this->UpdateModel($results,false);
        if($ex){
            throw new ErrorWhileUpdatingTheModel(null,$ex);
        }
        parent::Process();
    }

    protected function Explore(string $queryString)
    {
        $tmp = $this->ProxyResultsNumber($queryString);
        $ResultsURL = $tmp['ResultsURL'];
        $XML = $tmp['XML'];
        $rules = [
            'ResultsURL' => 'string|required|min:1',
            'XML' => 'required',
        ];
        $ex = $this->ValidateData($tmp, $rules);
        if ($ex) {
            throw new ResultsItemDoesNotContainQueryURLAndNumber(null, $ex);
        }
        $this->LoadXML($XML);
        $resultsNumber = $this->getXMLResults(null);
        return [
            'query' => $queryString,
            'ResultsNumber' => $resultsNumber,
            'ResultsURL' => $ResultsURL,
        ];
    }
}
