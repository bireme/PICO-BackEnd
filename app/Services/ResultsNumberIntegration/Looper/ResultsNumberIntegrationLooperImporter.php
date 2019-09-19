<?php

namespace PICOExplorer\Services\ResultsNumberIntegration\Looper;

use DOMXPath;
use PICOExplorer\Exceptions\Exceptions\AppError\BadRequestInXML;
use PICOExplorer\Exceptions\Exceptions\AppError\DOMObjectNotFound;
use PICOExplorer\Exceptions\Exceptions\AppError\XMLImportantElementNotFound;
use Exception;
use PICOExplorer\Services\ServiceModels\ParallelServiceEntryPoint;
use PICOExplorer\Services\ServiceModels\ParallelServiceEntryPointInterface;
use PICOExplorer\Services\ServiceModels\ToExternalSourceTrait;
use ServicePerformanceSV;

class ResultsNumberIntegrationLooperImporter extends ParallelServiceEntryPoint implements ParallelServiceEntryPointInterface
{

    use ToExternalSourceTrait;

    protected final function Process(ServicePerformanceSV $ServicePerformance, $InitialData=null)
    {
        $headers = ['Content-Type' => 'application/x-www-form-urlencoded'];
        $sendAsJson = true;
        $ParseJSONReceived = false;
        $maxAttempts = 3;
        $timeout = 30;
        $fullURL = 'https://pesquisa.bvsalud.org/portal/?';
        $RequestMethod='POST';
        $XMLresults = $this->Connect($ServicePerformance,$RequestMethod, $InitialData, $fullURL, $headers, $timeout, $maxAttempts, $sendAsJson, $ParseJSONReceived);
        return $XMLresults;
    }

    protected final function processXML(DOMXPath $DOMXpath, string $XMLText)
    {
        $statusNode = $DOMXpath->query("*/int[@name='status']");
        if (!($statusNode) || (!count($statusNode))) {
            throw new DOMObjectNotFound(['Controller' => __METHOD__ . '@' . get_class($this), 'XMLText' => $XMLText]);
        }
        $status = $statusNode->item(0)->textContent;
        if ($status === '400') {
            throw new BadRequestInXML(['Controller' => __METHOD__ . '@' . get_class($this), 'XMLText' => $XMLText]);
        }
        $numFound = null;
        try {
            $resultNode = $DOMXpath->query("//result[@name='response'][1]/@numFound");
            $numFound = (int)$resultNode->item(0)->nodeValue;
        } catch (Exception $ex) {
            throw new XMLImportantElementNotFound(['Controller' => __METHOD__ . '@' . get_class($this), 'XMLText' => $XMLText, 'ElementNotFound' => 'numFound'], $ex);
        }
        return $numFound;
    }

}
