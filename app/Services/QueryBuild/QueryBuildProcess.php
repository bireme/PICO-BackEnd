<?php

namespace PICOExplorer\Services\QueryBuild;

use PICOExplorer\Exceptions\Exceptions\ClientError\QueryResultsCouldNotBeDecoded;
use PICOExplorer\Exceptions\Exceptions\ClientError\QueryResultsDoesNotExist;
use PICOExplorer\Exceptions\Exceptions\ClientError\QuerySplitCouldNotBeDecoded;
use PICOExplorer\Exceptions\Exceptions\ClientError\QuerySplitDoesNotExist;
use PICOExplorer\Services\ServiceModels\PICOQueryProcessorTrait;
use PICOExplorer\Services\ServiceModels\PICOServiceEntryPoint;
use Throwable;

class QueryBuildProcess extends QueryBuildBase implements PICOServiceEntryPoint
{

    use PICOQueryProcessorTrait;

    final public function Process()
    {
        $InitialData = $this->DTO->getInitialData();
        if ($InitialData['QuerySplit'] ?? null) {
            try {
                $decodedPrevious = json_decode($InitialData['QuerySplit'], true);
                $InitialData['QuerySplit'] = $decodedPrevious;
                $this->DTO->SaveToModel(get_class($this),['InitialData', $InitialData]);
            } catch (Throwable $ex) {
                throw new QuerySplitCouldNotBeDecoded(['QuerySplit' => json_encode($InitialData['QuerySplit'])], $ex);
            }
        } else {
            throw new QuerySplitDoesNotExist(['QuerySplit' => null]);
        }

        if ($InitialData['DeCSResults'] ?? null) {
            try {
                $decodedPrevious = json_decode($InitialData['DeCSResults'], true);
                $InitialData['DeCSResults'] = $decodedPrevious;
                $this->DTO->SaveToModel(get_class($this),['InitialData', $InitialData]);
            } catch (Throwable $ex) {
                throw new QueryResultsCouldNotBeDecoded(['DeCSResults' => json_encode($InitialData['DeCSResults'])], $ex);
            }
        } else {
            throw new QueryResultsDoesNotExist(['DeCSResults' => null]);
        }
        $this->Explore();

    }

    protected function Explore()
    {
        $baseEquation = $this->buildBaseEquation();
        $ProcessedImprovedSearchQuery = $this->ProcessQuery($this->DTO->getInitialData()['ImproveSearchQuery']);
        $ImprovedEquation = $this->ImproveBasicEquation($baseEquation, $ProcessedImprovedSearchQuery);
        $this->setResults(['newQuery'=>$ImprovedEquation]);
    }

}
