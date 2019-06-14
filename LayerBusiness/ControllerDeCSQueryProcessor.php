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
    private $ObjectQuerySplitList;
    private $previousData;

    public function __construct($ObjectQuerySplitList, $ObjectKeywordList, $previousData) {
        $this->ObjectQuerySplitList = $ObjectQuerySplitList;
        $this->ObjectKeywordList = $ObjectKeywordList;
        $this->previousData = json_decode($previousData, true);
        $this->AddPreviousDataToResults($ObjectKeywordList, $this->previousData);
    }

    private function AddPreviousDataToResults($ObjectKeywordList, $previousData) {
        if (!(is_array($previousData))) {
            return;
        }
        foreach ($previousData as $keyword => $KeywordData) {
            $KeywordObj = new ObjectKeyword($keyword, $this->ObjectQuerySplitList->getUsedLangs(),$this->ObjectQuerySplitList->getMainLanguage());
            $KeywordObj->setPreviousBaseData($previousData);
            $ObjectKeywordList->AddKeyword($KeywordObj);
        }
    }

    private function isDeCS($value, $ItemArrayCopy) {
        if (!(is_array($this->previousData))) {
            return false;
        }

        foreach ($this->previousData as $keyword => $KeywordData) {
            $KeywordDeCS = array();
            foreach ($KeywordData['content'] as $term => $termdata) {
                foreach ($termdata as $tree_id => $DeCSLang) {
                    foreach ($DeCSLang as $lang => $DeCS) {
                        $KeywordDeCS = array_merge($KeywordDeCS, $DeCS);
                    }
                }
            }
            $KeywordDeCS = array_map('strtolower', $KeywordDeCS);
            if (in_array($value, $KeywordDeCS)) {
                $countX = 0;
                foreach ($ItemArrayCopy as $valueCopy) {
                    if (in_array($valueCopy, $KeywordDeCS)) {
                        $countX++;
                    }
                }
                if ($countX > 1) {
                    return true;
                } else {
                    return false;
                }
            }
        }
        return false;
    }

    public function ProcessQuery($query) {
        $PreFixArr = $this->splitQueryWithDelimiters($query);
        return $this->setArrayItemElements($PreFixArr);
    }

    public function BuildKeywordList() {
        $query = $this->ObjectQuerySplitList->getQuery();
        $PreArr = $this->ProcessQuery($query);
        $ListObj = $this->setArrayItemType($PreArr);
        $this->AddToItemList($ListObj);
    }

    private function AddToItemList($ItemArray) {
        foreach ($ItemArray as $Item) {
            $this->ObjectQuerySplitList->AddToItemList($Item);
        }
    }

    private function getKeywordType($value, $UsedKeyWords, $ExploredKeywords, $ItemArrayCopy) {
        if (count($ExploredKeywords) == 0) {
            $typeX = 'key';
        } else {
            $isDeCS = $this->isDeCS($value, $ItemArrayCopy);
            $isRepeated = in_array($value, $UsedKeyWords);
            if (array_key_exists($value, $this->previousData)) {
                $typeX = 'keyexplored';
                if ($isDeCS) {
                    $typeX = 'DeCS';
                }
            } else {
                $typeX = 'key';
                if ($isDeCS) {
                    $typeX = 'DeCS';
                }
            }
            if (($typeX == 'key' || $typeX == 'keyexplored') && $isRepeated) {
                $typeX = 'keyrep';
            }
        }
        return $typeX;
    }

    private function BuildDeCSList($ExploredKeywords) {
        return $this->ObjectKeywordList->getAllDeCS();
    }

    private function setArrayItemType($ItemArray) {
        $ItemArray = array_map('strtolower', $ItemArray);
        $ItemArrayCopy = json_decode(json_encode($ItemArray), true);
        $QuerySplit = array();
        $keywords = Array();
        $Ops = array('or', 'and', 'not');
        $Seps = array('(', ')', ' ', ':');
        $UsedKeyWords = array();
        $ExploredKeywords = $this->ObjectKeywordList->getKeywordsTitlesAndObjects();
        foreach ($ItemArray as $value) {
            $keywordObj = NULL;
            if (strlen($value) == 0) {
                continue;
            }
            if (in_array($value, $Seps)) {
                $type = 'sep';
            } else {
                if (in_array($value, $Ops)) {
                    $type = 'op';
                } else {
                    $type = $this->getKeywordType($value, $UsedKeyWords, $ExploredKeywords, $ItemArrayCopy);
                    switch ($type) {
                        case 'key':
                            array_push($UsedKeyWords, $value);
                            $obj = new ObjectKeyword($value, $this->ObjectQuerySplitList->getUsedLangs(),$this->ObjectQuerySplitList->getMainLanguage());
                            $this->ObjectKeywordList->AddKeyword($obj);
                            break;
                        case 'keyexplored':
                            array_push($UsedKeyWords, $value);
                            break;
                    }
                }
            }
            array_push($QuerySplit, array('type' => $type, 'value' => $value));
        }
        return $QuerySplit;
    }

    private function splitQueryWithDelimiters($query) {
        $query = str_replace('  ', ' ', $query);
        $query = strtolower($query);
        $pattern = '/([()": ])/';
        $Ops = array('or', 'and', 'not');
        $PreFixArr = preg_split($pattern, $query, -1, PREG_SPLIT_DELIM_CAPTURE | PREG_SPLIT_NO_EMPTY);
        return $PreFixArr;
    }

    private function FixComposeParenthesesQuoted($IniArr) {
        $Arr = array();
        $doublequote = 0;
        foreach ($IniArr as $value) {
            if ($doublequote == 0) {
                if ($value == '"') {
                    $doublequote = 1;
                    $tmpx = '';
                } else {
                    array_push($Arr, $value);
                }
            } else {
                if ($value == '"') {
                    $doublequote = 0;
                    array_push($Arr, $tmpx);
                } else {
                    $tmpx = $tmpx . $value;
                }
            }
        }
        return $Arr;
    }

    private function FixComposeParentheses($IniArr) {
        $index = count($IniArr);
        $Ops = array('or', 'and', 'not');
        $Seps = array('(', ')', ' ', ':');
        $i = 0;
        $Arr = array();
        while ($i < $index) {
            if ((($i + 2 < $index))) {
                if (((!(in_array(strtolower($IniArr[$i]), $Seps) || in_array(strtolower($IniArr[$i]), $Ops))) && ($IniArr[$i + 1] == ' ' && (!(in_array(strtolower($IniArr[$i + 2]), $Seps) || in_array(strtolower($IniArr[$i + 2]), $Ops)))))) {
                    $valx = $IniArr[$i] . $IniArr[$i + 1] . $IniArr[$i + 2];
                    $i = $i + 3;
                } else {
                    $valx = $IniArr[$i];
                    $i++;
                }
            } else {
                $valx = $IniArr[$i];
                $i++;
            }
            $valx = ucfirst($valx);
            array_push($Arr, $valx);
        }
        return $Arr;
    }

    private function setArrayItemElements($PreFixArr) {
        $Arr=$this->FixComposeParenthesesQuoted($PreFixArr);
        $Arr=$this->FixComposeParentheses($Arr);
        return $Arr;
    }

}

?>