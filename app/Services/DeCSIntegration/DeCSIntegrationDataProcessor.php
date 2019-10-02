<?php

namespace PICOExplorer\Services\DeCSIntegration;

use PICOExplorer\Facades\UltraLoggerFacade;
use PICOExplorer\Models\DataTransferObject;
use PICOExplorer\Services\AdvancedLogger\Services\UltraLoggerDevice;
use Symfony\Component\CssSelector\Exception\InternalErrorException;

abstract class DeCSIntegrationDataProcessor extends DTOManager
{

    protected function ProcessImportResults(array $resultsByLang, bool $IsMainTree, DataTransferObject $DTO, string $queryTitle, UltraLoggerDevice $Log)
    {
        $TreeList = [];
        foreach ($resultsByLang as $lang => $TreeListObj) {
            if (!($TreeListObj)) {
                continue;
            }
            foreach ($TreeListObj as $index => $TreeData) {
                $tree_id = $TreeData['tree_id'] ?? null;
                if ($tree_id) {
                    if (($TreeList[$tree_id] ?? null) === null) {
                        $TreeList[$tree_id] = [];
                    }
                    $TreeList[$tree_id][$lang] = $TreeData;
                }
            }
        }
        if ($IsMainTree) {
            if (count($TreeList) === 0) {
                UltraLoggerFacade::WarningToUltraLogger($Log, 'There were no trees found to be processed');
                return null;
            } else {
                UltraLoggerFacade::InfoToUltraLogger($Log, count($TreeList) . ' trees found to be processed');

                $info = $this->addToMainTreeList(array_keys($TreeList), $DTO);
                $added = $info['added'];
                $cut = $info['cut'];
                if (count($added)) {
                    UltraLoggerFacade::InfoToUltraLogger($Log, 'The following keys were added to Primary Main Trees ' . json_encode($info['added']));
                }
                if (count($cut)) {
                    UltraLoggerFacade::WarningToUltraLogger($Log, count($info['cut']) . ' trees excluded from Primary Main List');
                }
            }
        }

        $CurrentResults = $this->getResultsOrderedByTreeId($DTO);
        $OfficialLangs = $this->getLangs($DTO);

        foreach ($TreeList as $tree_id => $newTreeObject) {
            if ($newTreeObject === null) {
                continue;
            }
            if (!(array_key_exists($tree_id, $CurrentResults))) {
                $CurrentResults[$tree_id] = [];
                $CurrentResults[$tree_id]['descendants'] = [];
                $CurrentResults[$tree_id]['remaininglangs'] = $OfficialLangs;
            }
            foreach ($newTreeObject as $lang => $newTreeLangData) {
                if ($newTreeLangData === null) {
                    continue;
                }
                if (!(array_key_exists($lang, $CurrentResults[$tree_id]))) {
                    $CurrentResults[$tree_id][$lang] = [];
                }
                if (!(array_key_exists($lang, $CurrentResults[$tree_id]))) {
                    $CurrentResults[$tree_id][$lang] = [];
                }
                $decs = $this->RemoveCommasFromDeCS(array_merge([$newTreeLangData['term']], $newTreeLangData['decs']));
                $term = $decs[0];
                $CurrentResults[$tree_id]['remaininglangs'] = array_diff($CurrentResults[$tree_id]['remaininglangs'], [$lang]);
                $CurrentResults[$tree_id][$lang] = [
                    'term' => $term,
                    'decs' => $decs,
                ];
                UltraLoggerFacade::InfoToUltraLogger($Log, '--------------');
                $treeinfo = $tree_id . '[' . $lang . '] => Term:' . $term . '. DeCS=' . count($decs);
                UltraLoggerFacade::InfoToUltraLogger($Log, $treeinfo);
                $oridescendants = $newTreeLangData['descendants'];
                $NewDescendants = $this->HandleTreeDescendantsSaving($DTO, $Log, $IsMainTree, $tree_id, $oridescendants);
                $treeinfo = count($NewDescendants) . ' added descendants(original=' . count($oridescendants) . ') => ' . json_encode($NewDescendants);
                UltraLoggerFacade::InfoToUltraLogger($Log, $treeinfo);
                $CurrentResults[$tree_id]['descendants'] = array_merge($CurrentResults[$tree_id]['descendants'], $NewDescendants);
            }
        }

        $this->saveResultsOrderedByTreeId($DTO, $CurrentResults);

        //$LogData = UltraLoggerFacade::UltraLoggerAttempt($Log, 'Managing Trees recently obtained');
        //$this->TreeManager($IsMainTree, array_unique($descendants),array_unique($currentTrees),  $DTO, $Log);
        //UltraLoggerFacade::UltraLoggerSuccessfulAttempt($Log, $LogData);
    }

