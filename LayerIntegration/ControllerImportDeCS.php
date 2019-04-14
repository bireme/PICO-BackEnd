<?php

namespace LayerIntegration;

require_once(realpath(dirname(__FILE__)) . '/../LayerIntegration/ProxyReceiveDeCS.php');
require_once(realpath(dirname(__FILE__)) . '/../LayerIntegration/ControllerImportModel.php');

use LayerIntegration\ProxyReceiveDeCS;
use LayerIntegration\ControllerImportModel;
use \DOMDocument;

/**
 * @access public
 * @author Daniel Nieto
 * @package LayerIntegration
 */
class ControllerImportDeCS extends ControllerImportModel {

    private $TmpResult;

    private function getTmpResult() {
        return $this->TmpResult;
    }

    private function setTmpResult($TmpResult) {
        $this->TmpResult = $TmpResult;
    }

    public function InnerObtain($params) {
        $tree_id = $params["tree_id"];
        $langs = $params["langs"];

        $ResultsArr = array();
        $langArr = explode(",", $langs);
        foreach ($langArr as $lang) {
            $this->getDeCSOneLang($tree_id, $lang);
            $tmpres = $this->getTmpResult();
            foreach ($tmpres as $item) {
                array_push($ResultsArr, $item);
            }
        }
        $this->setResultVar($ResultsArr);
    }

    private function getDeCSOneLang($tree_id, $lang) {
        $XMLObject = new ProxyReceiveDeCS($tree_id, $lang);
        $this->setXMLObject($XMLObject->getResultdata());
        $this->ExtractFromXML();
    }

    /**
     * @AssociationType LayerIntegration\ProxyReceiveDeCS
     */
    private function ExtractFromXML() {
        $dom = new DOMDocument();
        $xml = $this->getXMLObject();
        $dom->loadXML($xml);
        $result = $dom->getElementsByTagName('decsws_response');
        if ($result->length == 0) {
            $FinalArr = -1;
        } else {
            $result = $result->item(0)->getElementsByTagName('synonym');
            $FinalArr = array();
            foreach ($result as $item) {
                array_push($FinalArr, $item->textContent);
            }
        }
        $this->setTmpResult($FinalArr);
    }

}

?>