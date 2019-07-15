<?php

namespace PICOExplorer\Services\DeCS;

class DeCSIntegrationHandler Extends GuzzleProxyRequestModel
{

    protected $attributes = [
        'headers' => array(),
        'async' => true,
        'baseuri' => 'API/DeCSExplore',
    ];


    public function ObtainDeCS($keyword,$langs)
    {
        $data = [
            'keyword' => $keyword,
            'langs' => $langs,
            ];
        return $this->MakeRequest('POST',$data,true,true);
    }

}
