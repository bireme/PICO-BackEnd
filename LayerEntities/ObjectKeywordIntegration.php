<?php

namespace LayerEntities;

require_once('DeCSDescriptor.php');
require_once(realpath(dirname(__FILE__)) . '/../SimpleLife/SimpleLifeMessages.php');
require_once(realpath(dirname(__FILE__)) . '/../SimpleLife/ManualExceptions.php');

use SimpleLife\SimpleLifeException;
use SimpleLife\SimpleLifeMessage;
use LayerEntities\DeCSDescriptor;

class ObjectKeywordIntegration {

    private $keyword;
    private $DeCSList;
    private $AllTreesList;
    private $langArr;
    private $InMainTree;
    private $ConnectionTime;
    private $Completed;
    private $MaxRelatedTrees;
    private $MaxDescendantExplores;

    public function getMaxDeepLevel() {
        return $this->MaxDescendantExplores;
    }

    public function SetAsCompleted() {
        $this->Completed = true;
    }

    public function IsCompleted() {
        return $this->Completed;
    }

    public function setConnectionTime(int $time) {
        $this->ConnectionTime += $time;
    }

    public function getConnectionTime() {
        return $this->ConnectionTime;
    }

    public function __construct($keyword, $langArr, $MaxImports) {
        $this->ConnectionTime = 0;
        $this->keyword = $keyword;
        $this->DeCSList = array();
        $this->AllTreesList = array();
        $this->langArr = $langArr;
        $this->InMainTree = true;
        $this->Completed = false;
        $this->MaxImports($MaxImports);
    }

    public function getLang() {
        return $this->langArr;
    }

    public function getKeyword() {
        return $this->keyword;
    }

    private function MaxImports($MaxImports) {
        $this->MaxRelatedTrees = $MaxImports['MaxRelatedTrees'];
        $this->MaxDescendantExplores = $MaxImports['MaxDescendantExplores'];
    }

    //------------------------------------------------------------
    //------------------------------------------------------------
    //-THIS SECTION CONTAINS TREE CREATION AND ORGANIZYNG----------------------
    //------------------------------------------------------------
    //------------------------------------------------------------

    public function getTreeObjFromMainList($tree_id) {
        if ((array_key_exists($tree_id, $this->AllTreesList))) {
            return $this->AllTreesList[$tree_id];
        }
        return NULL;
    }

    public function BuildNewDescriptor($tree_id, $RelatedOrTreeId, $SimpleLifeMessage) {
        $DeCSObject = $this->getTreeObjFromMainList($tree_id);
        if (!(isset($DeCSObject))) {
            $DeCSObject = new DeCSDescriptor($tree_id);
            $this->AllTreesList[$tree_id] = $DeCSObject;
        }
        if ($RelatedOrTreeId == -1) {
            $SimpleLifeMessage->AddAsNewLine('[' . $this->keyword . '] ' . $tree_id . ' -> Created Tree');
            return $DeCSObject;
        }
        if ($RelatedOrTreeId == 'Related') {
            if (!(array_key_exists($tree_id, $this->DeCSList))) {
                $this->DeCSList[$tree_id] = $DeCSObject;
                $SimpleLifeMessage->AddAsNewLine('[' . $this->keyword . '] ' . $tree_id . ' -> Added to Related Trees');
            }
        } else {
            $ParentDeCSObject = $this->getTreeObjFromMainList($RelatedOrTreeId);
            if (!(isset($ParentDeCSObject))) {
                throw new SimpleLifeException(new \SimpleLife\ParentTreeDoesNotExist($this->keyword, $tree_id, $RelatedOrTreeId, $this->AllTreesList));
            }
            $OldDescendants = $ParentDeCSObject->GetDescendants();
            if (!(array_key_exists($tree_id, $OldDescendants))) {
                $ParentDeCSObject->AddDescendant($DeCSObject, $tree_id);
                $SimpleLifeMessage->AddAsNewLine('[' . $this->keyword . '] ' . $RelatedOrTreeId . ' -> Added descendant:' . $tree_id);
            }
        }
        return $DeCSObject;
    }

    public function SetKeywordRelatedTrees($RelatedTreeList, $SimpleLifeMessage) {
        foreach ($RelatedTreeList as $tree_id) {
            $DeCSObject = $this->BuildNewDescriptor($tree_id, 'Related', $SimpleLifeMessage);
        }
    }

    private function getTreesToExplore() {
        $Result = array();
        $countX = 0;
        foreach ($this->DeCSList as $tree_id => $DeCSObject) {
            if ($countX >= $this->MaxRelatedTrees) {
                break;
            }
            $Result[$tree_id] = $DeCSObject;
            $countX++;
        }
        return $Result;
    }

    public function GetUnexploredTrees() {
        $Result = array();
        $TreeArr = $this->getTreesToExplore();
        foreach ($TreeArr as $tree_id => $DeCSObject) {
            if ($DeCSObject->isExplored() == false) {
                array_push($Result, $tree_id);
            }
        }
        return $Result;
    }

