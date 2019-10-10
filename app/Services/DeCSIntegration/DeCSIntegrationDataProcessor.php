<?php

namespace PICOExplorer\Services\DeCSIntegration;

use PICOExplorer\Facades\UltraLoggerFacade;
use PICOExplorer\Models\DataTransferObject;
use PICOExplorer\Services\AdvancedLogger\Services\UltraLoggerDevice;

abstract class DeCSIntegrationDataProcessor extends DTOManager
{

    protected function ProcessImportResults(array $resultsByLang, bool $IsMainTree, DataTransferObject $DTO, UltraLoggerDevice $Log)
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
        $cut=[];
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
