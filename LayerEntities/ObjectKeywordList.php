<?php

namespace LayerEntities;

require_once('ObjectKeyword.php');

use LayerEntities\ObjectKeyword;

class ObjectKeywordList {

    private $ItemList;
    private $langArr;
    private $mainLanguage;

    public function __construct($langArr, $mainLanguage) {
        $this->ItemList = array();
        $this->langArr = $langArr;
        $this->mainLanguage = $mainLanguage;
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

    public function ExistsKeyword($keyword) {
        if (array_key_exists($keyword, $this->ItemList)) {
            return true;
        } else {
            return false;
        }
    }

    public function CreateKeywordObject($keyword) {
        if ($this->ExistsKeyword($keyword)) {
            $keywordObj = $this->ItemList[$keyword];
        } else {
            $keywordObj = new ObjectKeyword($keyword, $this->langArr, $this->mainLanguage);
            $this->ItemList[$keyword] = $keywordObj;
        }
        return $keywordObj;
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