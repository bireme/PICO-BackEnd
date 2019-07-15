<?php

namespace PICOExplorer\Services\DeCSIntegration;

use PICOExplorer\Exceptions\InternalErrors\ErrorInDeCSLoopXMLExtraction;
use PICOExplorer\Exceptions\InternalErrors\ErrorInExploredTrees;
use PICOExplorer\Exceptions\InternalErrors\ErrorInTreesOrDescendantsFormat;
use PICOExplorer\Exceptions\InternalWarnings\TreesOrDescendantsNotFound;
use PICOExplorer\Services\ServiceModels\ExtractFromXMLTrait;

class ExtractDeCSFromXML extends ProxyReceiveDeCS
{

    use ExtractFromXMLTrait;

    protected $XMLResultsRules=[
        'numFound' => 'required|string|min:1',
    ];

    protected function ExploreXML($arguments)
    {
        $rules = [
            'ExploredTrees'=>'required|array|,min:0',
            'ExploredTrees.*'=>'string|,min:1',
        ];
        $ex = $this->ValidateData($arguments,$rules);
        if($ex){
            throw new ErrorInExploredTrees(null,$ex);
        }
        $ExploredTrees=$arguments['ExploredTrees'];
        $results = array();
        $decswsResults = $this->DOM->getElementsByTagName('decsws_response');
        foreach ($decswsResults as $decswsResponseElement) {
            $tmp = $this->ImportDeCSRelatedAndDescendants($decswsResponseElement, $ExploredTrees);
            $this->ValidateLoopResults($tmp);
            $results[$tmp['tree_id']] = $tmp;
        }
        return $results;
    }

//-----------------------------------------------------------
//-THIS SECTION CONTAINS DATA VALIDATION---------------------
//-----------------------------------------------------------
    private function ValidateLoopResults($results)
    {
        //FATAL ERRORS
        $rules = [
            'numFound'=>'required|string|min:1',
            'tree_id' => 'required|string|min:1',
            'term' => 'required|string|min:1',
            'DeCSArr' => 'required|array|min:0',
            'DeCSArr.*' => 'array|required|min:1', //langs
            'DeCSArr.*.*' => 'nullable|string|min:1',
        ];
        $ex=$this->ValidateData($results,$rules);
        if($ex){
            throw new ErrorInDeCSLoopXMLExtraction(null,$ex);
        }
        //WARNINGS
        $rules = [
            'treesArr' => 'required|array|min:1',
            'descendantTreeArr' => 'required|array|min:1',
        ];
        $ex=$this->ValidateData($results,$rules);
        if($ex){
            throw new TreesOrDescendantsNotFound(null,$ex);
        }

        //WARNINGS
        $rules = [
            'treesArr.*' => 'required|string|min:1',
            'descendantTreeArr.*' => 'required|string|min:1',
        ];
        $ex=$this->ValidateData($results,$rules);
        if($ex){
            throw new ErrorInTreesOrDescendantsFormat(null,$ex);
        }

    }

//-----------------------------------------------------------
//-THIS SECTION CONTAINS XML EXPLORATION---------------------
//-----------------------------------------------------------

    private function ImportDeCSRelatedAndDescendants($decswsResponseElement, $ExploredTrees)
    {
        $tmp = $this->getTreeIdAndTerm($decswsResponseElement);
        $tree_id = $tmp['tree_id'];
        $term = $tmp['term'];
        if (in_array($tree_id, $ExploredTrees)) {
            return -1;
        }
        $DeCSArr = $this->getDeCSArr($decswsResponseElement)['DeCSArr'];
        $tmp = $this->getRelatedAndDescendants($decswsResponseElement);
        $treesArr = $tmp['treesArr'];
        $descendantTreeArr = $tmp['descendantTreeArr'];
        return [
            'tree_id' => $tree_id,
            'term' => $term,
            'DeCSArr' => $DeCSArr,
            'treesArr' => $treesArr,
            'descendantTreeArr' => $descendantTreeArr,
        ];
    }

    private function getDeCSArr($decswsResponseElement)
    {
        $DeCSArr = array();
        $DeCSArrObj = $decswsResponseElement->getElementsByTagName('record_list')->item(0)->getElementsByTagName('synonym_list')->item(0)->getElementsByTagName('synonym');
        foreach ($DeCSArrObj as $DeCSObj) {
            array_push($DeCSArr, $DeCSObj->nodeValue);
        }
        return ['DeCSArr' => $DeCSArr];
    }

    private function getTreeIdAndTerm($decswsResponseElement)
    {
        $DeCSTitle = $decswsResponseElement->getElementsByTagName('self')->item(0)->getElementsByTagName('term')->item(0);
        $tree_id = $DeCSTitle->getAttribute('tree_id');
        $term = $DeCSTitle->textContent;
        return ['tree_id' => $tree_id, 'term' => $term];
    }

    private function getRelatedAndDescendants($decswsResponseElement)
    {
        $treesArr = array();
        $descendantTreeArr = array();
        $descendants = $decswsResponseElement->getElementsByTagName('descendants')->item(0)->getElementsByTagName('term');
        foreach ($descendants as $obj) {
            array_push($descendantTreeArr, $obj->getAttribute('tree_id'));
        }
        $trees = $decswsResponseElement->getElementsByTagName('record_list')->item(0)->getElementsByTagName('tree_id_list')->item(0)->getElementsByTagName('tree_id');
        foreach ($trees as $obj) {
            array_push($treesArr, $obj->nodeValue);
        }
        return ['treesArr' => $treesArr, 'descendantTreeArr' => $descendantTreeArr];
    }

}
