<?php

namespace PICOExplorer\Services\ResultsNumberIntegration\Looper;

use Illuminate\Support\Facades\Session;
use PICOExplorer\Models\DataTransferObject;
use PICOExplorer\Services\ServiceModels\ServiceEntryPoint;
use PICOExplorer\Services\ServiceModels\ServiceEntryPointInterface;
use PICOExplorer\Services\ServiceModels\ToExternalSourceTrait;
use ServicePerformanceSV;

class BIREMESearchIntegration extends ServiceEntryPoint implements ServiceEntryPointInterface
{

    use ToExternalSourceTrait;

    protected final function Process(ServicePerformanceSV $ServicePerformance, DataTransferObject $DTO, $InitialData)
    {
        $InitialData = $DTO->getInitialData();
        $data = [
            'count' => 20,
            'q' => $InitialData['query'],
        ];
        $result= $this->Explore($ServicePerformance, $data);
        Session::put('ExploreURL', $result);
        Session::save();
        return true;
    }

    private final function Explore(ServicePerformanceSV $ServicePerformance, array $data)
    {
        $headers = ['Content-Type' => 'application/x-www-form-urlencoded'];
        $sendAsJson = true;
        $ParseJSONReceived = false;
        $maxAttempts = 3;
        $timeout = 30;
        $fullURL = 'https://pesquisa.bvsalud.org/portal/?';
        $RequestMethod = 'POST';
        return $this->MakeRequest($ServicePerformance, $RequestMethod, $data, $fullURL, $headers, $timeout, $maxAttempts, $sendAsJson, $ParseJSONReceived);
    }

}
