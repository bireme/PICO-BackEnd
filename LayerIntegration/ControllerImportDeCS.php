<?php

namespace LayerIntegration;

require_once(realpath(dirname(__FILE__)) . '/../LayerIntegration/ProxyReceiveDeCS.php');
require_once(realpath(dirname(__FILE__)) . '/../LayerIntegration/ControllerImportModel.php');
require_once(realpath(dirname(__FILE__)) . '/../LayerIntegration/ControllerImportRelatedTrees.php');

use LayerIntegration\ProxyReceiveDeCS;
use LayerIntegration\ControllerImportModel;
use LayerIntegration\ControllerImportRelatedTrees;
use \DOMDocument;

class ControllerImportDeCS extends ControllerImportModel {

    private $DeCSObject;
    private $langs;
    private $Descendants;

    public function InnerObtain($params) {
        $this->langs = $params["langs"];
        $this->DeCSObject = $params["DeCSObject"];

        if (is_object($params["DeCSObject"]) == false) {
            $Ex = new DeCSObjectNotMatch();
            $this->DeCSObject = NULL;
            throw new IntegrationExceptions($Ex->build());
        }
        if (get_class($params["DeCSObject"]) != 'LayerEntities\ObjectDeCS') {
            $Ex = new DeCSObjectNotMatch();
            $this->DeCSObject = NULL;
            throw new IntegrationExceptions($Ex->build());
        }

        if (strlen($params["langs"]) == 0) {
            $Ex = new NoLanguages();
            $this->DeCSObject->setNull();
            throw new IntegrationExceptions($Ex->build());
        }
        $langArr = explode(",", $params["langs"]);
        if (count($langArr) == 0) {
            $Ex = new NoArrLanguages();
            $this->DeCSObject->setNull();
            throw new IntegrationExceptions($Ex->build());
        }

        foreach ($langArr as $lang) {
            if (!($lang == "es" || $lang == "en" || $lang == "pt")) {
                $Ex = new UnrecognizedLanguage($lang);
                $this->DeCSObject->setNull();
                throw new IntegrationExceptions($Ex->build());
            }
        }

        $this->Error = false;
        if (array_key_exists('keyword', $params)) {
            $MaximumQuerySize = 5000;
            $keyword = str_replace("  ", " ", $params["keyword"]);
            $keyword = str_replace(" ", "*", trim($keyword));
            if (strlen($keyword) == 0) {
                $Ex = new EmptyKeyword();
                $this->DeCSObject->setNull();
                throw new IntegrationExceptions($Ex->build());
            }
            if (strlen($keyword) > $MaximumQuerySize) {
                $Ex = new KeywordTooLarge(strlen($keyword), $MaximumQuerySize);
                throw new IntegrationExceptions($Ex->build());
            }
            $ByKeyword = true;
            try {
                $this->InnerObtainByLang($langArr, $keyword, $ByKeyword);
            } catch (IntegrationExceptions $exc) {
                if ($exc->HandleError()) {
                    $this->DeCSObject->setNull();
                    $Ex = new SuspendingDeCSError();
                    throw new IntegrationExceptions($Ex->build(),$exc->PreviousUserErrorCode());
                }
            }
        } else {
            $tree_list = $params["tree_list"];
            $ByKeyword = false;
            foreach ($tree_list as $tree_id) {
                try {
                    $this->InnerObtainByLang($langArr, $tree_id, $ByKeyword);
                } catch (IntegrationExceptions $exc) {
                    if ($exc->HandleError()) {
                        $this->DeCSObject->setNull();
                        $Ex = new SuspendingDeCSError();
                        throw new IntegrationExceptions($Ex->build(),$exc->PreviousUserErrorCode());
                    }
                }
            }
        }
        $Descendants = $this->DeCSObject->getDescendants();
        if (count($Descendants) > 0) {
            $RelatedTrees = new ControllerImportRelatedTrees($this->DeCSObject, $Descendants, $this->langs);
            try {
                $RelatedTrees->ExploreRelatedTrees();
            } catch (IntegrationExceptions $exc) {
                if ($exc->HandleError()) {
                    $this->DeCSObject->setNull();
                    $Ex = new SuspendingDeCSError();
                    throw new IntegrationExceptions($Ex->build(),$exc->PreviousUserErrorCode());
                }
            } finally {
                $this->addTimer($RelatedTrees->getTimer());
            }
        }
    }

    public function InnerObtainByLang($langArr, $key, $ByKeyword) {
        $ResultsArr = array();
        $i = 0;
        foreach ($langArr as $lang) {
            $CheckDescendants = true;
            $isComplete = false;
            if ($i > 0 & $ByKeyword == false) {
                $CheckDescendants = false;
            }
            if ($i == count($langArr) - 1) {
                $isComplete = true;
            }
            try {
                $this->getDeCSOneLang($key, $lang, $CheckDescendants, $isComplete, $ByKeyword);
            } catch (IntegrationExceptions $exc) {
                if ($exc->HandleError()) {
                    $Ex = new JustReturnException();
                    throw new IntegrationExceptions($Ex->build(),$exc->PreviousUserErrorCode());
                }
            }
            $i++;
        }
    }

