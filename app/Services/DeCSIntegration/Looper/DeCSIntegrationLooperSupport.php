<?php

namespace PICOExplorer\Services\DeCSIntegration\Looper;

use DOMXPath;
use DOMNode;
use PICOExplorer\Facades\UltraLoggerFacade;
use PICOExplorer\Services\AdvancedLogger\Services\UltraLoggerDevice;
use PICOExplorer\Services\ServiceModels\ParallelServiceEntryPoint;

abstract class DeCSIntegrationLooperSupport extends ParallelServiceEntryPoint
{
    protected function XMLDeCSRelatedAndDescendants(DOMNode $decswsResponseElement, string $XMLText, DOMXPath $DOMXpath, UltraLoggerDevice &$Log)
    {
        $TmpResults = $this->XMLTreeIdAndTerm($decswsResponseElement, $XMLText, $DOMXpath, $Log);
        if ($TmpResults === -1) {
            return -1;
        }
        $this->XMLDeCSArr($decswsResponseElement, $XMLText, $DOMXpath, $TmpResults, $Log);
        if ($TmpResults === -1) {
            return -1;
        }
        $this->XMLRelatedAndDescendants($decswsResponseElement, $XMLText, $DOMXpath, $TmpResults, $Log);
        return $TmpResults;
    }


    ///////////////////////////////////////////////////////////////////
    //INNER FUNCTIONS
    ///////////////////////////////////////////////////////////////////

    private function XMLTreeIdAndTerm(DOMNode $decswsResponseElement, string $XMLText, DOMXPath $DOMXpath, UltraLoggerDevice &$Log)
    {
        $tree_id = $DOMXpath->query('.//self[1]//term[1]/@tree_id', $decswsResponseElement)->item(0)->nodeValue;
        if (!($tree_id) || strlen($tree_id) === 0) {
            UltraLoggerFacade::ErrorToUltraLogger($Log, 'Tree id not found in XML');
            return -1;
        }
        $term = $DOMXpath->query('.//self[1]//term', $decswsResponseElement)->item(0)->textContent;
        if (!($term) || strlen($term) === 0) {
            UltraLoggerFacade::ErrorToUltraLogger($Log, 'Term not found in XML');
            return -1;
        }
        $TmpResults['term'] = $term;
        $TmpResults['tree_id'] = $tree_id;
        return $TmpResults;
    }


    private function XMLDeCSArr(DOMNode $decswsResponseElement, string $XMLText, DOMXPath $DOMXpath, &$TmpResults, UltraLoggerDevice &$Log)
    {
        $DeCSArr = [];
        $DeCSArrObj = $DOMXpath->query('.//record_list[1]//synonym_list[1]//synonym', $decswsResponseElement);
        if (count($DeCSArrObj) === 0) {
            UltraLoggerFacade::InfoToUltraLogger($Log, 'Contained Zero DeCS');
        }else {
            foreach ($DeCSArrObj as $DeCSObj) {
                $res = null;
                try {
                    $res = $DeCSObj->nodeValue;
                    array_push($DeCSArr, $res);
                } catch (\Throwable $ex) {
                    UltraLoggerFacade::ErrorToUltraLogger($Log, 'DeCS nodeValue not found in :' . json_encode($DeCSObj));
                    return -1;
                }
            }
        }
        $TmpResults['decs'] = $DeCSArr;
    }


    private function XMLRelatedAndDescendants(DOMNode $decswsResponseElement, string $XMLText, DOMXPath $DOMXpath, &$TmpResults, UltraLoggerDevice &$Log)
    {
        $descendantTreeArr = [];
        $treesArr = [];
        $descendants = $DOMXpath->query('.//descendants[1]//term', $decswsResponseElement);
        if (count($descendants) === 0) {
            UltraLoggerFacade::InfoToUltraLogger($Log, 'Contained Zero Descendants');
        }else{
            foreach ($descendants as $obj) {
                $Desc_Tree_id = $DOMXpath->query('.//@tree_id', $obj)->item(0)->nodeValue;
                if (!($Desc_Tree_id) || strlen($Desc_Tree_id) === 0) {
                    UltraLoggerFacade::ErrorToUltraLogger($Log, 'Descendants Container does not exist');
                    return -1;
                }
                array_push($descendantTreeArr, $Desc_Tree_id);
            }
            $descendantTreeArr = array_unique($descendantTreeArr);
        }

        $trees = $DOMXpath->query('.//record_list[1]//tree_id_list[1]//tree_id', $decswsResponseElement);
        if (count($trees) === 0) {
            UltraLoggerFacade::InfoToUltraLogger($Log, 'Contained Zero Trees');
        }else{
            foreach ($trees as $obj) {
                $res = null;
                try {
                    $res = $obj->nodeValue;
                } catch (\Throwable $ex) {
                    UltraLoggerFacade::ErrorToUltraLogger($Log, 'Descendants nodeValue not found in :' . json_encode($obj));
                    return -1;
                }
                array_push($treesArr, $res);
            }
            $treesArr = array_unique($treesArr);
        }

        $TmpResults['trees'] = $treesArr;
        $TmpResults['descendants'] = $descendantTreeArr;
    }

}
