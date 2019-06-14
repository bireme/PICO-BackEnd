<?php

namespace LayerEntities;

class ObjectQuerySplitList {

    private $ItemList;
    private $usedLangs;
    private $mainLanguage;
    private $HTMLDescriptors;
    private $HTMLDeCS;
    private $query;

    public function getUsedLangs() {
        return $this->usedLangs;
    }

    public function getMainLanguage() {
        return $this->mainLanguage;
    }

    public function getQuery() {
        return $this->query;
    }

    public function setQuery($query) {
        $this->query = $query;
    }

    public function __construct($query, $ObjectKeywordList, $usedLangs, $mainLanguage) {
        $this->usedLangs = $usedLangs;
        $this->mainLanguage = $mainLanguage;
        $this->ItemList = array();
        $this->ObjectKeywordList = $ObjectKeywordList;
        $this->query = $query;
    }

    public function AddToItemList($item) {
        array_push($this->ItemList, $item);
    }

    public function getObjectKeywordList() {
        return $this->ObjectKeywordList;
    }

    public function AddDescriptorsHTML($msg) {
        $this->HTMLDescriptors = $this->HTMLDescriptors . $msg;
    }

    public function AddDeCSHTML($msg) {
        $this->HTMLDeCS = $this->HTMLDeCS . $msg;
    }

    public function getItemList() {
        return $this->ItemList;
    }

    public function getResults() {
        return array(
            'HTMLDescriptors' => $this->HTMLDescriptors,
            'HTMLDeCS' => $this->HTMLDeCS,
            'QuerySplit' => json_encode($this->ItemList),
            'results' => json_encode($this->ObjectKeywordList->getKeywordListResults())
        );
    }

}

?>