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

    private $Error;

    public function InnerObtain($params) {
        $this->Error = false;
        $query = urlencode($params["query"]);
        $XMLObject = new ProxyReceiveResultsNumber($query);
        $this->setXMLObject($XMLObject->getResultdata());
        $this->ExtractFromXML();
        unset($XMLObject);
    }

    private function ExtractFromXML() {
        $dom = new DOMDocument();
        $xml = $this->getXMLObject();
        if (is_array($xml)) {
            if (array_key_exists('Error', $xml)) {
                $this->ErrorHandling($xml['Error']);
                $this->Error = true;
                return;
            }
        }
        $dom->loadxml($xml);
        $result = $dom->getElementsByTagName('result');
        if ($result->length == 0) {
            $FinalArr = -1;
        } else {
            $FinalArr = $result->item(0)->getAttribute('numFound');
        }
        $this->setResultVar($FinalArr);
    }

    private function ErrorHandling($Code) {
        echo '</br></br>(Fatal Error): ';
        echo $Code;
        $this->setResultVar(array('Error'=>$Code));
    }

}

?>