    protected function MixDescendantTreesIntoMainTree(DataTransferObject $DTO, UltraLoggerDevice $Log)
    {
        $MainTrees = $this->getMainTreeList($DTO);
        $TmpResults = $this->getResultsOrderedByTreeId($DTO);
        $ValidLangs = $this->getLangs($DTO);
        $FinalResults = [];
        foreach ($MainTrees as $MainTree) {
            UltraLoggerFacade::InfoToUltraLogger($Log, '--------------');
            UltraLoggerFacade::InfoToUltraLogger($Log, '[Maintree ' . $MainTree . '] Merging DeCS and Terms of descendants');
            try {
                $res = $this->MergeDescendantsOfMainTree($MainTree, $TmpResults, $ValidLangs, $Log);
                if ($res !== null) {
                    $FinalResults[$MainTree] = $res;
                }
            } catch (\Throwable $ex) {
                UltraLoggerFacade::ErrorToUltraLogger($Log, $ex->getMessage() . '@' . $ex->getLine() . '@' . $ex->getFile());
            }
        }
        $this->saveFinalResults($DTO, $FinalResults);
    }

    private function MergeDescendantsOfMainTree(string $MainTree, array $TmpResults, array $ValidLangs, UltraLoggerDevice $Log)
    {
        $MainTreeData = $TmpResults[$MainTree] ?? null;
        if ($MainTreeData === null) {
            UltraLoggerFacade::ErrorToUltraLogger($Log, 'MainTree ' . $MainTree . ' not found in explored data');
            return null;
        }
        $globaldecs = [];
        $this->MergeDescendantsOTree($MainTree, $MainTreeData, $TmpResults, $ValidLangs, $globaldecs, $Log);
        if (count($globaldecs) === 0) {
            UltraLoggerFacade::ErrorToUltraLogger($Log, $MainTree . ': Error Retrieving globaldecs. Was empty');
            return null;
        }
        $Error = false;
        foreach ($globaldecs as $lang => $langdata) {
            if (count($langdata) === 0) {
                UltraLoggerFacade::ErrorToUltraLogger($Log, $MainTree . ': Error Retrieving globaldecs langdata. Lang=' . $lang);
                $Error = true;
                continue;
            }
            $globaldecs[$lang]['decs'] = array_unique($langdata['decs']);
            $globaldecs[$lang]['term'] = array_shift($globaldecs[$lang]['decs']);
        }
        if ($Error) {
            return null;
        }
        return $globaldecs;
    }

    /////////////////////////////////////////////////////////
    /////////////////////////////////////////////////////////
    /// INNER FUNCTIONS
    /////////////////////////////////////////////////////////
    /////////////////////////////////////////////////////////

    private function MergeDescendantsOTree(string $parent_tree_id, array $TreeData, array $TmpResults, array $ValidLangs, array &$globaldecs, UltraLoggerDevice $Log)
    {
        $descendants = $TreeData['descendants'] ?? null;
        $remaininglangs = $TreeData['remaininglangs'] ?? null;
        if ($descendants === null) {
            UltraLoggerFacade::ErrorToUltraLogger($Log, 'Descendants not found in ' . $parent_tree_id);
            return;
        }
        if ($remaininglangs === null) {
            UltraLoggerFacade::ErrorToUltraLogger($Log, 'remaininglangs not found in ' . $parent_tree_id);
            return;
        }
        if (count($remaininglangs) > 0) {
            UltraLoggerFacade::ErrorToUltraLogger($Log, $parent_tree_id . 'HAS UNEXPLORED LANGS: ' . json_encode($remaininglangs));
            return;
        }
        foreach ($TreeData as $lang => $langData) {
            if (!(in_array($lang, $ValidLangs))) {
                continue;
            }
            if (!($globaldecs[$lang] ?? null)) {
                $globaldecs[$lang] = [
                    'term' => 'Unset',
                    'decs' => [],
                ];
            }
            $decs = $langData['decs'] ?? null;
            $term = $langData['term'] ?? null;
            if ($decs === null) {
                UltraLoggerFacade::ErrorToUltraLogger($Log, '$decs not found in ' . $parent_tree_id);
                return;
            }
            if ($term === null) {
                UltraLoggerFacade::ErrorToUltraLogger($Log, '$term not found in ' . $parent_tree_id);
                return;
            }
            $local_decs = array_merge([$term], $decs);
            $added = array_diff($local_decs, $globaldecs[$lang]['decs']);
            UltraLoggerFacade::InfoToUltraLogger($Log, 'Explored ' . $parent_tree_id . ': Added ' . count($added) . ' decs [' . count($local_decs) . ' total]');
            $globaldecs[$lang]['decs'] = array_merge($globaldecs[$lang]['decs'], $local_decs);
        }


        if (count($descendants)) {
            foreach ($descendants as $descendant) {
                $InnerTreeData = $TmpResults[$descendant] ?? null;
                if (!($InnerTreeData)) {
                    UltraLoggerFacade::WarningToUltraLogger($Log, 'Tree ' . $descendant . ' not found in explored data. Skipping');
                    continue;
                }
                $this->MergeDescendantsOTree($descendant, $InnerTreeData, $TmpResults, $ValidLangs, $globaldecs, $Log);

            }
        }
    }

