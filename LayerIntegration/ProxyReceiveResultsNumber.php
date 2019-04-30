<?php

namespace LayerIntegration;

require_once(realpath(dirname(__FILE__)) . '/../LayerIntegration/ProxyModel.php');

use LayerIntegration\ProxyModel;

class ProxyReceiveResultsNumber extends ProxyModel {

    public function __construct($query, $timeSum) {
        $this->timeSum = $timeSum;
        $this->setBaseURL("http://pesquisa.bvsalud.org/portal/?");
        $Fields = array('output' => 'xml',
            'count=' => 20,
            'q' => $query
        );
        $this->setPOSTFields($Fields);
    }

}

?>