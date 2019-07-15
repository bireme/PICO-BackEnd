<?php

namespace PICOExplorer\Services\ResultsNumberIntegration;

use PICOExplorer\Services\ServiceModels\PICOServiceModel;
use PICOExplorer\Services\ServiceModels\GuzzleProxyRequestTrait;

class ProxyReceiveResultsNumber Extends PICOServiceModel
{

    use GuzzleProxyRequestTrait;

    /**
     * @var array
     */
    protected $Proxyattributes = [
        'headers' => array(),
        'async' => true,
        'baseuri' => 'http://pesquisa.bvsalud.org/portal/?',
    ];

    protected function ProxyResultsNumber(string $query) {
        $data = [
            'output' => 'xml',
            'count=' => 20,
            'q' => $query
        ];
        $uri = $this->Proxyattributes['baseuri'].http_build_query($data);
        $res = $this->MakeRequest('POST',$data);
        return ['ResultsURL'=>$uri,'ResultsNumber'=>$res];
    }

}
