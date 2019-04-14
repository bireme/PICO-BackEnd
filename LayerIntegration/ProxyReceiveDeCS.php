<?php

namespace LayerIntegration;

require_once(realpath(dirname(__FILE__)) . '/../LayerIntegration/ProxyModel.php');

use LayerIntegration\ProxyModel;

/**
 * @access public
 * @author Daniel Nieto
 * @package LayerIntegration
 */
class ProxyReceiveDeCS extends ProxyModel {

     function __construct($tree_id, $lang) {
        $this->setBaseURL("http://decs.bvsalud.org/cgi-bin/mx/cgi=@vmx/decs/?");
        $Fields = array('tree_id' => $tree_id,
            'lang' => $lang
        );
        $this->setPOSTFields($Fields);
        $this->POSTRequest();
    }

}

?>