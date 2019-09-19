<?php

namespace PICOExplorer\Services\DeCSIntegration;

use PICOExplorer\Facades\DeCSIntegrationLooperFacade;
use PICOExplorer\Models\DataTransferObject;
use PICOExplorer\Services\ServiceModels\ServiceEntryPointInterface;
use PICOExplorer\Services\ServiceModels\ToParallelServiceIntegrationTrait;
use ServicePerformanceSV;

class DeCSIntegrationProcess extends DeCSIntegrationSupport implements ServiceEntryPointInterface
{

    use ToParallelServiceIntegrationTrait;

    protected final function Process(ServicePerformanceSV $ServicePerformance, DataTransferObject $DTO,$InitialData)
    {
        $this->setInitialVars($DTO);
        $this->ExploreMainTree($ServicePerformance, $DTO);
        $this->ExploreUnexploredLanguagesPerTreeId($ServicePerformance, $DTO);
        $this->ExploreSecondaryTrees($ServicePerformance, $DTO);
        return $this->getDataByTreeId($DTO);
    }

    ///////////////////////////////////////////////////////////////////
    //INNER FUNCTIONS
    //////////////////////////////////////////////////////////////////

    protected function Explore(ServicePerformanceSV $ServicePerformance, string $key, string $lang, bool $IsMainTree,bool $reExploringMainTrees,DataTransferObject $DTO)
    {

        $FirstArgument = 'tree_id';
        if ($IsMainTree == true) {
            $FirstArgument = 'words';
        }
        $data = [
            $FirstArgument => $key,
            'lang' => $lang,
        ];
        if($reExploringMainTrees){
            $IsMainTree=true;
        }
        $this->addLogData('Performing connection with query',$data,$DTO);
        $XMLresults = $this->Connect($ServicePerformance, $data);
        if($XMLresults!=='none'){
            foreach($XMLresults as $content){
                $this->ProcessImportResults($content, $lang, $IsMainTree,$DTO);
            }
        }
    }

    ///////////////////////////////////////////////////////////////////
    //INNER FUNCTIONS
    ///////////////////////////////////////////////////////////////////

    private final function Connect(ServicePerformanceSV $ServicePerformance, $data)
    {
        $AdvancedFacade= new DeCSIntegrationLooperFacade();
        $XMLresults = $this->ToParallelServiceIntegration($ServicePerformance, $AdvancedFacade, $data);
        return $XMLresults;
    }

}
