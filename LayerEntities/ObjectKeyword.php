<?php

namespace LayerEntities;

require_once('DeCSDescriptor.php');
require_once(realpath(dirname(__FILE__)) . '/../SimpleLife/SimpleLifeMessages.php');

use SimpleLife\SimpleLifeMessage;
use LayerEntities\DeCSDescriptor;

class ObjectKeyword {

    private $keyword;
    private $DeCSList;
    private $KeywordRelatedTrees;
    private $langArr;
    private $InMainTree;
    private $ConnectionTime;

    public function setConnectionTime(int $time) {
        $this->ConnectionTime += $time;
    }

    public function getConnectionTime() {
        return $this->ConnectionTime;
    }

    public function __construct($keyword, $langArr) {
        $this->ConnectionTime = 0;
        $this->keyword = $keyword;
        $this->DeCSList = array();
        $this->KeywordRelatedTrees = array();
        $this->langArr = $langArr;
        $this->InMainTree = true;
    }

    public function getLang() {
        return $this->langArr;
    }

    public function getKeyword() {
        return $this->keyword;
    }

    //------------------------------------------------------------
    //------------------------------------------------------------
    //-THIS SECTION CONTAINS TREE EXPLORING----------------------
    //------------------------------------------------------------
    //------------------------------------------------------------

    public function AddKeywordRelatedTrees($List) {
        foreach ($List as $NewRelatedTree) {
            if (!(array_key_exists($NewRelatedTree, $this->KeywordRelatedTrees))) {
                $this->KeywordRelatedTrees[$NewRelatedTree] = false;
            }
        }
    }

    public function GetAllUnexploredTrees() {
        $Result = array();
        foreach ($this->KeywordRelatedTrees as $tree_id => $boolX) {
            if ($boolX == false) {
                array_push($Result, $tree_id);
            }
        }
        return $Result;
    }

    public function SetRelatedTreeAsExplored($Tree_id) {
        if (!(array_key_exists($Tree_id, $this->KeywordRelatedTrees))) {
            throw new SimpleLifeException(new \SimpleLife\UnexistantTreeId($Tree_id));
        }
        $this->KeywordRelatedTrees[$Tree_id] = true;
    }

    public function IsTreeExplored($Tree_id) {
        if (!(array_key_exists($Tree_id, $this->KeywordRelatedTrees))) {
            throw new SimpleLifeException(new \SimpleLife\UnexistantTreeId($Tree_id));
        }
        return $this->KeywordRelatedTrees[$Tree_id];
    }

    //------------------------------------------------------------
    //------------------------------------------------------------
    //-THIS SECTION CONTAINS RESULTS AND SUMMARIES
    //------------------------------------------------------------
    //------------------------------------------------------------

    public function Tree_List() {
        return array_keys($this->DeCSList);
    }

    public function getFullDeCSList() {
        $results = array();
        foreach ($this->DeCSList as $key => $DeCSDescriptor) {
            $tree_id = $DeCSDescriptor->ID();
            $term = $DeCSDescriptor->getTerm();
            $DeCS = $this->getDeCSAndDescendants($DeCSDescriptor);
            $results[$tree_id] = array('term' => $term, 'DeCS' => $DeCS);
        }
        return $results;
    }

    public function KeyWordInfo($SimpleLifeMessage) {
        $SimpleLifeMessage->AddEmptyLine();
        $SimpleLifeMessage->AddAsNewLine('Keyword ' . $this->keyword . ':');
        foreach ($this->DeCSList as $item) {
            $Descendants = '- Descendants: ' . $item->GetDescendantsAsString();
            $SynCount = count($this->getDeCSAndDescendants($item));
            $msg = $item->ID() . ' (' . $item->getTerm() . ') ' . $Descendants . '=> ' . $SynCount . ' Synonyms';
            $SimpleLifeMessage->AddAsNewLine($msg);
        }
    }

    //------------------------------------------------------------
    //------------------------------------------------------------
    //-THIS SECTION CONTAINS OPERATIONS ON DECSDESCRIPTOR OBJECT--
    //------------------------------------------------------------
    //------------------------------------------------------------

    private function getDeCSAndDescendants($item) {
        $res = $item->getDeCS();
        $des = $this->getDescendantDeCS($item);
        return array_unique(array_merge($res, $des));
    }

    private function getDescendantDeCS($item) {
        $descendantsSyn = array();
        foreach ($item->GetDescendants() as $tree_id) {
            $Descendants = $this->getDeCSAndDescendants($this->DeCSList[$tree_id]);
            $descendantsSyn = array_merge($descendantsSyn, $Descendants);
        }
        return $descendantsSyn;
    }

    public function AddDeCSBasic($tree_id, $term, $DeCS, $CompleteWithThis, $lang) {
        $msg = $term . ' (' . $tree_id . ' [' . $lang . ']' . ') --> ';
        if (!(array_key_exists($tree_id, $this->DeCSList))) {
            $msg = $msg . 'created: ' . count($DeCS) . ' synonyms';
            $DeCSObject = new DeCSDescriptor($tree_id, $term, $DeCS);
            $this->DeCSList[$tree_id] = $DeCSObject;
        } else {
            $DeCSObject = $this->DeCSList[$tree_id];
            if ($DeCSObject->IsComplete() == false) {
                $DeCSObject->setDeCS($DeCS);
                $msg = $msg . ' added ' . count($DeCS) . ' synonyms';
                if ($CompleteWithThis == true) {
                    $msg = $msg . ' (set as completed)';
                    $DeCSObject->setComplete();
                }
            } else {
                $msg = $msg . 'already completed';
            }
        }
        return $msg;
    }

    public function SetDescendantsByTree($tree_id, $Descendants) {
        if (!(array_key_exists($tree_id, $this->DeCSList))) {
            throw new SimpleLifeException(new \SimpleLife\TreeIdDoesntNotExist($tree_id));
        }
        $this->DeCSList[$tree_id]->SetDescendants($Descendants);
    }

}

?>