    private function HandleTreeDescendantsSaving(DataTransferObject $DTO, UltraLoggerDevice $Log, bool $IsMainTree, string $tree_id, array $descendants)
    {
        if (count($descendants) === 0) {
            return [];
        }
        $depthlevel = null;
        $depthAuth = true;
        if (!($IsMainTree)) {
            $depthAuth = null;
            $depthlevel = 0;
            try {
                $info = $this->IsTreeDepthOk($tree_id, $DTO);
                $maxdepth = $info['max'];
                $depthlevel = $info['current'];
                $MainTree = $info['tree_id'] ?? 'ErrorCalculating';
                $subtitle = ' depth =' . $depthlevel . ' (Max ' . $maxdepth . ')';
                if ($depthlevel === null) {
                    UltraLoggerFacade::ErrorToUltraLogger($Log, 'Couldnt calculate currentdepth===999');
                    $depthAuth = true;
                }
                if (($depthlevel + 1) <= $maxdepth) {
                    UltraLoggerFacade::InfoToUltraLogger($Log, '[Granted]' . $subtitle . ' belongs to MainTree: ' . $MainTree);
                    $depthAuth = true;
                } else {
                    UltraLoggerFacade::WarningToUltraLogger($Log, '[Denied]' . $subtitle);
                    $depthAuth = false;
                }
            } catch (\Throwable $ex) {
                UltraLoggerFacade::ErrorToUltraLogger($Log, 'ERROR PROCESSING TREEDEPTH: ' . $ex->getMessage() . '@' . $ex->getLine() . '@' . $ex->getFile());
            }
            if ($depthAuth === null) {
                UltraLoggerFacade::ErrorToUltraLogger($Log, 'Error Obtaining depthAuth');
                return [];
            }
        }
        if ($depthAuth) {
            $ProcessedDescendants = $this->ToTreeData($DTO, $Log, $descendants, $depthlevel + 1, $tree_id, $IsMainTree);
            return $ProcessedDescendants;
        } else {
            return [];
        }
    }

    private function ToTreeData(DataTransferObject $DTO, UltraLoggerDevice $Log, array $descendants, int $depthlevel, string $tree_id, bool $isMain)
    {
        $info = $this->addDescendantsToTree($descendants, $tree_id, $depthlevel, $isMain, $DTO);
        $countAdd = count($info['added']);
        $countCut = count($info['cut']);
        if ($countAdd > 0) {
            if ($countCut > 0) {
                UltraLoggerFacade::WarningToUltraLogger($Log, 'Full descendants. Excluded' . count($info['cut']) . ' trees');
            }
        } else {
            if ($countCut > 0) {
                UltraLoggerFacade::WarningToUltraLogger($Log, 'Full descendants. Excluded' . count($info['cut']) . ' trees');
            } else {
                UltraLoggerFacade::InfoToUltraLogger($Log, 'No new descendants to add');
            }
        }
        return $info['added'];
    }

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
                        UltraLoggerFacade::WarningToUltraLogger($Log, 'MainList Full. Couldnt add descendants: ' . count($info['cut']) . ' items');
                    }
                } else {
                    if ($countCut > 0) {
                        UltraLoggerFacade::WarningToUltraLogger($Log, 'MainList Full. Couldnt add any descendant: ' . count($info['cut']) . ' items');
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
                        UltraLoggerFacade::WarningToUltraLogger($Log, 'MainList Full. Couldnt add related trees: ' . count($info['cut']) . ' items');
                    }
                } else {
                    if ($countCut > 0) {
                        UltraLoggerFacade::WarningToUltraLogger($Log, 'MainList Full. Couldnt add any related tree: ' . count($info['cut']) . ' items');
                    } else {
                        UltraLoggerFacade::InfoToUltraLogger($Log, 'There were no new trees to add to MainList');
                    }
                }
            }
        }
    }

    ////////////////////////////////
    /// NOT LOGGED
    /// ///////////////////////////////////


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
