<?php

namespace LayerEntities;

require_once('ObjectKeyword.php');

use LayerEntities\ObjectKeyword;

class ObjectKeywordList {

    private $keywordList;
    private $langArr;

    public function __construct($langArr) {
        $this->keywordList = array();
        $this->langArr = $langArr;
    }

    public function getConnectionTimeSum() {
        $res = 0;
        foreach ($this->keywordList as $Keywordobj) {
            $res += $Keywordobj->getConnectionTime();
        }
        return $res;
    }

    public function setLang($langs) {
        $this->langArr = $langs;
    }

    public function getLang() {
        return $this->langArr;
    }

    public function getKeywords() {
        return array_keys($this->keywordList);
    }

    public function getKeywordList() {
        return $this->keywordList;
    }
    
    public function AddSelectedArray($KeywordArray) {
        foreach ($KeywordArray as $Keyword) {
            $obj = new ObjectKeyword($Keyword, $this->langArr);
            $this->keywordList[$Keyword] = $obj;
        }
    }

    public function AddKeywords($KeywordArray) {
        foreach ($KeywordArray as $Keyword) {
            $obj = new ObjectKeyword($Keyword, $this->langArr);
            $this->keywordList[$Keyword] = $obj;
        }
    }

    public function getFullDeCSList() {
        $results = array();
        foreach ($this->keywordList as $key => $ObjectKeyword) {
            $results[$ObjectKeyword->getKeyword()] = $ObjectKeyword->getFullDeCSList();
        }
        return $results;
    }

    public function getSelectedDeCSList() {
        $results = array();
        foreach ($this->keywordList as $ObjectKeyword) {
            $results[$ObjectKeyword->getKeyword()] = $ObjectKeyword->getSelectedDeCSList();
        }
        return $results;
    }

    public function KeyWordListInfo($SimpleLifeMessage) {
        $SimpleLifeMessage->AddAsNewLine('Summary of tree_ids,descendants and synonyms per keyword');
        foreach ($this->keywordList as $keywordObj) {
            $keywordObj->KeyWordInfo($SimpleLifeMessage);
        }
    }

}

?>