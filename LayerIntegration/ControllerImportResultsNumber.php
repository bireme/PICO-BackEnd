<?php

namespace LayerIntegration;

require_once(realpath(dirname(__FILE__)) . '/../LayerIntegration/ProxyReceiveResultsNumber.php');
require_once(realpath(dirname(__FILE__)) . '/../LayerIntegration/ControllerImportModel.php');

use LayerIntegration\ProxyReceiveResultsNumber;
use LayerIntegration\ControllerImportModel;
use \DOMDocument;

/**
 * @access public
 * @author Daniel Nieto
 * @package LayerIntegration
 */
class ControllerImportResultsNumber extends ControllerImportModel {

    public function InnerObtain($params) {
        $query = urlencode($params["query"]);
        $XMLObject = new ProxyReceiveResultsNumber($query);
        $this->setXMLObject($XMLObject->getResultdata());
        $this->ExtractFromXML();
    }

    private function ExtractFromXML() {
        $dom = new DOMDocument();
        $xml = strval($this->getXMLObject());
        $dom->loadxml($xml);
        $result = $dom->getElementsByTagName('result');
        if ($result->length == 0) {
            $FinalArr = -1;
        } else {
            $FinalArr = $result->item(0)->getAttribute('numFound');
        }
        $this->setResultVar($FinalArr);
    }

}

?>