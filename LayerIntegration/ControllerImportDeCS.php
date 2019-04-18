<?php

namespace LayerIntegration;

require_once(realpath(dirname(__FILE__)) . '/../LayerIntegration/ProxyReceiveDeCS.php');
require_once(realpath(dirname(__FILE__)) . '/../LayerIntegration/ControllerImportModel.php');
require_once(realpath(dirname(__FILE__)) . '/../LayerIntegration/ControllerImportRelatedTrees.php');

use LayerIntegration\ProxyReceiveDeCS;
use LayerIntegration\ControllerImportModel;
use LayerIntegration\ControllerImportRelatedTrees;
use \DOMDocument;

/**
 * @access public
 * @author Daniel Nieto
 * @package LayerIntegration
 */
class ControllerImportDeCS extends ControllerImportModel {

    private $DeCSObject;
    private $langs;
    private $Descendants;
    private $Error;

    public function InnerObtain($params) {
        $this->langs = $params["langs"];
        $langArr = explode(",", $params["langs"]);
        $this->DeCSObject = $params["DeCSObject"];
        $this->Error = false;
        $this->Descendants = array();
        if (array_key_exists('keyword', $params)) {
            $keyword = $params["keyword"];
            $ByKeyword = true;
            $this->InnerObtainByLang($langArr, $keyword, $ByKeyword);
        } else {
            $tree_list = $params["tree_list"];
            $ByKeyword = false;
            foreach ($tree_list as $tree_id) {
                $this->InnerObtainByLang($langArr, $tree_id, $ByKeyword);
                if ($this->Error == false) {
                    break;
                }
            }
        }
        if (!($this->Error == true)) {
            $RelatedTrees = new ControllerImportRelatedTrees($this->DeCSObject, $this->Descendants, $this->langs);
            $RelatedTrees->ExploreRelatedTrees();
        }
    }

    private function ErrorHandling($Code) {
        echo '</br></br>(Fatal Error): ';
        echo $Code;
        $this->DeCSObject->SetError($Code);
    }

    public function InnerObtainByLang($langArr, $key, $ByKeyword) {
        $ResultsArr = array();
        $i = 0;
        foreach ($langArr as $lang) {
            $CheckDescendants = false;
            $isComplete = false;
            if ($i == 0) {
                $CheckDescendants = true;
            }
            if ($i == count($langArr) - 1) {
                $isComplete = true;
            }
            $this->getDeCSOneLang($key, $lang, $CheckDescendants, $isComplete, $ByKeyword);
            if ($this->Error == true) {
                return;
            }
            $i++;
        }
    }

    private function getDeCSOneLang($key, $lang, $CheckDescendants, $isComplete, $ByKeyword) {
        $XMLObject = new ProxyReceiveDeCS($key, $lang, $ByKeyword);
        $result=$XMLObject->getResultdata();
        $this->setXMLObject($result);
        $this->ExtractFromXML($CheckDescendants, $isComplete);
        unset($XMLObject);
    }

    private function PushToDeCSObject($tree_id, $term, $DeCSArr, $isComplete) {
        $this->DeCSObject->AddDeCSBasic($tree_id, $term, $DeCSArr, $isComplete);
    }

    private function getDescendants($item) {
        $DeCSTitle = $item->getElementsByTagName('self')->item(0)->getElementsByTagName('term')->item(0);
        $tree_id = $DeCSTitle->getAttribute('tree_id');
        $descendants = $item->getElementsByTagName('descendants')->item(0)->getElementsByTagName('term');
        $trees = $item->getElementsByTagName('record_list')->item(0)->getElementsByTagName('tree_id_list')->item(0);
        $trees = $trees->getElementsByTagName('tree_id');
        $descendantsArr = array();
        $treesArr = array();
        foreach ($descendants as $obj) {
            array_push($descendantsArr, $obj->getAttribute('tree_id'));
        }
        foreach ($trees as $obj) {
            array_push($treesArr, $obj->nodeValue);
        }
        $this->DeCSObject->SetDescendantsByTree($tree_id, $descendantsArr);
        echo '</br>Descendants:{' . join($descendantsArr, ', ') . '} ';
        echo '   Trees:{' . join($treesArr, ', ') . '}</br>';
        $finalArr = array_merge($descendantsArr, $treesArr);
        $this->Descendants = array_merge($this->Descendants, $finalArr);
    }

    private function getDeCS($item, $isComplete) {

        $DeCSTitle = $item->getElementsByTagName('self')->item(0)->getElementsByTagName('term')->item(0);
        $tree_id = $DeCSTitle->getAttribute('tree_id');
        $DeCSTitle = $DeCSTitle->textContent;
        $DeCS = $item->getElementsByTagName('record_list')->item(0)->getElementsByTagName('synonym_list')->item(0);
        $DeCS = $DeCS->getElementsByTagName('synonym');
        $FinalArr = array();
        foreach ($DeCS as $item) {
            array_push($FinalArr, $item->nodeValue);
        }
        $this->PushToDeCSObject($tree_id, $DeCSTitle, $FinalArr, $isComplete);
    }

    private function ExtractFromXML($CheckDescendants, $isComplete) {
        $dom = new DOMDocument();
        $xml = $this->getXMLObject();
        if (is_array($xml)) {
            if (array_key_exists('Error', $xml)) {
                $this->ErrorHandling($xml['Error']);
                $this->Error = true;
                return;
            }
        }
        $dom->loadXML($xml);
        $result = $dom->getElementsByTagName('decsws_response');
        if ($result->length == 0) {
            return -1;
        }
        foreach ($result as $item) {
            $this->getDeCS($item, $isComplete);
            if ($CheckDescendants == true) {
                $this->getDescendants($item);
            }
        }
        unset($xml);
    }

}

?>