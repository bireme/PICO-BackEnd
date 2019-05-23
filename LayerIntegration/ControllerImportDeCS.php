<?php

namespace LayerIntegration;

require_once(realpath(dirname(__FILE__)) . '/../LayerIntegration/ProxyReceiveDeCS.php');
require_once(realpath(dirname(__FILE__)) . '/../LayerIntegration/ControllerImportModel.php');
require_once(realpath(dirname(__FILE__)) . '/../LayerIntegration/ControllerImportRelatedTrees.php');
require_once(realpath(dirname(__FILE__)) . '/../SimpleLife/SimpleLifeMessages.php');

use SimpleLife\SimpleLifeMessage;
use LayerIntegration\ProxyReceiveDeCS;
use LayerIntegration\ControllerImportModel;
use LayerIntegration\ControllerImportRelatedTrees;
use \DOMDocument;

class ControllerImportDeCS extends ControllerImportModel {

    private $ObjectKeyword;
    private $IsMainTree;

//------------------------------------------------------------
//------------------------------------------------------------
//-THIS SECTION CONTAINS MAIN OPERATIONS----------------------
//------------------------------------------------------------
//------------------------------------------------------------

    public function InnerObtain($params) {
        try {
            if (!(array_key_exists('ObjectKeyword', $params))) {
                throw new SimpleLifeException(new \SimpleLife\ObjectKeywordNotMatch());
            }
            $this->timeSum = $params['timeSum'];
            $this->ObjectKeyword = $params['ObjectKeyword'];
            if (is_object($this->ObjectKeyword) == false) {
                throw new SimpleLifeException(new \SimpleLife\ObjectKeywordNotMatch());
            }
            if (get_class($this->ObjectKeyword) != 'LayerEntities\ObjectKeyword') {
                throw new SimpleLifeException(new \SimpleLife\ObjectKeywordNotMatch());
            }
            if (!(array_key_exists('SimpleLifeMessage', $params))) {
                $this->SimpleLifeMessage = new SimpleLifeMessage('[Retrieveing deCS] Keyword= ' . $this->ObjectKeyword->getKeyword() . ' Langs =' . json_encode($this->ObjectKeyword->getLang()));
            } else {
                $this->SimpleLifeMessage = $params["SimpleLifeMessage"];
            }
            if (!(array_key_exists('tree_id', $params))) {
                $this->IsMainTree = true;
                $this->SimpleLifeMessage->AddEmptyLine();
                $this->SimpleLifeMessage->Add('Initial Trees: ');
                $fun = $this->InnerObtainByLang($this->ObjectKeyword->getKeyword());
                if ($fun) {
                    return $fun;
                }
            } else {
                $tree_id = $params['tree_id'];
                $this->IsMainTree = false;
                $fun = $this->InnerObtainByLang($tree_id);
                if ($fun) {
                    return $fun;
                }
                $this->ObjectKeyword->SetRelatedTreeAsExplored($tree_id);
            }
            $this->ExploreTreesAndDescendants();

            if ($this->IsMainTree == true) {
                $this->SimpleLifeMessage->SendAsLog();
            }
        } catch (SimpleLifeException $exc) {
            return $exc->PreviousUserErrorCode();
        }
    }

    private function ExploreTreesAndDescendants() {
        $RelatedList = $this->ObjectKeyword->GetAllUnexploredTrees();
        if (count($RelatedList) > 0) {
            $RelatedTrees = new ControllerImportRelatedTrees($this->ObjectKeyword, $this->SimpleLifeMessage, $this->timeSum);

            $fun = $RelatedTrees->ExploreRelatedTrees();
            if ($fun) {
                return $fun;
            }
        }
    }

//------------------------------------------------------------
//------------------------------------------------------------
//-THIS SECTION CONTAINS EXPLORING BY LANGUAGES----------------------
//------------------------------------------------------------
//------------------------------------------------------------

    public function InnerObtainByLang($key) {
        $i = 0;
        if ($this->IsMainTree == true) {
            $langs = array('en', 'pt', 'es');
        } else {
            $langs = $this->ObjectKeyword->getLang();
        }
        foreach ($langs as $lang) {
            $CheckDescendants = true;
            $isComplete = false;
            if ($i > 0 and ( $this->IsMainTree == false)) {
                $CheckDescendants = false;
            }
            if ($i == count($this->ObjectKeyword->getLang()) - 1) {
                $isComplete = true;
            }


            $fun = $this->getDeCSOneLang($key, $lang, $CheckDescendants, $isComplete);
            if ($fun) {
                return $fun;
            }
            $i++;
        }
    }

    private function getDeCSOneLang($key, $lang, $CheckDescendants, $isComplete) {
        $XMLObject = new ProxyReceiveDeCS($key, $lang, $this->IsMainTree, $this->timeSum);
        $fun = $XMLObject->POSTRequest();
        if ($fun) {
            return $fun;
        }
        $preresult = $XMLObject->getResultdata();
        $result = $preresult['result'];
        $this->setXMLObject($result);
        $fun = $this->ExtractFromXML($key, $CheckDescendants, $isComplete, $lang);
        if ($fun) {
            return $fun;
        }
        $timer = $preresult['timer'];
        $this->addTimer($timer);
        $this->ObjectKeyword->setConnectionTime($timer);
    }

