<?php

namespace LayerIntegration;

require_once(realpath(dirname(__FILE__)) . '/../LayerIntegration/ProxyReceiveDeCSTitles.php');
require_once(realpath(dirname(__FILE__)) . '/../LayerIntegration/ControllerImportModel.php');

use LayerIntegration\ProxyReceiveDeCSTitles;
use LayerIntegration\ControllerImportModel;
use \DOMDocument;

/**
 * @access public
 * @author Daniel Nieto
 * @package LayerIntegration
 */
class ControllerImportDeCSTitles extends ControllerImportModel {

    public function InnerObtain($params) {
        $keyword = $params["keyword"];
        $XMLObject = new ProxyReceiveDeCSTitles($keyword);
        $this->setXMLObject($XMLObject->getResultdata());
        $this->ExtractFromXML();
    }

    private function ExtractFromXML() {
        $dom = new DOMDocument();
        $xml = $this->getXMLObject();
        $dom->loadXML($xml);
        $result = $dom->getElementsByTagName('Result');
        if ($result->length == 0) {
            $FinalArr = -1;
        } else {
            $result = $result->item(0)->getElementsByTagName('item');
            $FinalArr = array();
            foreach ($result as $item) {
                array_push($FinalArr, array('term' => $item->getAttribute('term'), 'tree_id' => $item->getAttribute('id')));
            }
        }
        $this->setResultVar($FinalArr);
    }

}

?>