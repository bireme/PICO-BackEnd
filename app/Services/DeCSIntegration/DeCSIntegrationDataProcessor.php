<?php

namespace PICOExplorer\Services\DeCSIntegration;

use PICOExplorer\Facades\UltraLoggerFacade;
use PICOExplorer\Models\DataTransferObject;
use PICOExplorer\Services\AdvancedLogger\Services\UltraLoggerDevice;

abstract class DeCSIntegrationDataProcessor extends DTOManager
{
    //data['lang']=[obj,obj,obj,obj]
    //obj['term']
//obj['tree_id']
//obj['decs']
//obj['trees']
//obj['descendants']

    protected function ProcessImportResults(array $resultsByLang, bool $IsMainTree, DataTransferObject $DTO, string $queryTitle, UltraLoggerDevice $Log)
    {
        UltraLoggerFacade::MapArrayIntoUltraLogger($Log, 'Integration results for ' . $queryTitle, ['IntegrationResults' => $resultsByLang], 2, 'tree_id');
        $TreeList = [];

        foreach ($resultsByLang as $lang => $TreeListObj) {
            if (!($TreeListObj)) {
                continue;
            }
            foreach ($TreeListObj as $index => $TreeData) {
                $tree_id = $TreeData['tree_id'] ?? null;
                if ($tree_id) {
                    $TreeList[$tree_id] = $TreeData;
                }
            }
        }
        if (count($TreeList) === 0) {
            UltraLoggerFacade::WarningToUltraLogger($Log, 'No trees were found in this loop');
            return null;
        }else{
            if($IsMainTree){
                $info=$this->addToMainTreeList(array_keys($TreeList),$DTO);
                $added = $info['added'];
                $cut = $info['cut'];
                if(count($added)) {
                    UltraLoggerFacade::InfoToUltraLogger($Log, 'The following keys were added to main trees ' . json_encode($info['added']));
                }
                if(count($cut)) {
                    UltraLoggerFacade::WarningToUltraLogger($Log, 'The following keys were not included in master list ' . json_encode($info['cut']));
                }
            }
        }
        $CurrentResults = $this->getResultsOrderedByTreeId($DTO);
        $OfficialLangs = $this->getLangs($DTO);

        $currentTrees = [];
        $descendants = [];
        $trees = [];

        $LogData = UltraLoggerFacade::UltraLoggerAttempt($Log, 'Adding integration data to results');

        foreach ($TreeList as $tree_id => $newTreeData) {
            if ($newTreeData) {
                if (!(array_key_exists($tree_id, $CurrentResults))) {
                    $CurrentResults[$tree_id] = [];
                    $CurrentResults[$tree_id]['descendants'] = [];
                    $CurrentResults[$tree_id]['remaininglangs'] = $OfficialLangs;
                    UltraLoggerFacade::InfoToUltraLogger($Log, 'Created Tree ' . $tree_id . ' into results');
                }
                if (!(array_key_exists($lang, $CurrentResults[$tree_id]))) {
                    $CurrentResults[$tree_id][$lang] = [];
                    UltraLoggerFacade::InfoToUltraLogger($Log, 'Created ' . $tree_id . '[.$lang.]' . ' into results');
                }
                $decs = $this->RemoveCommasFromDeCS(array_merge([$newTreeData['term']], $newTreeData['decs']));
                $term = $decs[0];
                $CurrentResults[$tree_id][$lang] = [
                    'term' => $term,
                    'decs' => $decs,
                ];
                $NewDescendants = $this->HandleTreeDescendantsSaving($DTO, $Log, $IsMainTree, $tree_id, $newTreeData['descendants'] ?? []);
                $CurrentResults[$tree_id]['descendants'] = $NewDescendants;

                UltraLoggerFacade::InfoToUltraLogger($Log, 'Saved Lang="' . $lang . '" in tree ' . $tree_id);
                UltraLoggerFacade::InfoToUltraLogger($Log, $tree_id . '[' . $lang . '] => Term:' . $term . '. DeCS=' . count($decs) . ' Descendants=' . count($NewDescendants));

                if (($langkey = array_search($lang, $CurrentResults[$tree_id]['remaininglangs'])) !== false) {
                    unset($CurrentResults[$tree_id]['remaininglangs'][$langkey]);
                    UltraLoggerFacade::InfoToUltraLogger($Log, 'Tree´s (' . $tree_id . ') pending Langs. Remaining:' . json_encode($CurrentResults[$tree_id]['remaininglangs']));
                }
                array_push($currentTrees, $tree_id);
                $descendants = array_merge($descendants, $NewDescendants);
                $trees = array_merge($trees, $newTreeData['trees']);
            }
        }

        $this->saveResultsOrderedByTreeId($DTO, $CurrentResults);
        UltraLoggerFacade::UltraLoggerSuccessfulAttempt($Log, $LogData);

        //$LogData = UltraLoggerFacade::UltraLoggerAttempt($Log, 'Managing Trees recently obtained');
        //$this->TreeManager($IsMainTree, array_unique($descendants),array_unique($currentTrees),  $DTO, $Log);
        //UltraLoggerFacade::UltraLoggerSuccessfulAttempt($Log, $LogData);
    }

    /////////////////////////////////////////////////////////
    /////////////////////////////////////////////////////////
    /// INNER FUNCTIONS
    /////////////////////////////////////////////////////////
    /////////////////////////////////////////////////////////

