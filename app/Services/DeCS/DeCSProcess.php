<?php

namespace PICOExplorer\Services\DeCS;

use PICOExplorer\Exceptions\Exceptions\AppError\PreviousDataCouldNotBeDecoded;
use PICOExplorer\Exceptions\Exceptions\ClientError\NoNewDataToExplore;
use PICOExplorer\Services\ServiceModels\EquationCheckerTrait;
use PICOExplorer\Services\ServiceModels\PICOServiceEntryPoint;
use Throwable;

class DeCSProcess extends DeCSQueryProcessor implements PICOServiceEntryPoint
{
    use EquationCheckerTrait;

    protected $attributes = [
        'DescriptorsHTML' => '',
        'DeCSHTML' => '',
    ];

    final public function Process()
    {
        $previous = $this->DTO->getInitialData()['SavedData'] ?? null;
        $decodedPrevious = null;
        if ($previous) {
            try {
                $decodedPrevious = json_decode($previous, true);
            } catch (Throwable $ex) {
                throw new PreviousDataCouldNotBeDecoded(['PreviousData' => json_encode($previous)], $ex);
            }
        } else {
            $decodedPrevious = [];
        }
        $this->DTO->SaveToModel(get_class($this),['SavedData'=> $decodedPrevious]);
        $this->BuildKeywordList();
        $IntegrationResults = [];
        if (count($this->DTO->getAttr('KeywordList')) === 0) {
            throw new NoNewDataToExplore(['TreesToExplore' => null]);
        }
        foreach ($this->DTO->getAttr('KeywordList') as $keyword => $langs) {
            $IntegrationResults[$keyword] = $this->Explore($keyword, $langs);
        }
        $ProcessedResults = $this->ProcessIntegrationResults($IntegrationResults);
        $this->BuildHTML($ProcessedResults);
        $results = [
            'QuerySplit' => json_encode($this->DTO->getAttr('QuerySplit')),
            'SavedData' => json_encode($this->DTO->getAttr('SavedData')),
            'DescriptorsHTML' => $this->attributes['DescriptorsHTML'],
            'DeCSHTML' => $this->attributes['DeCSHTML'],

        ];
        $this->setResults($results);
    }

    protected function Explore(string $keyword, array $langs)
    {
        $Data = array(
            'keyword' => $keyword,
            'langs' => $langs,
        );
        $IntegrationResults = $this->ConnectToIntegration($Data);
        $this->IntegrationResultsToSavedData($IntegrationResults, $keyword);
        return $IntegrationResults;
    }

}
