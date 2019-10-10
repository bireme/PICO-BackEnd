<?php

namespace PICOExplorer\Services\DeCS;

use Illuminate\Support\Facades\Lang;
use PICOExplorer\Exceptions\Exceptions\AppError\FaltaImplementarError;
use PICOExplorer\Exceptions\Exceptions\AppError\PreviousDataCouldNotBeDecoded;
use PICOExplorer\Exceptions\Exceptions\ClientError\NoContentFound;
use PICOExplorer\Facades\UltraLoggerFacade;
use PICOExplorer\Models\DataTransferObject;
use PICOExplorer\Services\ServiceModels\ServiceEntryPoint;
use PICOExplorer\Services\ServiceModules\BuildHTMLTrait;
use PICOExplorer\Services\AdvancedLogger\Services\UltraLoggerDevice;
use Throwable;

abstract class DeCSSupport extends ServiceEntryPoint
{

    use BuildHTMLTrait;

    protected function DecodePreviousData(DataTransferObject $DTO, string $undecodedPreviousData = null, string $undecodedImproveSearchWords = null, string $undecodedOldSelectedDescriptors = null)
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
        $ImproveSearchWords = null;
        if ($undecodedImproveSearchWords) {
            try {
                $ImproveSearchWords = json_decode($undecodedImproveSearchWords, true);
            } catch (Throwable $ex) {
                throw new PreviousDataCouldNotBeDecoded(['ImproveSearchWords' => json_encode($undecodedImproveSearchWords)], $ex);
            }
        } else {
            $ImproveSearchWords = [];
        }
        $OldSelectedDescriptors = null;
        if ($undecodedOldSelectedDescriptors) {
            try {
                $OldSelectedDescriptors = json_decode($undecodedOldSelectedDescriptors, true);
            } catch (Throwable $ex) {
                throw new PreviousDataCouldNotBeDecoded(['OldSelectedDescriptors' => json_encode($undecodedOldSelectedDescriptors)], $ex);
            }
        } else {
            $OldSelectedDescriptors = [];
        }
        $DTO->SaveToModel(get_class($this), ['PreviousData' => $decodedPrevious, 'ImproveSearchWords' => $ImproveSearchWords, 'OldSelectedDescriptors' => $OldSelectedDescriptors]);
    }

    protected function MixWithOldData(DataTransferObject $DTO, array $IntegrationData, array $langs, UltraLoggerDevice $Log)
    {
        $SavedData = $DTO->getAttr('PreviousData');
        foreach ($IntegrationData as $keyword => $keywordData) {
            if (!($keywordData)) {
                continue;
            }
            if ($SavedData[$keyword] ?? null === null) {
                $SavedData[$keyword] = [];
            }
            foreach ($keywordData as $tree_id => $treeData) {
                if ($SavedData[$keyword][$tree_id] ?? null === null) {
                    $SavedData[$keyword][$tree_id] = [];
                }
                if (count($treeData['remaininglangs'] ?? [])) {
                    UltraLoggerFacade::ErrorToUltraLogger($Log, 'ERROR STILL REMAINING LANGS: ' . $keyword . ' data:' . json_encode($treeData));
                }
                foreach ($treeData as $lang => $content) {
                    if ($lang === 'descendants' || $lang === 'remaininglangs') {
                        continue;
                    }
                    if ($SavedData[$keyword][$tree_id][$lang] ?? null === null) {
                        $IntegrationTerm = $content['term'];
                        $IntegrationDeCS = $content['decs'];
                        $SavedData[$keyword][$tree_id][$lang] = [
                            'term' => $IntegrationTerm,
                            'decs' => $IntegrationDeCS,
                        ];
                    }
                }
            }
        }
        $DTO->SaveToModel(get_class($this), ['SavedData' => $SavedData]);
    }

    protected function BuildAsTerms(DataTransferObject $DTO, array $langs, string $mainLanguage, UltraLoggerDevice $Log)
    {
        $ProcessedDescriptors = [];
        $ProcessedDeCS = [];


        if (in_array($mainLanguage, $langs)) {
            $TitleLanguage = $mainLanguage;
        } else {
            if (in_array('en', $langs)) {
                $TitleLanguage = 'en';
            } else {
                $TitleLanguage = $langs[0];
            }
        }
        $MixedData = $DTO->getAttr('SavedData');
        $AllKeywordList = $DTO->getAttr('AllKeywords');
        $QuerySplit = $DTO->getAttr('QuerySplit');

        $usedData = null;
        $OldSelectedDescriptors = $DTO->getAttr('OldSelectedDescriptors');



        $notFound = [];
        foreach ($AllKeywordList as $keyword) {
            if (!(in_array($keyword, array_keys($MixedData)))) {
                array_push($notFound, $keyword);
                continue;
            }
        }

        if ($OldSelectedDescriptors !== null) {
            $usedData = [];
            foreach ($QuerySplit as $index => $arrayitem) {
                $type = $arrayitem['type'] ?? null;
                $value = $arrayitem['value'] ?? null;
                if (!($type === 'op' || $type === 'sep' || $type === 'improve')) {
                    if(($type==='keyword' || $type==='keyrep') && in_array($value,$notFound)){
                        $QuerySplit[$index]['type']='free';
                    }
                    array_push($usedData, $value);
                }
            }
        }



        $QuerySplitValuesToRemove = [];
        foreach ($MixedData as $keyword => $keywordData) {
            if (!(in_array($keyword, $AllKeywordList))) {
                continue;
            }
            $keyword = ucwords(strtolower($keyword));
            if ($OldSelectedDescriptors !== null) {
                $isNewKeyword = true;
            } else {
                if (($OldSelectedDescriptors[$keyword] ?? null) === null) {
                    $isNewKeyword = true;
                } else {
                    $isNewKeyword = false;
                }
            }
            $this->processKeywordDescriptors($Log, $keyword, $keywordData, $TitleLanguage, $langs, $ProcessedDescriptors, $ProcessedDeCS, $isNewKeyword, $OldSelectedDescriptors, $usedData);
        }
        if (count($notFound)) {
            $txtNotFound = Lang::get('Lang.NotFound');
            $ProcessedDescriptors[$txtNotFound] = [];
            foreach ($notFound as $word) {
                $word = ucwords(strtolower($word));
                array_push($ProcessedDescriptors[$txtNotFound], ['title' => $word, 'value' => $word, 'checked' => -1]);
            }
        }

//dd(['ProcessedDescriptors' => $ProcessedDescriptors, 'ProcessedDeCS' => $ProcessedDeCS]);
        $DTO->SaveToModel(get_class($this), ['ProcessedDescriptors' => $ProcessedDescriptors, 'ProcessedDeCS' => $ProcessedDeCS, 'QuerySplit' => $QuerySplit]);
    }

    protected function processKeywordDescriptors(UltraLoggerDevice $Log, string $keyword, array $keywordData, String $TitleLanguage, array $langs, array &$ProcessedDescriptors, array &$ProcessedDeCS, bool $isNewKeyword, array $OldSelectedDescriptors = null, array $usedData = null)
    {
        $UsedTerms = [];
        foreach ($keywordData as $tree_id => $TreeObject) {
            $Term = $TreeObject[$TitleLanguage]['term'] ?? null;
            if (!($Term)) {
                UltraLoggerFacade::ErrorToUltraLogger($Log, 'Tree_id ' . $tree_id . ' has no Term in language ' . $TitleLanguage);
                continue;
            }
            if (in_array($Term, $UsedTerms)) {
                continue;
            }
            $alldecsInTerm = null;
            $alldecsInTerm = [];
            array_push($UsedTerms, $Term);
            $this->AddToDeCSTotal($alldecsInTerm, $TreeObject, $langs);
            foreach ($keywordData as $tree_idTwo => $TreeObjectTwo) {
                if (!($Term)) {
                    UltraLoggerFacade::ErrorToUltraLogger($Log, 'Tree_id ' . $tree_id . ' has no Term in language ' . $TitleLanguage);
                    continue;
                }
                if ($tree_id === $tree_idTwo) {
                    continue;
                }
                $TermTwo = $TreeObjectTwo[$TitleLanguage]['term'];
                if ($Term !== $TermTwo) {
                    continue;
                }
                $this->AddToDeCSTotal($alldecsInTerm, $TreeObjectTwo, $langs);
            }
            $this->AddToResults($keyword, $Term, $ProcessedDescriptors, $ProcessedDeCS, $alldecsInTerm, $isNewKeyword, $OldSelectedDescriptors, $usedData);
        }
    }

    private function AddToResults(String $keyword, String $Term, array &$ProcessedDescriptors, array &$ProcessedDeCS, array $alldecsInTerm, bool $isNewKeyword, array $OldSelectedDescriptors = null)
    {
        $Term = ucwords(strtolower($Term));
        $OldTerm=[];
        $DeCSTitle = $Term . ' [' . $keyword . ']';
        if ($isNewKeyword === 1) {
            $CheckedTerm = 1;
        } else {
            $OldTerm = $OldSelectedDescriptors[$keyword][$Term] ?? null;
            if ($OldTerm === null) {
                $CheckedTerm = 1;
            } else {
                if (count($OldTerm)) {
                    $CheckedTerm = 1;
                } else {
                    $CheckedTerm = 0;
                }
            }
        }

        $this->newHtmlArray($ProcessedDescriptors, $keyword, $Term, $Term, $CheckedTerm);


        $alldecsInTerm = array_map('strtolower', $alldecsInTerm);
        $alldecsInTerm = array_map('ucwords', $alldecsInTerm);
        $alldecsInTerm = array_unique($alldecsInTerm);
        $alldecsInTerm = array_diff($alldecsInTerm, [$keyword, $Term]);

        foreach ($alldecsInTerm as $DeCS) {
            if ($isNewKeyword) {
                $CheckedDeCS = 1;
            } else {
                if ($CheckedTerm === 0) {
                    $CheckedDeCS = 0;
                } else {
                    if (in_array($DeCS, $OldTerm)) {
                        $CheckedDeCS = 1;
                    } else {
                        $CheckedDeCS = 0;
                    }
                }
            }

            $this->newHtmlArray($ProcessedDeCS, $DeCSTitle, $DeCS, $DeCS, $CheckedDeCS);
        }

    }

    private function newHtmlArray(array &$array, String $mainKey, String $title, String $value, int $isChecked)
    {
        if (!($array[$mainKey] ?? null)) {
            $array[$mainKey] = [];
        }
        array_push($array[$mainKey], ['title' => $title, 'value' => $value, 'checked' => $isChecked]);
    }


    private function AddToDeCSTotal(array &$alldecsInTerm, array $TreeObject, array $langs)
    {
        foreach ($TreeObject as $lang => $content) {
            if (!(in_array($lang, $langs))) {
                continue;
            }
            $term = $content['term'];
            $decs = $content['decs'];
            $alldecsInTerm = array_merge($alldecsInTerm, [$term], $decs);
        }
    }


    protected function FalseBuildDeCSHTML(DataTransferObject $DTO, $arrayErrorMessageData)
    {
        $DescriptorsHTML = $this->BuildHTML('descriptorsform', $arrayErrorMessageData, Lang::get('lang.TooMuch'));
        $DTO->SaveToModel(get_class($this), ['DescriptorsHTML' => $DescriptorsHTML, 'DeCSHTML' => '']);
    }


    protected
    function BuildDeCSHTML(DataTransferObject $DTO, int $PICOnum)
    {
        $ProcessedDescriptors = $DTO->getAttr('ProcessedDescriptors');
        $ProcessedDeCS = $DTO->getAttr('ProcessedDeCS');
        if (count($ProcessedDescriptors) === 0) {
            throw new NoContentFound();
        }
        $QuerySplit = $DTO->getAttr('QuerySplit');
        //dd($QuerySplit);
        $DeCSHTML = $this->BuildHTML('decsform', $ProcessedDeCS);
        $DescriptorsHTML = $this->BuildHiddenField('descriptorsform', 'querysplit', $QuerySplit);
        $DescriptorsHTML = $DescriptorsHTML . $this->BuildHiddenField('descriptorsform', 'piconum', $PICOnum);
        $DescriptorsHTML = $DescriptorsHTML . $this->BuildHTML('descriptorsform', $ProcessedDescriptors);
        $DTO->SaveToModel(get_class($this), ['DescriptorsHTML' => $DescriptorsHTML, 'DeCSHTML' => $DeCSHTML]);
    }

///////////////////////////////////////////////////////////////////
//INNER FUNCTIONS
///////////////////////////////////////////////////////////////////


}
