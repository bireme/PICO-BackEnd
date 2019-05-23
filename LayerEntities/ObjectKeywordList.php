<?php

namespace LayerEntities;

require_once('ObjectKeyword.php');

use LayerEntities\ObjectKeyword;

class ObjectKeywordList {

    private $ItemList;
    private $langArr;
    private $HTMLDescriptors;
    private $HTMLDeCS;
    private $query;

    public function getQuery() {
        return $this->query;
    }

    public function setQuery($query) {
        $this->query = $query;
    }

    public function __construct($langArr, $query) {
        $this->ItemList = array();
        $this->langArr = $langArr;
        $this->query = $query;
    }

    public function getConnectionTimeSum() {
        $res = 0;
        foreach ($this->ItemList as $item) {
            if ($item['type'] == 'key') {
                $res += $item['value']->getConnectionTime();
            }
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
        return array_keys($this->ItemList);
    }

    public function getKeywordList() {
        $Arr = array();
        foreach ($this->ItemList as $item) {
            if ($item['type'] == 'key') {
                array_push($Arr, $item);
            }
        }
        return $Arr;
    }

    public function AddKeyword($item) {
        array_push($this->ItemList, $item);
    }

    public function KeyWordListInfo($SimpleLifeMessage) {
        $SimpleLifeMessage->AddAsNewLine('Summary of tree_ids,descendants and synonyms per keyword');
        foreach ($this->ItemList as $item) {
            if ($item['type'] == 'key') {
                $item['value']->KeyWordInfo($SimpleLifeMessage);
            }
        }
    }

    public function AddDescriptorsHTML($msg) {
        $this->HTMLDescriptors = $this->HTMLDescriptors . $msg;
    }

    public function AddDeCSHTML($msg) {
        $this->HTMLDeCS = $this->HTMLDeCS . $msg;
    }

    public function getFullDeCSList() {
        $results = array();
        foreach ($this->ItemList as $item) {
            if ($item['type'] == 'key') {
                $results[$item['value']->getKeyword()] = $item['value']->getFullDeCSList();
            }
        }
        return $results;
    }

    public function getOutputResults() {
        $results = array();
        foreach ($this->ItemList as $item) {
            if ($item['type'] == 'key') {
                $objKeyword= array('keyword' =>$item['value']->getKeyword(), 'value' => $item['value']->getFullDeCSList());
                $obj=$objKeyword;
            }else{
                $obj=$item['value'];
            }
            $valx = array('type' => $item['type'], 'value' => $obj);
            array_push($results,$valx);
        }
        return json_encode($results);
    }

    public function getResults() {
        return array(
            'HTMLDescriptors' => $this->HTMLDescriptors,
            'HTMLDeCS' => $this->HTMLDeCS,
            'results' => $this->getOutputResults()
        );
    }

}

?>