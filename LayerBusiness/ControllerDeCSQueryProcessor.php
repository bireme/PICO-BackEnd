<?php

namespace LayerBusiness;

require_once(realpath(dirname(__FILE__)) . '/../SimpleLife/ManualExceptions.php');
require_once(realpath(dirname(__FILE__)) . '/../SimpleLife/SimpleLifeMessages.php');
require_once(realpath(dirname(__FILE__)) . '/../LayerEntities/ObjectKeyword.php');

use LayerEntities\ObjectKeyword;
use SimpleLife\SimpleLifeMessage;
use SimpleLife\SimpleLifeException;

class ControllerDeCSQueryProcessor {

    private $ObjectKeywordList;

    public function __construct($ObjectKeywordList) {
        $this->ObjectKeywordList = $ObjectKeywordList;
    }

    public function BuildKeywordList() {
        $KeywordList = $this->splitQuery($this->ObjectKeywordList->getQuery());
        $this->AddKeywords($KeywordList);
    }

    private function AddKeywords($ItemArray) {
        $UsedKeyWords = array();
        foreach ($ItemArray as $Item) {
            if (strlen($Item['value']) == 0) {
                continue;
            }
            if ($Item['type'] == 'key') {
                if (in_array($Item['value'], $UsedKeyWords)) {
                    $Item['type'] = 'keyrep';
                    $obj = $Item['value'];
                } else {
                    $obj = new ObjectKeyword($Item['value'], $this->ObjectKeywordList->getLang());
                    array_push($UsedKeyWords, $Item['value']);
                }
            } else {
                $obj = $Item['value'];
            }
            $valx = array('type' => $Item['type'], 'value' => $obj);
            $this->ObjectKeywordList->AddKeyword($valx);
        }
    }

    public function splitQuery($query) {
        $query = str_replace('  ', ' ', $query);
        $query = strtolower($query);
        $pattern = '/[() ]/m';
        $PreArr = preg_split($pattern, $query, -1, PREG_SPLIT_DELIM_CAPTURE | PREG_SPLIT_NO_EMPTY);
        $Arr = array();
        $Ops = array('or', 'and', 'not');
        $Seps = array('(', ')', ' ', ':');
        foreach ($PreArr as $value) {
            if (in_array($value, $Seps)) {
                $type = 'sep';
            } else {
                if (in_array($value, $Ops)) {
                    $type = 'op';
                } else {
                    $type = 'key';
                }
            }
            $valx = array('type' => $type, 'value' => $value);
            array_push($Arr, $valx);
        }
        return $Arr;
    }

}

?>