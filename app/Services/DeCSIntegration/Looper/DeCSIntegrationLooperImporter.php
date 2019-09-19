<?php

namespace PICOExplorer\Services\DeCSIntegration\Looper;

use DOMXPath;
use PICOExplorer\Exceptions\Exceptions\AppError\DOMObjectNotFound;
use PICOExplorer\Services\ServiceModels\ParallelServiceEntryPointInterface;
use PICOExplorer\Services\ServiceModels\ToExternalSourceTrait;
use ServicePerformanceSV;

class DeCSIntegrationLooperImporter extends DeCSIntegrationLooperSupport implements ParallelServiceEntryPointInterface
{

    use ToExternalSourceTrait;

    protected final function Process(ServicePerformanceSV $ServicePerformance, $InitialData=null)
    {
        $headers = [];//['Content-Type' => 'application/x-www-form-urlencoded'];
        $sendAsJson=true;
        $ParseJSONReceived=false;
        $maxAttempts=3;
        $timeout=30;
        $fullURL= 'http://decs.bvsalud.org/cgi-bin/mx/cgi=@vmx/decs/?';
        $XMLresults = $this->Connect($ServicePerformance,'POST', $InitialData, $fullURL, $headers, $timeout, $maxAttempts, $sendAsJson, $ParseJSONReceived);
        if((!($XMLresults))){
            $XMLresults='none';
        }
        return $XMLresults;
    }

    public function processXML(DOMXPath $DOMXpath, string $XMLText)
    {
        $statusNode = $DOMXpath->query("//decsvmx");
        if (!($statusNode) || (!count($statusNode))) {
            throw new DOMObjectNotFound(['Controller' => __METHOD__ . '@' . get_class($this), 'XMLText' => $XMLText]);
        }
        $decswsResults = $DOMXpath->query("//decsws_response");
        if (!($decswsResults) || count($decswsResults)===0) {
            //throw new BadRequestInXML(['Controller' => __METHOD__ . '@' . get_class($this), 'XMLText' => $XMLText]);
        }
        $DeCSData = [];
        foreach($decswsResults as $key => $ResultElement){
            $DeCSData[$key] = $this->XMLDeCSRelatedAndDescendants($ResultElement, $XMLText, $DOMXpath);
        }
        return $DeCSData;
    }

}
