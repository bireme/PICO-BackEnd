<?php

namespace PICOExplorer\Services\KeywordManager;

use PICOExplorer\Exceptions\Exceptions\AppError\PreviousDataCouldNotBeDecoded;
use PICOExplorer\Exceptions\Exceptions\ClientError\NoNewDataToExplore;
use PICOExplorer\Models\DataTransferObject;
use PICOExplorer\Services\ServiceModels\PICOQueryProcessorTrait;
use PICOExplorer\Services\ServiceModels\ServiceEntryPoint;
use PICOExplorer\Services\ServiceModules\BuildHTMLTrait;
use Throwable;

abstract class KeywordManagerSupport extends ServiceEntryPoint
{
    use PICOQueryProcessorTrait;
    use BuildHTMLTrait;

    protected function ProcessInitialQuery(string $query)
    {
        $QueryProcessed = $this->ProcessQuery($query);
        return $QueryProcessed;
    }

    protected function BuildKeywordList(DataTransferObject $DTO, array $QueryProcessed, array $langArr)
    {
        $PreviousData = $DTO->getAttr('PreviousData');
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


    protected function BuildFormData(DataTransferObject $DTO, int $PICOnum)
    {
        $FormData = $this->ProcessUnexploredList($DTO);
        $this->SaveData($DTO, $FormData, $PICOnum);
    }


///////////////////////////////////////////////////////////////////
//INNER FUNCTIONS
///////////////////////////////////////////////////////////////////

    private function ProcessUnexploredList(DataTransferObject $DTO)
    {
        $KeywordList = $DTO->getAttr('KeywordList');
        $Explored = [];
        $Partial = [];
        $Unexplored = [];
        foreach ($KeywordList as $keyword => $keywordData) {
            $value = json_encode(['keyword' => $keyword, 'langs' => $keywordData['Unexplored']]);
            if (count($keywordData['Explored']) === 0) {
                $title = $keyword . ' [Not explored]';
                array_push($Unexplored, ['title' => $title, 'value' => $value, 'checked' => true]);
            } elseif (count($keywordData['Unexplored']) === 0) {
                $title = $keyword . ' [Already explored]';
                array_push($Explored, ['title' => $title, 'value' => $value, 'checked' => false]);
            } else {
                $title = $keyword . ' [Remaining langs:' . json_encode($keywordData['Unexplored']) . ']';
                array_push($Partial, ['title' => $title, 'value' => $value, 'checked' => true]);
            }
        }
        $FormData = [
            'Unexplored' => $Unexplored,
            'Partial' => $Partial,
            'Explored' => $Explored,
        ];
        return $FormData;
    }

    private function SaveData(DataTransferObject $DTO, array $FormData, int $PICOnum)
    {
        $QuerySplit = $DTO->getAttr('QuerySplit');
        $HTML = $this->BuildHiddenField('keywordform', 'querysplit', $QuerySplit);
        $HTML = $HTML . $this->BuildHiddenField('keywordform', 'piconum', $PICOnum);
        $HTML = $HTML . $this->BuildHTML('keywordform', $FormData);
        $DTO->SaveToModel(get_class($this), ['HTML' => $HTML]);
    }

    private function setArrayItemType(array $ItemArray, array $PreviousData, array $langArr)
    {
        $ItemArray = array_map('strtolower', $ItemArray);
        $KeywordsDeCSTerms = $this->getDeCSAndKeywords($PreviousData);
        $Ops = ['or', 'and', 'not'];
        $Seps = ['(', ')', ' ', ':'];
        $UsedKeyWords = [];
        $KeywordList = [];
        $QuerySplit = [];
        $type = null;
        foreach ($ItemArray as $value) {
            if (strlen($value) == 0) {
                continue;
            }
            if (in_array($value, $Seps)) {
                $type = 'sep';
            } elseif (in_array($value, $Ops)) {
                $type = 'op';
            } else {
                $type = $this->getWordType($value, $KeywordsDeCSTerms, $UsedKeyWords);
                if ($type === 'keyexplored' || $type = 'keyword') {
                    $KeywordData = $this->getUnexploredLangs($value, $PreviousData, $langArr);
                    if ($type === 'keyexplored') {
                        if (count($KeywordData['Unexplored']) > 0) {
                            $type = 'keypartial';
                        }
                    }
                    array_push($UsedKeyWords, $value);
                    $KeywordList[$value] = $KeywordData;
                }
            }
            array_push($QuerySplit, ['type' => $type, 'value' => $value]);
        }
        $res = ['QuerySplit' => $QuerySplit, 'KeywordList' => $KeywordList];
        return $res;
    }

    private function getUnexploredLangs(string $keyword, array $PreviousData, array $langArr)
    {
        $KeywordData = $PreviousData[$keyword]??null;
        if (!($KeywordData)) {
            return [
                'Unexplored' => $langArr,
                'Explored' => [],
            ];
        }
        $Unexplored = [];
        foreach ($KeywordData as $tree_id => $treedata) {
            $localLangs = array_diff(array_keys($treedata), ['descendants']);
            $tmpUnexplored = array_diff($langArr, $localLangs);
            array_merge($Unexplored, $tmpUnexplored);
        }
        $Explored = array_diff($langArr, $Unexplored);
        return [
            'Unexplored' => $Unexplored,
            'Explored' => $Explored,
        ];

    }


    private function getWordType(string $word, array $KeywordsDeCSTerms, array $UsedKeyWords)
    {
        $word = strtolower($word);
        if (in_array($word, $KeywordsDeCSTerms['DeCS'])) {
            return 'decs';
        } elseif (in_array($word, $KeywordsDeCSTerms['terms'])) {
            return 'terms';
        } elseif (in_array($word, $UsedKeyWords)) {
            return 'keyrep';
        } elseif (in_array($word, $KeywordsDeCSTerms['Keywords'])) {
            return 'keyexplored';
        } else {
            return 'keyword';
        }
    }

    private function getDeCSAndKeywords(array $PreviousData = null)
    {
        $Keywords = [];
        $DeCS = [];
        $terms = [];
        if ($PreviousData && is_array($PreviousData)) {
            foreach ($PreviousData as $keyword => $KeywordData) {
                array_push($Keywords, $keyword);
                foreach ($KeywordData as $tree_id => $TreeData) {
                    foreach ($TreeData as $lang => $langData) {
                        if ($lang !== 'descendants') {
                            $DeCS = array_merge($DeCS, $langData['decs']);
                            array_push($terms, $langData['term']);
                        }
                    }
                }
            }
            $Keywords = array_map('strtolower', $Keywords);
            $DeCS = array_map('strtolower', $DeCS);
            $terms = array_map('strtolower', $terms);
            $Keywords = array_unique($Keywords);
            $DeCS = array_unique($DeCS);
            $terms = array_unique($terms);
            $DeCS = array_diff($DeCS, $terms);
            $DeCS = array_diff($DeCS, $Keywords);
            $terms = array_diff($terms, $Keywords);
        }
        $KeywordsDeCSTerms = [
            'Keywords' => $Keywords,
            'DeCS' => $DeCS,
            'terms' => $terms,
        ];
        return $KeywordsDeCSTerms;
    }

}
