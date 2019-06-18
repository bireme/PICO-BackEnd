<?php

namespace LayerIntegration;

use \DOMDocument;

class ControllerExtractXML {

    private $XMLResults;
    private $DOM;
    private $OperationTitle;

    ///////////////////////////////////////////////////////////////////
    //PUBLIC FUNCTIONS
    ///////////////////////////////////////////////////////////////////

    public function setOperationTitle($OperationTitle) {
        $this->OperationTitle = $OperationTitle;
    }

    public function DeCSImporterXMLExtract($XML, $lang, $ExploredTrees,$results) {
        return $this->LoadXML($XML) ||
                $this->DeCSImporterXMLExtractInner($lang, $ExploredTrees,$results);
    }

    public function ResultsImporterXMLExtract($XML) {
        return $this->LoadXML($XML) ||
                $this->ResultsImporterXMLExtractInner();
    }

    public function getResults() {
        return $this->XMLResults;
    }

    ///////////////////////////////////////////////////////////////////
    //INNER FUNCTIONS
    ///////////////////////////////////////////////////////////////////

    private function LoadXML($XML) {
        $this->DOM = new DOMDocument();
        $this->DOM->loadxml($XML);
    }

    private function SaveDeCSResults($treeid, $lang, $term, $DeCSArr, $treesArr, $descendantTreeArr) {
        $decswsResponseElementTmpResults = array();
        $decswsResponseElementTmpResults['DeCSArr'] = $DeCSArr;
        $decswsResponseElementTmpResults['term'] = $term;
        if(!(array_key_exists($treeid,$this->XMLResults))){
            $this->XMLResults[$treeid] = array();
            $this->XMLResults[$treeid]['content'] = array();
            $this->XMLResults[$treeid]['descendants'] = array();
        }
        $this->XMLResults[$treeid]['content'][$lang] = $decswsResponseElementTmpResults;
        $this->XMLResults[$treeid]['descendants'] = array_unique(array_merge($this->XMLResults[$treeid]['descendants'],$descendantTreeArr));
        $this->XMLResults[$treeid]['relatedtrees'] = array_unique(array_merge($this->XMLResults[$treeid]['descendants'],$treesArr));
    }

    private function SaveResultNumberResults($numFound) {
        $this->XMLResults = array(
            'numFound' => $numFound,
        );
    }

    private function ResultsImporterXMLExtractInner() {
        $numFound = -1;
        $this->getNumFound($numFound);
        $this->SaveResultNumberResults($numFound);
    }

    private function DeCSImporterXMLExtractInner($lang, $ExploredTrees,$results) {
        $decswsResults = $this->DOM->getElementsByTagName('decsws_response');
        $this->XMLResults=$results;
        foreach ($decswsResults as $decswsResponseElement) {
            if ($fun = $this->ImportDeCSRelatedAndDescendants($decswsResponseElement, $lang,  $ExploredTrees)) {
                return $fun;
            }
        }
    }

    private function ImportDeCSRelatedAndDescendants($decswsResponseElement, $lang, $ExploredTrees) {
        $DeCSArr = array();
        $treesArr = array();
        $descendantTreeArr = array();
        $tree_id = NULL;
        $term = NULL;
        if ($fun = $this->getTreeIdAndTerm($tree_id, $term, $decswsResponseElement)) {
            return $fun;
        }
        if (in_array($tree_id, $ExploredTrees)) {
            return;
        }

        return ($this->getDeCSArr($DeCSArr, $decswsResponseElement)) ?:
                ($this->getRelatedAndDescendants($treesArr, $descendantTreeArr, $decswsResponseElement)) ?:
                ($this->SaveDeCSResults($tree_id, $lang, $term, $DeCSArr, $treesArr, $descendantTreeArr));
    }

//------------------------------------------------------------
//------------------------------------------------------------
//-THIS SECTION CONTAINS XML EXPLORATION---------------------
//------------------------------------------------------------
//------------------------------------------------------------

    private function getDeCSArr(&$DeCSArr, $decswsResponseElement) {
        $DeCSArrObj = $decswsResponseElement->getElementsByTagName('record_list')->item(0)->getElementsByTagName('synonym_list')->item(0)->getElementsByTagName('synonym');
        foreach ($DeCSArrObj as $DeCSObj) {
            array_push($DeCSArr, $DeCSObj->nodeValue);
        }
    }

    private function getTreeIdAndTerm(&$tree_id, &$term, $decswsResponseElement) {
        $DeCSTitle = $decswsResponseElement->getElementsByTagName('self')->item(0)->getElementsByTagName('term')->item(0);
        $tree_id = $DeCSTitle->getAttribute('tree_id');
        $term = $DeCSTitle->textContent;
    }

    private function getRelatedAndDescendants(&$treesArr, &$descendantTreeArr, $decswsResponseElement) {
        $descendants = $decswsResponseElement->getElementsByTagName('descendants')->item(0)->getElementsByTagName('term');
        foreach ($descendants as $obj) {
            array_push($descendantTreeArr, $obj->getAttribute('tree_id'));
        }

        $trees = $decswsResponseElement->getElementsByTagName('record_list')->item(0)->getElementsByTagName('tree_id_list')->item(0);
        $trees = $trees->getElementsByTagName('tree_id');
        foreach ($trees as $obj) {
            array_push($treesArr, $obj->nodeValue);
        }
        $treesArr = array_unique($treesArr);
        $descendantTreeArr = array_unique($descendantTreeArr);
    }

    private function getNumFound(&$numFound) {
        $result = $this->DOM->getElementsByTagName('result');
        if ($result->length == 0) {
            throw new SimpleLifeException(new \SimpleLife\NoResultsInXMLException($ResultQuery->getQuery()));
        }
        $numFound = $result->item(0)->getAttribute('numFound');
    }

}

?>