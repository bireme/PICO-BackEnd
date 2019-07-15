<?php

namespace PICOExplorer\Services\ResultsNumberIntegration;

use PICOExplorer\Services\ServiceModels\ExtractFromXMLTrait;

class ExtractResultsNumberFromXML extends ProxyReceiveResultsNumber
{

    use ExtractFromXMLTrait;

    /**
     * @var array
     */

    protected $XMLResultsRules=[
        'numFound' => 'required|string|min:1',
    ];

    private function ExploreXML(array $arguments=null)
    {
        $result = $this->DOM->getElementsByTagName('result');
        $numFound = $result->item(0)->getAttribute('numFound');
        return ['numFound' => $numFound];
    }

}
