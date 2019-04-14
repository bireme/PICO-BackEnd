<?php

namespace LayerIntegration;

require_once(realpath(dirname(__FILE__)) . '/../LayerIntegration/ProxyModel.php');

use LayerIntegration\ProxyModel;

/**
 * @access public
 * @author Daniel Nieto
 * @package LayerIntegration
 */
class ProxyReceiveResultsNumber extends ProxyModel {

    public function __construct($query) {
        $this->setBaseURL("http://pesquisa.bvsalud.org/portal/?");
        $Fields = array('output' => 'xml',
            'count=' => 20,
            'q' => $query
        );
        $this->setPOSTFields($Fields);
        $this->POSTRequest();
    }

}

?>