<?php

namespace LayerEntities;

class ObjectKeyword {

    private $keyword;
    private $langArr;
    private $InMainTree;
    private $Completed;
    private $basedata;
    private $ConnectionTime;

    public function __construct($keyword, $langArr, $mainlanguage) {
        $this->keyword = $keyword;
        $this->mainlanguage = $mainlanguage;
        $this->langArr = $langArr;
        $this->Completed = false;
    }

    public function getConnectionTime() {
        return $this->ConnectionTime;
    }

    public function setBaseData($results) {
        $this->basedata = $results;
    }

    public function SetAsCompleted() {
        $this->Completed = true;
    }

    public function IsCompleted() {
        return $this->Completed;
    }

    public function getMainLanguage() {
        return $this->mainlanguage;
    }

    public function getLang() {
        return $this->langArr;
    }

    public function getKeyword() {
        return $this->keyword;
    }

    public function getFullDeCSListKeyword() {
        return array(
            'content' => $this->basedata,
            'lang' => $this->langArr,
        );
    }

}

?>