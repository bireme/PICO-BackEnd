<?php

namespace LayerIntegration;

require_once(realpath(dirname(__FILE__)) . '/../LayerIntegration/ProxyModel.php');

use LayerIntegration\ProxyModel;

/**
 * @access public
 * @author Daniel Nieto
 * @package LayerIntegration
 */
class ProxyReceiveDeCSTitles extends ProxyModel {

    public function __construct($query) {
        $this->setBaseURL("http://srv.bvsalud.org/decsQuickTerm/search?");
        $Fields = array('query' => $query);
        $this->setPOSTFields($Fields);
        $this->POSTRequest();
    }

}

?>