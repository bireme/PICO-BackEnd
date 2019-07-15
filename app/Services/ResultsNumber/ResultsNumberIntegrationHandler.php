<?php

namespace PICOExplorer\Services\ResultsNumber;

use PICOExplorer\Services\ServiceModels\PICOServiceModel;
use PICOExplorer\Services\ServiceModels\GuzzleProxyRequestTrait;

class ResultsNumberIntegrationHandler Extends PICOServiceModel
{

    use GuzzleProxyRequestTrait;

    /**
     * @var array
     */

    protected $Proxyattributes = [
        'headers' => array(),
        'async' => true,
        'baseuri' => 'API/ResultsNumber',
    ];

    protected function ProxyIntegrationResultsNumber(array $ProcessedQueries)
    {
        $data = ['ProcessedQueries' => $ProcessedQueries];
        return $this->MakeRequest('POST',$data,true,true);
    }

}
