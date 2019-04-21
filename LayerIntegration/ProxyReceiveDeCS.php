<?php

namespace LayerIntegration;
require_once(realpath(dirname(__FILE__)) . '/../LayerIntegration/ProxyModel.php');
use LayerIntegration\ProxyModel;

class ProxyReceiveDeCS extends ProxyModel{

     function __construct($key, $lang,$ByKeyword) {
        $this->setBaseURL("http://decs.bvsalud.org/cgi-bin/mx/cgi=@vmx/decs/?");
        if($ByKeyword==true){
            $Fields = array('words' => $key,
            'lang' => $lang
        );
        }else{
            $Fields = array('tree_id' => $key,
            'lang' => $lang
        );
        }        
        $this->setPOSTFields($Fields);
    }

}

?>