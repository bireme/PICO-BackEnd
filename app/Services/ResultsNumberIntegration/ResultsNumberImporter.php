<?php

namespace PICOExplorer\Services\ResultsNumberIntegration;

use DOMXPath;
use PICOExplorer\Exceptions\Exceptions\AppError\BadRequestInXML;
use PICOExplorer\Exceptions\Exceptions\AppError\DOMObjectNotFound;
use PICOExplorer\Exceptions\Exceptions\AppError\XMLImportantElementNotFound;
use PICOExplorer\Services\AdvancedLogger\Traits\SpecialValidator;
use PICOExplorer\Services\ServiceModels\PICOServiceExternalImporter;
use PICOExplorer\Services\ServiceModels\ExternalImporter;
use Exception;

abstract class ResultsNumberImporter extends ExternalImporter implements PICOServiceExternalImporter
{

    use SpecialValidator;

    public function ImportResultsNumber(array $data)
    {
        $url = 'http://pesquisa.bvsalud.org/portal/?';
        $results = [];
        $results['ResultsNumber'] = $this->ImportData('POST', $data, $url, null, null, false, false);
        $results['query'] = $data['q'];
        $results['ResultsURL'] = 'http://pesquisa.bvsalud.org/portal/?count=20&q='.$results['query'];
        $ImportedDataRules = [
            'ResultsURL' => 'string|required|min:1',
            'query' => 'string|required|min:1',
            'ResultsNumber' => 'integer|required'
        ];
        $this->SpecialValidate($results, $ImportedDataRules, 'ImportResultsNumber@ResultsNumberImporter', 'ResultsNumberImporter');
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
