<?php

namespace PICOExplorer\Services\DeCS;

use PICOExplorer\Exceptions\Exceptions\AppError\CouldntDetectWordType;
use PICOExplorer\Exceptions\Exceptions\AppError\CouldntIdentifyKeywordType;
use PICOExplorer\Exceptions\Exceptions\AppError\getKeywordTypeReturnedUnknownType;
use PICOExplorer\Services\ServiceModels\PICOQueryProcessorTrait;

abstract class DeCSQueryProcessor extends DeCSMenuBuilder
{
    use PICOQueryProcessorTrait;

    protected function BuildKeywordList()
    {
        $QueryProcessed = $this->ProcessQuery($this->DTO->getInitialData()['query']);
        $this->setArrayItemType($QueryProcessed);
    }

///////////////////////////////////////////////////////////////////
//INNER FUNCTIONS
///////////////////////////////////////////////////////////////////

    private function setArrayItemType(array $ItemArray)
    {
        $ItemArray = array_map('strtolower', $ItemArray);
        $Ops = ['or', 'and', 'not'];
        $Seps = ['(', ')', ' ', ':'];
        $UsedKeyWords = [];
        $KeywordList = [];
        $QuerySplit = [];
        $tmp = null;
        foreach ($ItemArray as $value) {
            $keywordObj = NULL;
            $type = 'UNKNOWNERROR';
            if (strlen($value) == 0) {
                continue;
            }
            if (in_array($value, $Seps)) {
                $type = 'sep';
            } else {
                if (in_array($value, $Ops)) {
                    $type = 'op';
                } else {
                    $type = null;
                    $keywordData = $this->getKeywordType($value, $UsedKeyWords, $ItemArray);
                    $type = $keywordData['type'];
                    switch ($keywordData['type']) {
                        case 'key':
                            $KeywordList[$value] = $keywordData['langs'];
                            array_push($UsedKeyWords, $value);
                            break;
                        case 'keypartial':
                            $type = 'key';
                            $KeywordList[$value] = $keywordData['langs'];
                            array_push($UsedKeyWords, $value);
                            break;
                        case 'keyexplored':
                            array_push($UsedKeyWords, $value);
                            break;
                        default:
                            break;
                    }
                    $errdata = [
                        'ErrorData' => [
                            'value' => $value,
                        ]
                    ];
                    if ($type === 'UNKNOWNERROR') {
                        throw new CouldntDetectWordType($errdata);
                    } elseif ($type === null) {
                        throw new CouldntIdentifyKeywordType($errdata);
                    } elseif ($type === 'KEYWORDTYPEERRORTWO') {
                        throw new getKeywordTypeReturnedUnknownType($errdata);
                    }
                }
            }
            array_push($QuerySplit, ['type' => $type, 'value' => $value]);
        }
        $this->DTO->SaveToModel(get_class($this),['QuerySplit' => $QuerySplit,'KeywordList' => $KeywordList]);
    }

    private function getKeywordType($value, array $UsedKeyWords, array $keywordsArr)
    {
        $data = null;
        $results = [];
        $SavedData = $this->DTO->getAttr('SavedData');
        $isDeCS = $this->isDeCS($value, $keywordsArr, $SavedData);
        $isRepeated = in_array($value, $UsedKeyWords);

        $langs = $this->DTO->getInitialData()['langs'];
        if ($SavedData !== null && array_key_exists($value, $SavedData)) {
            if ($isDeCS) {
                $results['type'] = 'DeCS';
            } else {
                if ($isRepeated) {
                    $results['type'] = 'keyrep';
                } else {
                    $currentLangs = array_keys(current($SavedData[$value]));
                    $remainingLangs = array_diff($langs, $currentLangs);
                    if (count($remainingLangs) > 0) {
                        $results['type'] = 'keypartial';
                        $results['langs'] = $remainingLangs;
                    } else {
                        $results['type'] = 'keyexplored';
                    }
                }
            }
        } else {
            if ($isDeCS) {
                $results['type'] = 'DeCS';
            } else {
                if ($isRepeated) {
                    $results['type'] = 'keyrep';
                } else {
                    $results['type'] = 'key';
                    $results['langs'] = $langs;
                }
            }
        }
        if (!($results['type'] ?? null)) {
            throw new getKeywordTypeReturnedUnknownType(['word' => $value]);
        }
        return $results;
    }

    private function isDeCS($value, array $keywordsArr, array $SavedData = null)
    {
        if (!($SavedData) || !(is_array($SavedData))) {
            return false;
        }
        foreach ($SavedData as $keyword => $KeywordData) {
            $KeywordDeCS = [];
            foreach ($KeywordData as $tree_id => $TreeData) {
                $Exclude = [$keyword];
                foreach ($TreeData as $lang => $langData) {
                    $DeCSArr = array_diff($langData['decs'], $Exclude);
                    $KeywordDeCS = array_merge($KeywordDeCS, $DeCSArr);
                }
            }

            $KeywordDeCS = array_map('strtolower', $KeywordDeCS);
            if (in_array(strtolower($value), $KeywordDeCS)) {
                foreach ($keywordsArr as $keyword) {
                    if (in_array($keyword, $KeywordDeCS)) {
                        return true;
                    }
                }
            }
        }
        return false;
    }

}
