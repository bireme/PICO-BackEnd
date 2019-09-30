<?php

namespace PICOExplorer\Services\DeCSIntegration\Looper;

use DOMXPath;
use PICOExplorer\Exceptions\Exceptions\AppError\ErrorInDeCSLoopXMLExtraction;
use PICOExplorer\Facades\UltraLoggerFacade;
use PICOExplorer\Services\AdvancedLogger\Exceptions\DontCatchException;
use PICOExplorer\Services\ServiceModels\ParallelServiceEntryPointInterface;
use PICOExplorer\Services\ServiceModels\ToExternalSourceTrait;
use ServicePerformanceSV;

class DeCSIntegrationLooperImporter extends DeCSIntegrationLooperSupport implements ParallelServiceEntryPointInterface
{

    use ToExternalSourceTrait;

    protected final function Process(ServicePerformanceSV $ServicePerformance, $InitialData = null)
    {
        $headers = [];//['Content-Type' => 'application/x-www-form-urlencoded'];
        $sendAsJson = true;
        $ParseJSONReceived = false;
        $maxAttempts = 3;
        $timeout = 30;
        $fullURL = 'http://decs.bvsalud.org/cgi-bin/mx/cgi=@vmx/decs/?';
        $ConnData = $InitialData;
        $ConnData['url'] = $fullURL . http_build_query($InitialData);

        $XMLresults = $this->Connect($ServicePerformance, 'POST', $InitialData, $fullURL, $headers, $timeout, $maxAttempts, $sendAsJson, $ParseJSONReceived, $ConnData);
        return $XMLresults;
    }

    public function processXML(DOMXPath $DOMXpath, string $XMLText, $InitialData)
    {
        $Log = null;
        $DeCSData = null;
        try {
            $Log = UltraLoggerFacade::createUltraLogger('DeCSIntegrationLooper', $InitialData);
            UltraLoggerFacade::InfoToUltraLogger($Log, 'Full URL: ' . $InitialData['url']);
            $LogData = UltraLoggerFacade::UltraLoggerAttempt($Log, 'Attempting to process data imported from BIREME');
            $statusNode = $DOMXpath->query("//decsvmx");
            if (!($statusNode) || (!count($statusNode))) {
                UltraLoggerFacade::ErrorToUltraLogger($Log, 'Wrong XML');
                Throw new ErrorInDeCSLoopXMLExtraction(['Wrong XML']);
            } else {
                UltraLoggerFacade::UltraLoggerSuccessfulAttempt($Log, $LogData);
            }

            $decswsResults = $DOMXpath->query("//decsws_response");
            if (!($decswsResults) || count($decswsResults) === 0) {
                UltraLoggerFacade::WarningToUltraLogger($Log, 'No results found for this key in this language');
                $DeCSData = 'no-res';
                return $DeCSData;
            }
            foreach ($decswsResults as $key => $ResultElement) {
                $result = $this->XMLDeCSRelatedAndDescendants($ResultElement, $XMLText, $DOMXpath, $Log);
                if ($result === -1) {
                    break;
                }
                $DeCSData[$key] = $result;
            }
        } catch (DontCatchException $ex) {
            //
        } finally {
            if ($DeCSData === 'no-res') {
                UltraLoggerFacade::WarningToUltraLogger($Log, 'No results built in this service. Returning "no-res"');
            }
            if ($Log) {
                UltraLoggerFacade::saveUltraLoggerResults($Log, !!($DeCSData), true);
            }
        }
        return $DeCSData;
    }
}
