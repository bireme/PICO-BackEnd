<?php

namespace LayerEntities;

require_once('ObjectKeyword.php');

use LayerEntities\ObjectKeyword;

class ObjectKeywordList {

    private $ItemList;
    private $langArr;
    private $MaxRelatedTrees;
    private $MaxDescendantExplores;

    public function getMaxImports() {
        return array('MaxRelatedTrees' => $this->MaxRelatedTrees,
            'MaxDescendantExplores' => $this->MaxDescendantExplores);
    }

    public function __construct($langArr,$MaxImports) {
        $this->ItemList = array();
        $this->langArr = $langArr;
        $this->MaxRelatedTrees = $MaxImports['MaxRelatedTrees'];
        $this->MaxDescendantExplores = $MaxImports['MaxDescendantExplores'];
    }

    public function getConnectionTimeSum() {
        $res = 0;
        foreach ($this->ItemList as $item) {
            $res += $item->getConnectionTime();
        }
        return $res;
    }

    public function setLang($langs) {
        $this->langArr = $langs;
    }

    public function getLang() {
        return $this->langArr;
    }

    public function getKeywordList() {
        return $this->ItemList;
    }

    public function AddKeyword($item) {
        array_push($this->ItemList, $item);
    }

    public function KeyWordListInfo($SimpleLifeMessage) {
        $SimpleLifeMessage->AddAsNewLine('Summary of tree_ids,descendants and synonyms per keyword');
        foreach ($this->ItemList as $item) {
            $item->KeyWordInfo($SimpleLifeMessage);
        }
    }

    public function getKeywordsTitlesAndObjects() {
        $results = array();
        foreach ($this->ItemList as $item) {
            $results[$item->getKeyword()] = $item;
        }
        return $results;
    }

    public function getKeywordListResults() {
        $results = array();
        foreach ($this->ItemList as $KeywordObj) {
            $results[$KeywordObj->getKeyword()] = $KeywordObj->getFullDeCSListKeyword();
        }
        return $results;
    }

}

?>