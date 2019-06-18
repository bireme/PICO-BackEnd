<?php

namespace LayerIntegration;

require_once(realpath(dirname(__FILE__)) . '/../LayerIntegration/ProxyModel.php');

use LayerIntegration\ProxyModel;

class ProxyReceiveDeCS extends ProxyModel {

    public function __construct(&$timer) {
        $this->setBaseURLAndTimer("http://decs.bvsalud.org/cgi-bin/mx/cgi=@vmx/decs/?", $timer);
    }

    ///////////////////////////////////////////////////////////////////
    //PUBLIC FUNCTIONS
    /////////////////////////////////////////////////////////////////// 

    public function setConnectionInfo($key, $lang, $ByKeyword) {
        $FirstArgument = 'tree_id';
        if ($ByKeyword == true) {
            $FirstArgument = 'words';
        }
        $Fields = array($FirstArgument => $key,
            'lang' => $lang
        );
        $this->setPOSTFields($Fields);
    }

}

?>