    private function getDeCSOneLang($key, $lang, $CheckDescendants, $isComplete, $ByKeyword) {
        try {
            $XMLObject = new ProxyReceiveDeCS($key, $lang, $ByKeyword);
            $XMLObject->POSTRequest();
        } catch (IntegrationExceptions $exc) {
            if ($exc->HandleError()) {
                $Ex = new JustReturnException();
                throw new IntegrationExceptions($Ex->build(),$exc->PreviousUserErrorCode());
            }
        } finally {
            $result = $XMLObject->getResultdata();
            $timer = $result['timer'];
            $this->addTimer($timer);
        }
        $result = $result['result'];


        $this->setXMLObject($result);

        try {
            $this->ExtractFromXML($key, $CheckDescendants, $isComplete, $ByKeyword, $lang);
        } catch (IntegrationExceptions $exc) {
            if ($exc->HandleError()) {
                $Ex = new JustReturnException();
                throw new IntegrationExceptions($Ex->build(),$exc->PreviousUserErrorCode());
            }
        }
        unset($XMLObject);
    }

    private function PushToDeCSObject($tree_id, $term, $DeCSArr, $isComplete) {
        $this->DeCSObject->AddDeCSBasic($tree_id, $term, $DeCSArr, $isComplete);
    }

    private function getDescendants($item, $ByKeyword) {
        $descendantsArr = array();
        $treesArr = array();

        try {
            $DeCSTitle = $item->getElementsByTagName('self')->item(0)->getElementsByTagName('term')->item(0);
            $tree_id = $DeCSTitle->getAttribute('tree_id');
            if (strlen($tree_id) == 0) {
                throw 'no data';
            }
        } catch (Exception $exc) {
            $Ex = new CouldntGetItemTreeId();
            throw new IntegrationExceptions($Ex->build());
        }

        if ($ByKeyword == true) {
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
        $treesArr = array_unique($treesArr);
        if ($ByKeyword == false) {
            $this->DeCSObject->SetDescendantsByTree($tree_id, $descendantsArr);
            echo '</br>Descendants:{' . join($descendantsArr, ', ') . '} ';
        }
        echo '   Trees:{' . join($treesArr, ', ') . '}</br>';
        $finalArr = array_merge($descendantsArr, $treesArr);

        if (count($finalArr) == 0) {
            $Ex = new NoDescendantsNorTrees($tree_id, $lang);
            throw new IntegrationExceptions($Ex->build());
        }

        $finalArr = array_unique(array_merge($this->DeCSObject->getDescendants(), $finalArr));
        $this->DeCSObject->setDescendants($finalArr);
    }

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
        if (count($FinalArr) == 0) {
            $Ex = new NoSynonymsFound($tree_id, $lang);
            throw new IntegrationExceptions($Ex->build());
        }
        $this->PushToDeCSObject($tree_id, $DeCSTitle, $FinalArr, $isComplete);
    }

    private function ExtractFromXML($keyword, $CheckDescendants, $isComplete, $ByKeyword, $lang) {
        $dom = new DOMDocument();
        $xml = $this->getXMLObject();
        if (is_array($xml)) {
            if (array_key_exists('Error', $xml)) {
                $Ex = new ErrorTagInXML($keyword);
                throw new IntegrationExceptions($Ex->build());
            }
        }

        try {
            $dom->loadxml($xml);
        } catch (Exception $exc) {
            $Ex = new XMLLoadException($exc->ToString());
            throw new IntegrationExceptions($Ex->build());
        }
        if ($dom->getElementsByTagName('decsvmx')->length == 0) {
            $Ex = new XMLNotBiremeType();
            throw new IntegrationExceptions($Ex->build());
        }

        $result = $dom->getElementsByTagName('decsws_response');
        if ($result->length == 0) {
            $Ex = new NoResponsesException($keyword, $lang);
            throw new IntegrationExceptions($Ex->build());
        }
        foreach ($result as $item) {
            if ($ByKeyword == false) {
                try {
                    $this->getDeCS($item, $isComplete, $lang);
                } catch (IntegrationExceptions $exc) {
                    if ($exc->HandleError()) {
                        $Ex = new JustReturnException();
                        throw new IntegrationExceptions($Ex->build(),$exc->PreviousUserErrorCode());
                    }
                }
            }
            if ($CheckDescendants == true) {
                try {
                    $this->getDescendants($item, $ByKeyword);
                } catch (IntegrationExceptions $exc) {
                    if ($exc->HandleError()) {
                        $Ex = new JustReturnException();
                        throw new IntegrationExceptions($Ex->build(),$exc->PreviousUserErrorCode());
                    }
                }
            }
        }
        unset($xml);
    }

}

?>