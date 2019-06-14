<?php

namespace LayerIntegration;

require_once(realpath(dirname(__FILE__)) . '/../LayerIntegration/ProxyReceiveDeCS.php');
require_once(realpath(dirname(__FILE__)) . '/../LayerIntegration/ControllerImportRelatedTrees.php');
require_once(realpath(dirname(__FILE__)) . '/../SimpleLife/SimpleLifeMessages.php');

use SimpleLife\SimpleLifeMessage;
use LayerIntegration\ProxyReceiveDeCS;
use LayerIntegration\ControllerImportRelatedTrees;
use \DOMDocument;

class ControllerImportDeCS {

    private $ObjectKeyword;
    private $IsMainTree;
    private $RelatedTreesHandler;
    private $connectionTimer;
    private $SimpleLifeMessage;
    private $level;
    private $timeSum;

//------------------------------------------------------------
//------------------------------------------------------------
//-THIS SECTION CONTAINS MAIN OPERATIONS----------------------
//------------------------------------------------------------
//------------------------------------------------------------   

    
    public function MaxDeepLevelExceded(){
        return $this->ObjectKeyword->getMaxDeepLevel()< $this->level;
    }
        
    public function RaiseDeepLevl() {
        return $this->level++;
    }

    public function getTimer() {
        return $this->connectionTimer;
    }

    public function addTimer($time) {
        $this->connectionTimer += $time;
    }

    public function startTimer() {
        $this->connectionTimer = 0;
    }

    public function __construct($ObjectKeyword, $timeSum, $SimpleLifeMessage,$level) {
        $this->level=$level;
        $this->timeSum = $timeSum;
        $this->IsMainTree = true;
        $this->ObjectKeyword = $ObjectKeyword;
        $this->SimpleLifeMessage = $SimpleLifeMessage;
    }

    public function InnerObtain($caller) {
        $this->caller = $caller;
        if ($caller != 'main') {
            $this->IsMainTree = false;
        }
        $this->SimpleLifeMessage->AddAsNewLine('Exploring inner obtain of '.$caller);
        $this->RelatedTreesHandler = new ControllerImportRelatedTrees($this->ObjectKeyword, $this->timeSum, $this->SimpleLifeMessage, $this->caller,$this->level);
        return $this->InnerObtainByLang() ||
                $this->RelatedTreesHandler->ExploreRelated();
    }

//------------------------------------------------------------
//------------------------------------------------------------
//-THIS SECTION CONTAINS EXPLORING BY LANGUAGES----------------------
//------------------------------------------------------------
//------------------------------------------------------------

    public function InnerObtainByLang() {
        if ($this->IsMainTree) {
            $key = $this->ObjectKeyword->getKeyword();
            $langs = array('en', 'pt', 'es');
        } else {
            $key = $this->caller;
            $langs = $this->ObjectKeyword->getLang();
        }
        foreach ($langs as $lang) {
            if ($fun = $this->importDeCSOneLang($key, $lang)) {
                return $fun;
            }
        }
    }

    private function importDeCSOneLang($key, $lang) {
        $XMLObject = new ProxyReceiveDeCS($key, $lang, $this->IsMainTree, $this->timeSum);
        $result=NULL;
        if ($fun = $XMLObject->POSTRequest($result)) {
            return $fun;
        }
        $timer = $result['timer'];
        $this->addTimer($timer);
        $this->ObjectKeyword->setConnectionTime($timer);
        return $this->ExtractFromXML($result['data'], $key, $lang);
    }

//------------------------------------------------------------
//------------------------------------------------------------
//-THIS SECTION CONTAINS XML EXPLORATION---------------------
//------------------------------------------------------------
//------------------------------------------------------------

    private function importDeCS($item, $lang) {
        $DeCSTitle = $item->getElementsByTagName('self')->item(0)->getElementsByTagName('term')->item(0);
        $tree_id = $DeCSTitle->getAttribute('tree_id');
        $term = $DeCSTitle->textContent;
        $DeCSObj = $item->getElementsByTagName('record_list')->item(0)->getElementsByTagName('synonym_list')->item(0)->getElementsByTagName('synonym');
        $DeCSArr = array();
        foreach ($DeCSObj as $item) {
            array_push($DeCSArr, $item->nodeValue);
        }
        $this->ObjectKeyword->ExploreTree($tree_id, $term, $DeCSArr, $lang, $this->SimpleLifeMessage);
    }

    private function getRelatedAndDescendants($item) {
        $tree_id = $item->getElementsByTagName('self')->item(0)->getElementsByTagName('term')->item(0)->getAttribute('tree_id');
        if (strlen($tree_id) == 0) {
            throw 'no data';
        }

        $descendantTreeArr = array();
        $descendants = $item->getElementsByTagName('descendants')->item(0)->getElementsByTagName('term');
        foreach ($descendants as $obj) {
            array_push($descendantTreeArr, $obj->getAttribute('tree_id'));
        }

        $treesArr = array();
        $trees = $item->getElementsByTagName('record_list')->item(0)->getElementsByTagName('tree_id_list')->item(0);
        $trees = $trees->getElementsByTagName('tree_id');
        foreach ($trees as $obj) {
            array_push($treesArr, $obj->nodeValue);
        }
        $treesArr=array_unique($treesArr);
        $descendantTreeArr=array_unique($descendantTreeArr);
        $this->ObjectKeyword->SetKeywordRelatedTrees($treesArr, $this->SimpleLifeMessage);
        if($this->IsMainTree==false){
            $this->ObjectKeyword->SetDescendantsByTree($tree_id, $descendantTreeArr, $this->SimpleLifeMessage);    
        }
        
    }

    private function ImportDeCSRelatedAndDescendants($item, $lang) {
        $fun = $this->importDeCS($item, $lang);
        return $fun || $this->getRelatedAndDescendants($item);
    }

    private function ExtractFromXML($xml, $keyword, $lang) {
        try {
            $dom = new DOMDocument();
            if (is_array($xml)) {
                if (array_key_exists('Error', $xml)) {
                    throw new SimpleLifeException(new \SimpleLife\ErrorTagInXML($keyword));
                }
            }
            try {
                $dom->loadxml($xml);
            } catch (Exception $exc) {
                throw new SimpleLifeException(new \SimpleLife\XMLLoadException($exc->ToString()));
            }

            $result = $dom->getElementsByTagName('decsws_response');
            foreach ($result as $item) {
                if($fun = $this->ImportDeCSRelatedAndDescendants($item, $lang)){
                    return $fun;
                }
            }
        } catch (SimpleLifeException $exc) {
            return $exc->PreviousUserErrorCode();
        }
    }

}

?>