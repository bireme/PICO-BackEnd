<?php

namespace PICOExplorer\Services\DeCSIntegration;

use PICOExplorer\Services\ServiceModels\PICOServiceModel;
use PICOExplorer\Services\ServiceModels\GuzzleProxyRequestTrait;

class ProxyReceiveDeCS Extends PICOServiceModel
{

    use GuzzleProxyRequestTrait;

    protected $attributes = [
        'headers' => array(),
        'async' => true,
        'baseuri' => 'http://decs.bvsalud.org/cgi-bin/mx/cgi=@vmx/decs/?',
    ];

    protected function ProxyDeCS($key, $lang, $ByKeyword) {
        $data = [
            $ByKeyword?'tree_id':'words' => $key,
            'lang' => $lang
        ];
        $res = $this->MakeRequest('POST',$data);
        return $res;
    }


}
