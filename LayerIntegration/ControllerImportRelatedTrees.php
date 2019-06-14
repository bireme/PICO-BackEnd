<?php

namespace LayerIntegration;

class ControllerImportRelatedTrees {

    private $ObjectKeyword;
    private $SimpleLifeMessage;
    private $timeSum;
    private $ImportHandler;
    private $tree_id;
    private $caller;

    public function __construct($ObjectKeyword, $timeSum, $SimpleLifeMessage, $caller, $level) {
        $this->ObjectKeyword = $ObjectKeyword;
        $this->SimpleLifeMessage = $SimpleLifeMessage;
        $this->timeSum = $timeSum;
        $this->level = $level;
        $this->caller = $caller;
        $this->ImportHandler = new ControllerImportDeCS($ObjectKeyword, $timeSum, $SimpleLifeMessage, $this->level);
    }

    public function ExploreRelated() {
        if ($this->caller != 'main') {
            $this->ObjectKeyword->SetTreeAsExplored($this->caller);
            $this->ExploreDescendants();
        }
        $this->ExploreRelatedTrees();
    }

    private function ExploreRelatedTrees() {
        $RelatedList = $this->ObjectKeyword->GetUnexploredTrees();
        return $this->ExploreGenericTree($RelatedList, false);
    }

    private function ExploreDescendants() {
        $DescendantList = $this->ObjectKeyword->GetLocalUnexploredDescendants($this->caller);
        return $this->ExploreGenericTree($DescendantList, true);
    }

    private function ExploreGenericTree($TreeArr, $AreDescendants) {
        foreach ($TreeArr as $tree_id) {
            if ($fun = $this->LaunchImportDeCS($tree_id, $AreDescendants)) {
                return $fun;
            }
        }
    }

    private function LaunchImportDeCS($tree_id, $AreDescendants) {
        if ($this->ObjectKeyword->IsTreeExplored($tree_id)) {
            if ($AreDescendants == true) {
                $this->ObjectKeyword->BuildNewDescriptor($tree_id, $this->caller, $this->SimpleLifeMessage); //Adds descroptor to its parent without exploring
            }
        } else {
            if ($AreDescendants == true) {
                $this->ImportHandler->RaiseDeepLevl();
                if($this->ImportHandler->MaxDeepLevelExceded()){
                    return;
                }
            }
            $this->SimpleLifeMessage->AddAsNewLine($this->caller . '-> Exploring ' . $tree_id);
            return $this->ImportHandler->InnerObtain($tree_id);
        }
    }

}

?>