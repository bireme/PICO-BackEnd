<?php

namespace PICOExplorer\Services\DeCSIntegration\Looper;

use DOMXPath;
use PICOExplorer\Exceptions\Exceptions\AppError\XMLImportantElementNotFound;
use DOMNode;
use PICOExplorer\Services\ServiceModels\ParallelServiceEntryPoint;

abstract class DeCSIntegrationLooperSupport extends ParallelServiceEntryPoint
{

    protected function XMLDeCSRelatedAndDescendants(DOMNode $decswsResponseElement, string $XMLText, DOMXPath $DOMXpath)
    {
        $TmpResults = $this->XMLTreeIdAndTerm($decswsResponseElement, $XMLText, $DOMXpath);
        $this->XMLDeCSArr($decswsResponseElement, $XMLText, $DOMXpath, $TmpResults);
        $this->XMLRelatedAndDescendants($decswsResponseElement, $XMLText, $DOMXpath, $TmpResults);
        return $TmpResults;
    }

    ///////////////////////////////////////////////////////////////////
    //INNER FUNCTIONS
    ///////////////////////////////////////////////////////////////////

    private function XMLDeCSArr(DOMNode $decswsResponseElement, string $XMLText, DOMXPath $DOMXpath, array &$TmpResults)
    {
        $DeCSArr = [];
        $DeCSArrObj = $DOMXpath->query('.//record_list[1]//synonym_list[1]//synonym', $decswsResponseElement);
        if (!($DeCSArrObj) || count($DeCSArrObj) === 0) {
            //WARNING
            //throw new XMLImportantElementNotFound(['Controller' => __METHOD__ . '@' . get_class($this), 'XMLText' => $XMLText, 'ElementNotFound' => 'numFound'], null);
        }
        foreach ($DeCSArrObj as $DeCSObj) {
            array_push($DeCSArr, $DeCSObj->nodeValue);
        }
        $TmpResults['decs'] = $DeCSArr;
    }

    private function XMLTreeIdAndTerm(DOMNode $decswsResponseElement, string $XMLText, DOMXPath $DOMXpath)
    {
        $tree_id = $DOMXpath->query('.//self[1]//term[1]/@tree_id', $decswsResponseElement)->item(0)->nodeValue;
        if (!($tree_id) || strlen($tree_id) === 0) {
            throw new XMLImportantElementNotFound(['Controller' => __METHOD__ . '@' . get_class($this), 'XMLText' => $XMLText, 'ElementNotFound' => 'numFound'], null);
        }
        $term = $DOMXpath->query('.//self[1]//term', $decswsResponseElement)->item(0)->textContent;
        if (!($term) || strlen($term) === 0) {
            throw new XMLImportantElementNotFound(['Controller' => __METHOD__ . '@' . get_class($this), 'XMLText' => $XMLText, 'ElementNotFound' => 'numFound'], null);
        }
        $TmpResults['term'] = $term;
        $TmpResults['tree_id'] = $tree_id;
        return $TmpResults;
    }

    private function XMLRelatedAndDescendants(DOMNode $decswsResponseElement, string $XMLText, DOMXPath $DOMXpath, array &$TmpResults)
    {
        $descendantTreeArr = [];
        $treesArr = [];
        $descendants = $DOMXpath->query('.//descendants[1]//term', $decswsResponseElement);
        $trees = $DOMXpath->query('.//record_list[1]//tree_id_list[1]//tree_id', $decswsResponseElement);
        foreach ($descendants as $obj) {
            $Desc_Tree_id = $DOMXpath->query('.//@tree_id', $obj)->item(0)->nodeValue;
            if (!($Desc_Tree_id) || strlen($Desc_Tree_id) === 0) {
                //WARNING
                //throw new XMLImportantElementNotFound(['Controller' => __METHOD__ . '@' . get_class($this), 'XMLText' => $XMLText, 'ElementNotFound' => 'numFound'], null);
            }
            array_push($descendantTreeArr, $Desc_Tree_id);
        }
        foreach ($trees as $obj) {
            array_push($treesArr, $obj->nodeValue);
        }
        $treesArr = array_unique($treesArr);
        $descendantTreeArr = array_unique($descendantTreeArr);
        $TmpResults['trees'] = $treesArr;
        $TmpResults['descendants'] = $descendantTreeArr;
    }

}