    private function TreeManager(bool $IsMainTree, array $descendants, array $trees, DataTransferObject $DTO, UltraLoggerDevice $Log)
    {
        if ($IsMainTree) {
            if ($this->ShouldISaveDescendantsOfPrimaryMainTreesIntoMainTrees()) {
                $LogData = UltraLoggerFacade::UltraLoggerAttempt($Log, 'Attempting to save Main´s descendants into main trees');
                $info = $this->addToMainTreeList($descendants, $DTO);
                $countAdd = count($info['add']);
                $countCut = count($info['cut']);
                UltraLoggerFacade::UltraLoggerSuccessfulAttempt($Log, $LogData);
                if ($countAdd > 0) {
                    if ($countCut > 0) {
                        UltraLoggerFacade::WarningToUltraLogger($Log, 'MainList Full. Couldnt add descendants: ' . json_encode($info['cut']));
                    }
                } else {
                    if ($countCut > 0) {
                        UltraLoggerFacade::WarningToUltraLogger($Log, 'MainList Full. Couldnt add any descendant: ' . json_encode($info['cut']));
                    } else {
                        UltraLoggerFacade::InfoToUltraLogger($Log, 'There were no new trees to add to MainList');
                    }
                }
            }
            if ($this->ShouldISaveRelatedTreesOfPrimaryMainTreesIntoMainTrees()) {
                $LogData = UltraLoggerFacade::UltraLoggerAttempt($Log, 'Attempting to save Main´s related trees into main trees');
                $info = $this->addToMainTreeList($trees, $DTO);
                $countAdd = count($info['add']);
                $countCut = count($info['cut']);
                UltraLoggerFacade::UltraLoggerSuccessfulAttempt($Log, $LogData);
                if ($countAdd > 0) {
                    if ($countCut > 0) {
                        UltraLoggerFacade::WarningToUltraLogger($Log, 'MainList Full. Couldnt add related trees: ' . json_encode($info['cut']));
                    }
                } else {
                    if ($countCut > 0) {
                        UltraLoggerFacade::WarningToUltraLogger($Log, 'MainList Full. Couldnt add any related tree: ' . json_encode($info['cut']));
                    } else {
                        UltraLoggerFacade::InfoToUltraLogger($Log, 'There were no new trees to add to MainList');
                    }
                }
            }
        }
    }

    private function HandleTreeDescendantsSaving(DataTransferObject $DTO, UltraLoggerDevice $Log, bool $IsMainTree, string $tree_id, array $descendants)
    {
        if($IsMainTree) {
            $descendants = $this->ToTreeData($DTO, $Log, $descendants, $tree_id,$IsMainTree);
            return $descendants;
        }
        $LogData = UltraLoggerFacade::UltraLoggerAttempt($Log, 'Attempting to retrieve tree depth');
        $info = $this->IsTreeDepthOk($tree_id, $DTO);
        $maxdepth = $info['maxdepth'];
        $currentdepth = $info['currentdepth'];
        UltraLoggerFacade::UltraLoggerSuccessfulAttempt($Log, $LogData);
        $title = 'DEPTH: tree_id=' . json_encode($currentdepth) . ' descendants=' . json_encode($currentdepth + 1) . ' max=' . json_encode($maxdepth);
        if (($currentdepth + 1) <= $maxdepth) {
            $title = '[Granted] ' . $title;
            UltraLoggerFacade::InfoToUltraLogger($Log, $title);
            $ProcessedDescendants = $this->ToTreeData($DTO, $Log, $descendants, $tree_id,$IsMainTree);
            return $ProcessedDescendants;
        } else {
            $title = '[Failed] ' . $title;
            UltraLoggerFacade::WarningToUltraLogger($Log, $title);
            return [];
        }

    }

    ////////////////////////////////
    /// NOT LOGGED
    /// ///////////////////////////////////

    private function ToTreeData(DataTransferObject $DTO, UltraLoggerDevice $Log, array $descendants, string $tree_id, bool $isMain)
    {
        $LogData = UltraLoggerFacade::UltraLoggerAttempt($Log, 'Processing descentants to add to tree_id ' . $tree_id);
        $info = $this->addDescendantsToTree($descendants, $tree_id, $isMain, $DTO);
        $countAdd = count($info['added']);
        $countCut = count($info['cut']);
        UltraLoggerFacade::UltraLoggerSuccessfulAttempt($Log, $LogData);
        if ($countAdd > 0) {
            if ($countCut > 0) {
                UltraLoggerFacade::WarningToUltraLogger($Log, 'List Full. Couldnt add descendants: ' . json_encode($info['cut']));
            }
        } else {
            if ($countCut > 0) {
                UltraLoggerFacade::WarningToUltraLogger($Log, 'List Full. Couldnt add any descendant: ' . json_encode($info['cut']));
            } else {
                UltraLoggerFacade::InfoToUltraLogger($Log, 'There were no new descenants to add to this tree');
            }
        }
        return $info['added'];
    }


    private function RemoveCommasFromDeCS(array $DeCSarray)
    {
        $results = [];
        foreach ($DeCSarray as $DeCSWord) {
            $DeCSWordSplitByComma = explode(', ', $DeCSWord);
            if (count($DeCSWordSplitByComma) > 1) {
                $NumberOfCommas = count($DeCSWordSplitByComma) - 1;
                $DeCSWordReOrderedWithoutComma = '';
                while ($NumberOfCommas >= 0) {
                    if (strlen($DeCSWordReOrderedWithoutComma) > 0) {
                        $DeCSWordReOrderedWithoutComma = $DeCSWordReOrderedWithoutComma . ' ';
                    }
                    $DeCSWordReOrderedWithoutComma = $DeCSWordReOrderedWithoutComma . $DeCSWordSplitByComma[$NumberOfCommas];
                    $NumberOfCommas--;
                }
                $DeCSWord = $DeCSWordReOrderedWithoutComma;
            }
            array_push($results, $DeCSWord);
        }
        return array_unique($results);
    }


}