    private function PushToKeywordObject($tree_id, $term, $DeCSArr, $isComplete, $lang) {
        $msg = $this->ObjectKeyword->AddDeCSBasic($tree_id, $term, $DeCSArr, $isComplete, $lang);
        $this->SimpleLifeMessage->AddAsNewLine($msg);
    }

//------------------------------------------------------------
//------------------------------------------------------------
//-THIS SECTION CONTAINS XML EXPLORATION---------------------
//------------------------------------------------------------
//------------------------------------------------------------

    private function getDeCS($item, $isComplete, $lang) {

        $DeCSTitle = $item->getElementsByTagName('self')->item(0)->getElementsByTagName('term')->item(0);
        $tree_id = $DeCSTitle->getAttribute('tree_id');
        $DeCSTitle = $DeCSTitle->textContent;
        $DeCS = $item->getElementsByTagName('record_list')->item(0)->getElementsByTagName('synonym_list')->item(0);
        $DeCS = $DeCS->getElementsByTagName('synonym');
        $FinalArr = array();
        array_push($FinalArr, $DeCSTitle);
        foreach ($DeCS as $item) {
            array_push($FinalArr, $item->nodeValue);
        }
        try {
            if (count($FinalArr) == 0) {
                throw new SimpleLifeException(new \SimpleLife\NoSynonymsFound($tree_id, $lang));
            }
            $this->PushToKeywordObject($tree_id, $DeCSTitle, $FinalArr, $isComplete, $lang);
        } catch (SimpleLifeException $exc) {
            return $exc->PreviousUserErrorCode();
        }
    }

    private function getDescendants($item) {

        $descendantsArr = array();
        $treesArr = array();
        $DeCSTitle = $item->getElementsByTagName('self')->item(0)->getElementsByTagName('term')->item(0);
        $tree_id = $DeCSTitle->getAttribute('tree_id');
        if (strlen($tree_id) == 0) {
            throw 'no data';
        }

        if ($this->IsMainTree == true) {
            $descendants = array();
            array_push($treesArr, $tree_id);
        } else {
            $descendants = $item->getElementsByTagName('descendants')->item(0)->getElementsByTagName('term');
        }

        $trees = $item->getElementsByTagName('record_list')->item(0)->getElementsByTagName('tree_id_list')->item(0);
        $trees = $trees->getElementsByTagName('tree_id');

        foreach ($descendants as $obj) {
            array_push($descendantsArr, $obj->getAttribute('tree_id'));
        }
        foreach ($trees as $obj) {
            array_push($treesArr, $obj->nodeValue);
        }
        $descendantsArr = array_unique($descendantsArr);
        $treesArr = array_unique($treesArr);
        $msg = '';
        if ($this->IsMainTree == false) {
            $this->ObjectKeyword->SetDescendantsByTree($tree_id, $descendantsArr);
            $msg = $msg . ' --> Descendants:{' . join($descendantsArr, ', ') . '} . ';
            $msg = $msg . 'Trees:{' . join($treesArr, ', ') . '}';
        } else {
            $msg = $msg . '  {' . join($treesArr, ', ') . '}';
        }
        $this->SimpleLifeMessage->Add($msg);
        $finalArr = array_merge($descendantsArr, $treesArr);
        try {
            if (count($finalArr) == 0) {
                throw new SimpleLifeException(new \SimpleLife\NoDescendantsNorTrees($tree_id, $lang));
            }

            $this->ObjectKeyword->AddKeywordRelatedTrees($finalArr);
        } catch (Exception $exc) {
            return $exc->PreviousUserErrorCode();
        }
    }

    private function ExtractFromXML($keyword, $CheckDescendants, $isComplete, $lang) {
        try {
            $dom = new DOMDocument();
            $xml = $this->getXMLObject();
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

            if ($dom->getElementsByTagName('decsvmx')->length == 0) {
                //throw new SimpleLifeException(new \SimpleLife\XMLNotBiremeType());
            }

            $result = $dom->getElementsByTagName('decsws_response');
            if ($result->length == 0) {
                //throw new SimpleLifeException(new \SimpleLife\NoResponsesException($keyword, $lang));
            }

            foreach ($result as $item) {
                if ($this->IsMainTree == false) {
                    $fun = $this->getDeCS($item, $isComplete, $lang);
                    if ($fun) {
                        return $fun;
                    }
                }
                if ($CheckDescendants == true) {
                    $fun = $this->getDescendants($item);
                    if ($fun) {
                        return $fun;
                    }
                }
            }
            unset($xml);
        } catch (SimpleLifeException $exc) {
            return $exc->PreviousUserErrorCode();
        }
    }

}

?>