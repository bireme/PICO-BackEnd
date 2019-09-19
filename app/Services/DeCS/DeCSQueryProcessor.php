<?php

namespace PICOExplorer\Services\DeCS;

use PICOExplorer\Exceptions\Exceptions\AppError\CouldntDetectWordType;
use PICOExplorer\Exceptions\Exceptions\AppError\CouldntIdentifyKeywordType;
use PICOExplorer\Exceptions\Exceptions\AppError\getKeywordTypeReturnedUnknownType;
use PICOExplorer\Exceptions\Exceptions\AppError\PreviousDataCouldNotBeDecoded;
use PICOExplorer\Exceptions\Exceptions\ClientError\NoNewDataToExplore;
use PICOExplorer\Models\DataTransferObject;
use PICOExplorer\Services\ServiceModels\PICOQueryProcessorTrait;
use Throwable;

abstract class DeCSQueryProcessor extends DeCSMenuBuilder
{
    use PICOQueryProcessorTrait;

    protected function BuildKeywordList(DataTransferObject $DTO, string $query, array $PreviousData, array $langArr)
    {
        $QueryProcessed = $this->ProcessQuery($query);
        $KeywordListAndQuerySplit = $this->setArrayItemType($QueryProcessed, $PreviousData, $langArr);
        if (count($KeywordListAndQuerySplit['KeywordList'] ?? []) === 0) {
            throw new NoNewDataToExplore(['TreesToExplore' => null]);
        }
        $DTO->SaveToModel(get_class($this), $KeywordListAndQuerySplit);
    }

    protected function DecodePreviousData(DataTransferObject $DTO, string $undecodedPreviousData)
    {
        $decodedPrevious = null;
        if ($undecodedPreviousData) {
            try {
                $decodedPrevious = json_decode($undecodedPreviousData, true);
            } catch (Throwable $ex) {
                throw new PreviousDataCouldNotBeDecoded(['PreviousData' => json_encode($undecodedPreviousData)], $ex);
            }
        } else {
            $decodedPrevious = [];
        }
        $DTO->SaveToModel(get_class($this), ['PreviousData' => $decodedPrevious]);
    }


///////////////////////////////////////////////////////////////////
//INNER FUNCTIONS
///////////////////////////////////////////////////////////////////

    private function setArrayItemType(array $ItemArray, array $SavedData, array $langArr)
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
                    $keywordData = $this->getKeywordType($value, $UsedKeyWords, $ItemArray, $SavedData, $langArr);
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
        $res = ['QuerySplit' => $QuerySplit, 'KeywordList' => $KeywordList];
        return $res;
    }

    private function getKeywordType($value, array $UsedKeyWords, array $keywordsArr, array $SavedData, array $langArr)
    {
        $data = null;
        $results = [];
        $isDeCS = $this->isDeCS($value, $keywordsArr, $SavedData);
        $isRepeated = in_array($value, $UsedKeyWords);
        if ($SavedData !== null && array_key_exists($value, $SavedData)) {
            if ($isDeCS) {
                $results['type'] = 'DeCS';
            } else {
                if ($isRepeated) {
                    $results['type'] = 'keyrep';
                } else {
                    $currentLangs = array_keys(current($SavedData[$value]));
                    $remainingLangs = array_diff($langArr, $currentLangs);
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
                    $results['langs'] = $langArr;
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
