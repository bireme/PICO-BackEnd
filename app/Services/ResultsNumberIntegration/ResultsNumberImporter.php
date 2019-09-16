<?php

namespace PICOExplorer\Services\ResultsNumberIntegration;

use DOMXPath;
use PICOExplorer\Exceptions\Exceptions\AppError\BadRequestInXML;
use PICOExplorer\Exceptions\Exceptions\AppError\DOMObjectNotFound;
use PICOExplorer\Exceptions\Exceptions\AppError\XMLImportantElementNotFound;
use PICOExplorer\Facades\SpecialValidatorFacade;
use PICOExplorer\Services\ServiceModels\PICOServiceExternalImporter;
use PICOExplorer\Services\ServiceModels\ExternalImporter;
use Exception;

abstract class ResultsNumberImporter extends ExternalImporter implements PICOServiceExternalImporter
{

    public function ImportResultsNumber(array $data)
    {
        $results = [];
        $headers = ['Content-Type' => 'application/x-www-form-urlencoded'];
        $sendAsJson=true;
        $ParseJSONReceived=false;
        $maxAttempts=3;
        $timeout=30;
        $fullURL= 'https://pesquisa.bvsalud.org/portal/?';
        $results['ResultsNumber'] = $this->ImportData('POST', $data, $fullURL, $headers, $timeout, $maxAttempts, $sendAsJson, $ParseJSONReceived);
        $results['query'] = $data['q'];
        $results['ResultsURL'] = 'http://pesquisa.bvsalud.org/portal/?count=20&q='.$results['query'];
        return $results;
    }

    public function getXMLResults(DOMXPath $DOMXpath, string $XMLText)
    {
        $statusNode = $DOMXpath->query("*/int[@name='status']");
        if (!($statusNode) || (!count($statusNode))) {
            throw new DOMObjectNotFound(['Controller' => __METHOD__ . '@' . get_class($this), 'XMLText' => $XMLText]);
        }
        $status = $statusNode->item(0)->textContent;
        if ($status === '400') {
            throw new BadRequestInXML(['Controller' => __METHOD__ . '@' . get_class($this), 'XMLText' => $XMLText]);
        }
        $numFound=null;
        try {
            $resultNode = $DOMXpath->query("//result[@name='response'][1]/@numFound");
            $numFound=(int)$resultNode->item(0)->nodeValue;
        } catch (Exception $ex) {
            throw new XMLImportantElementNotFound(['Controller' => __METHOD__ . '@' . get_class($this), 'XMLText' => $XMLText, 'ElementNotFound' => 'numFound'], $ex);
        }
        return $numFound;
    }

}
