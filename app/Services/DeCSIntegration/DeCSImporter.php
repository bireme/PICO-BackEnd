<?php

namespace PICOExplorer\Services\DeCSIntegration;

use DOMXPath;
use PICOExplorer\Exceptions\Exceptions\AppError\BadRequestInXML;
use PICOExplorer\Exceptions\Exceptions\AppError\DOMObjectNotFound;
use PICOExplorer\Exceptions\Exceptions\AppError\XMLImportantElementNotFound;
use PICOExplorer\Services\ServiceModels\PICOServiceExternalImporter;
use DOMNode;

abstract class DeCSImporter extends DeCSIntegrationProcessor implements PICOServiceExternalImporter
{

    public function ImportDeCS(array $data)
    {
        $url = 'http://decs.bvsalud.org/cgi-bin/mx/cgi=@vmx/decs/';
        return $this->ImportData('POST', $data, $url, null, null, false, false);
    }

    public function getXMLResults(DOMXPath $DOMXpath, string $XMLText)
    {
        $statusNode = $DOMXpath->query("//decsvmx");
        if (!($statusNode) || (!count($statusNode))) {
            throw new DOMObjectNotFound(['Controller' => __METHOD__ . '@' . get_class($this), 'XMLText' => $XMLText]);
        }
        $decswsResults = $DOMXpath->query("//decsws_response");
        if (!($decswsResults) || count($decswsResults)===0) {
            //throw new BadRequestInXML(['Controller' => __METHOD__ . '@' . get_class($this), 'XMLText' => $XMLText]);
        }
        $Res = [];
        foreach($decswsResults as $key => $ResultElement){
            $Res[$key] = $this->ImportDeCSRelatedAndDescendants($ResultElement, $XMLText, $DOMXpath);
        }
        return $Res;
    }

    ///////////////////////////////////////////////////////////////////
    //INNER FUNCTIONS
    ///////////////////////////////////////////////////////////////////

    private function ImportDeCSRelatedAndDescendants(DOMNode $decswsResponseElement, string $XMLText, DOMXPath $DOMXpath)
    {
        $TmpResults = $this->getTreeIdAndTerm($decswsResponseElement, $XMLText, $DOMXpath);
        $this->getDeCSArr($decswsResponseElement, $XMLText, $DOMXpath, $TmpResults);
        $this->getRelatedAndDescendants($decswsResponseElement, $XMLText, $DOMXpath, $TmpResults);
        return $TmpResults;
    }

    private function getDeCSArr(DOMNode $decswsResponseElement, string $XMLText, DOMXPath $DOMXpath, array &$TmpResults)
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

    private function getTreeIdAndTerm(DOMNode $decswsResponseElement, string $XMLText, DOMXPath $DOMXpath)
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

    private function getRelatedAndDescendants(DOMNode $decswsResponseElement, string $XMLText, DOMXPath $DOMXpath, array &$TmpResults)
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
