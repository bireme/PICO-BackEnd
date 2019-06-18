<?php

namespace LayerIntegration;

require_once(realpath(dirname(__FILE__)) . '/../LayerIntegration/ProxyModel.php');

use LayerIntegration\ProxyModel;

class ProxyReceiveResultsNumber extends ProxyModel {

    public function __construct(&$timer) {
        $this->setBaseURLAndTimer("http://pesquisa.bvsalud.org/portal/?", $timer);
    }

    ///////////////////////////////////////////////////////////////////
    //PUBLIC FUNCTIONS
    /////////////////////////////////////////////////////////////////// 

    public function setConnectionInfo($query) {
        $Fields = array('output' => 'xml',
            'count=' => 20,
            'q' => $query
        );
        $this->setPOSTFields($Fields);
    }

}

?>