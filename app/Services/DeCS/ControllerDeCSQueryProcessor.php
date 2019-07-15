<?php

namespace PICOExplorer\Services\DeCS;

require_once(realpath(dirname(__FILE__)) . '/../LayerEntities/ObjectKeyword.php');

use LayerEntities\ObjectKeyword;

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

    ///////////////////////////////////////////////////////////////////
    //PUBLIC FUNCTIONS
    /////////////////////////////////////////////////////////////////// 

    public function ProcessQuery($query, &$QueryProcessed) {
        $QuerySplitBySeparators = NULL;
        return $this->splitQueryWithDelimiters($query, $QuerySplitBySeparators) ||
                $this->setArrayItemElements($QuerySplitBySeparators, $QueryProcessed);
    }

    public function BuildKeywordList() {
        $query = $this->ObjectQuerySplitList->getQuery();
        $QueryProcessed = NULL;
        $QuerySplitBySepsWithValues=NULL;
        return $this->ProcessQuery($query, $QueryProcessed) ||
                $this->setArrayItemType($QueryProcessed, $QuerySplitBySepsWithValues) ||
                        $this->AddToItemList($QuerySplitBySepsWithValues);
    }

    ///////////////////////////////////////////////////////////////////
    //INNER FUNCTIONS
    /////////////////////////////////////////////////////////////////// 


    private function AddPreviousDataToResults($ObjectKeywordList, $previousData) {
        if (!(is_array($previousData))) {
            return;
        }
        foreach ($previousData as $keyword => $KeywordData) {
            $KeywordObj = $this->ObjectKeywordList->CreateKeywordObject($keyword);
            $KeywordObj->setBaseData($previousData);
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

    private function AddToItemList($ItemArray) {;
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

    private function getKeywordsTitlesAndObjects() {
        $AllItemList = $this->ObjectKeywordList->getKeywordList();
        $results = array();
        foreach ($AllItemList as $item) {
            $results[$item->getKeyword()] = $item;
        }
        return $results;
    }

    private function setArrayItemType($ItemArray, &$QuerySplitBySepsWithValues) {
        $ItemArray = array_map('strtolower', $ItemArray);
        $ItemArrayCopy = json_decode(json_encode($ItemArray), true);
        $QuerySplit = array();
        $keywords = Array();
        $Ops = array('or', 'and', 'not');
        $Seps = array('(', ')', ' ', ':');
        $UsedKeyWords = array();
        $ExploredKeywords = $this->getKeywordsTitlesAndObjects();
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
                            $KeywordObj = $this->ObjectKeywordList->CreateKeywordObject($value);
                            break;
                        case 'keyexplored':
                            array_push($UsedKeyWords, $value);
                            break;
                    }
                }
            }
            array_push($QuerySplit, array('type' => $type, 'value' => $value));
        }
        $QuerySplitBySepsWithValues = $QuerySplit;
    }
    
    private function splitQueryWithDelimiters($query, &$QuerySplitBySeparators) {
        $query = str_replace('  ', ' ', $query);
        $query = strtolower($query);
        $pattern = '/([()": ])/';
        $QuerySplitBySeparators = preg_split($pattern, $query, -1, PREG_SPLIT_DELIM_CAPTURE | PREG_SPLIT_NO_EMPTY);
    }

    private function FixComposeParenthesesQuoted($QuerySplitBySeparators, &$FixedQuoted) {
        $Arr = array();
        $doublequote = 0;
        foreach ($QuerySplitBySeparators as $QuerySplitBySepsElement) {
            if ($doublequote == 0) {
                if ($QuerySplitBySepsElement == '"') {
                    $doublequote = 1;
                    $NewComposedKeyword = '';
                } else {
                    array_push($Arr, $QuerySplitBySepsElement);
                }
            } else {
                if ($QuerySplitBySepsElement == '"') {
                    $doublequote = 0;
                    array_push($Arr, $NewComposedKeyword);
                } else {
                    $NewComposedKeyword = $NewComposedKeyword . $QuerySplitBySepsElement;
                }
            }
        }
        $FixedQuoted = $Arr;
    }

    private function FixComposeParentheses($FixedQuoted,&$QueryProcessed) {
        $index = count($FixedQuoted);
        $Ops = array('or', 'and', 'not');
        $Seps = array('(', ')', ' ', ':');
        $i = 0;
        $Arr = array();
        while ($i < $index) {
            if ((($i + 2 < $index))) {
                if (((!(in_array(strtolower($FixedQuoted[$i]), $Seps) || in_array(strtolower($FixedQuoted[$i]), $Ops))) && ($FixedQuoted[$i + 1] == ' ' && (!(in_array(strtolower($FixedQuoted[$i + 2]), $Seps) || in_array(strtolower($FixedQuoted[$i + 2]), $Ops)))))) {
                    $valx = $FixedQuoted[$i] . $FixedQuoted[$i + 1] . $FixedQuoted[$i + 2];
                    $i = $i + 3;
                } else {
                    $valx = $FixedQuoted[$i];
                    $i++;
                }
            } else {
                $valx = $FixedQuoted[$i];
                $i++;
            }
            $valx = ucfirst($valx);
            array_push($Arr, $valx);
        }
        $QueryProcessed = $Arr;
    }

    private function setArrayItemElements($QuerySplitBySeparators, &$QueryProcessed) {
        $FixedQuoted=NULL;
        return $this->FixComposeParenthesesQuoted($QuerySplitBySeparators, $FixedQuoted) ||
                $this->FixComposeParentheses($FixedQuoted,$QueryProcessed);
    }

}

?>