    public function SetTreeAsExplored($tree_id) {
        $DeCSObject = $this->getTreeObjFromMainList($tree_id);
        if (!(isset($DeCSObject))) {
            throw new SimpleLifeException(new \SimpleLife\TreeIdDoesntNotExist($tree_id));
        }
        $DeCSObject->setAsExplored();
    }

    public function ExploreTree($tree_id, $term, $DeCS, $lang, $SimpleLifeMessage) {
        $DeCSObject = $this->getTreeObjFromMainList($tree_id);
        if(!(isset($DeCSObject))){
            $DeCSObject=$this->BuildNewDescriptor($tree_id, -1, $SimpleLifeMessage);
        }
        $DeCSObject->SetTermAndDeCS($term, $DeCS, $lang);
        $SimpleLifeMessage->AddAsNewLine($tree_id . ' [' . $term . ' - ' . $lang . '] = {' . join($DeCS, ', ') . '}');
    }

    public function SetDescendantsByTree($tree_id, $DescendantsTreeIds, $SimpleLifeMessage) {
        $DeCSObject = $this->getTreeObjFromMainList($tree_id);
        if (!(isset($DeCSObject))) {
            throw new SimpleLifeException(new \SimpleLife\TreeIdDoesntNotExist($tree_id));
        }
        foreach ($DescendantsTreeIds as $Descendant_Treeid) {
            $this->BuildNewDescriptor($Descendant_Treeid, $tree_id, $SimpleLifeMessage);
        }
    }

    public function IsTreeExplored($tree_id) {
        $DeCSObject = $this->getTreeObjFromMainList($tree_id);
        if (!(isset($DeCSObject))) {
            return false;
        }
        return $DeCSObject->isExplored();
    }

    ///////////////////////////////////////////////////////////////////////////////
    //DESCENDANTS//////////////////////////////////////////////////////////////////
    ///////////////////////////////////////////////////////////////////////////////


    public function GetLocalUnexploredDescendants($tree_id) {
        $Result = array();
        $DeCSObject = $this->getTreeObjFromMainList($tree_id);
        if (!(isset($DeCSObject))) {
            throw new SimpleLifeException(new \SimpleLife\TreeIdDoesntNotExist($tree_id));
        }
        foreach ($DeCSObject->GetDescendants() as $tree_id => $Descendant) {
            if ($Descendant->isExplored() == false) {
                array_push($Result, $tree_id);
            }
        }
        return $Result;
    }

    private function BuildDescendantListInner($DescendantsArr, $Result) {
        foreach ($DescendantsArr as $tree_id => $MainObject) {
            if (!(array_key_exists($tree_id, $Result))) {
                $Result[$tree_id] = $MainObject['DeCSObject'];
            }
            if ((array_key_exists('Descendants', $MainObject))) {
                $Result = $this->BuildDescendantListInner($MainObject['Descendants'], $Result);
            }
        }
        return $Result;
    }

    private function BuildDescendantsTree() {
        $DeCSTrees = $this->getTreesToExplore();
        $Result = array();
        foreach ($DeCSTrees as $tree_id => $MainDeCSObject) {
            $Result[$tree_id] = array('DeCSObject' => $MainDeCSObject, 'Descendants' => $this->GetLocalDescendants($MainDeCSObject, 1));
        }
        return $Result;
    }

    private function GetLocalDescendants($DeCSObject, $level) {
        $Result = array();
        $DeCSObject = $this->getTreeObjFromMainList($tree_id);
        foreach ($DeCSObject->GetDescendants() as $DescendantDeCS) {
            if ($level + 1 > $this->MaxDescendantExplores) {
                $obj = array('DeCSObject' => $DescendantDeCS);
            } else {
                $obj = array('DeCSObject' => $DescendantDeCS, 'Descendants' => GetLocalDescendants($DescendantDeCS, $level + 1));
            }
            $Result[$DescendantDeCS->ID()] = $obj;
        }
        return $Result;
    }

    //------------------------------------------------------------
    //------------------------------------------------------------
    //-GETTING RESULTS
    //------------------------------------------------------------
    //------------------------------------------------------------

    public function getResults() {
        $results=array();
        $content = array();
        foreach ($this->DeCSList as $tree_id => $DeCSObject) {
            $Res = $DeCSObject->getResults(true);
            $TermsArr = $Res['TermsArr'];
            $DeCSArrLang = $Res['DeCS'];
            $Descendants = $Res['descendants'];
            $content[$tree_id] = array('TermsArr' => $TermsArr, 'DeCS'=>$DeCSArrLang,'descendants'=>$this->getDescendantData($Descendants));
        }
        return array('content' => $content, 'ConnectionTime'=>$this->getConnectionTime());
    }

    public function getDescendantData($DescendantArr) {
        $content=array();
        foreach ($DescendantArr as $tree_id => $DeCSObject) {
            $DeCSObjectResults = $DeCSObject->getResults(false);
            $DeCSArr= $DeCSObjectResults['DeCS'];
            $Descendants = $DeCSObjectResults['descendants'];
            $content[$tree_id]=array('DeCS'=>$DeCSArr,'descendants'=>$this->getDescendantData($Descendants));
        }
        return $content;
    }

}

?>