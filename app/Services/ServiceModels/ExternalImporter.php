<?php

namespace PICOExplorer\Services\ServiceModels;

use DOMDocument;
use Exception;
use DOMXPath;
use PICOExplorer\Exceptions\Exceptions\AppError\XMLDOMLoadingError;
use PICOExplorer\Exceptions\Exceptions\AppError\XMLDOMXPathError;
use PICOExplorer\Exceptions\Exceptions\AppError\XMLProcessingError;

abstract class ExternalImporter extends cURLRequest
{

    public function ImportData(string $RequestMethod, array $data, string $fullURL, array $headers, float $timeout=30.0, int $maxAttempts=3,bool $sendAsJson = false, bool $ParseJSONReceived = false)
    {
        $imported =$this->MakeRequest($RequestMethod, $data, $fullURL, $headers, $timeout, $maxAttempts, $sendAsJson, $ParseJSONReceived);
        $results = null;
        $XMLText=null;
        $DOM = new DOMDocument();
        try {
            $DOM->loadXML($imported);
        } catch (Exception $ex) {
            throw new XMLDOMLoadingError(['Imported' => $imported], $ex);
        }
        try {
            $DOMXpath = new DOMXPath($DOM);
        } catch (Exception $ex) {
            throw new XMLDOMXPathError(['Imported' => $imported], $ex);
        }
        try {
            $XMLText=$DOM->saveXML();
            $results = $this->getXMLResults($DOMXpath,$XMLText);
            return $results;
        } catch (Exception $ex) {
            throw new XMLProcessingError(['Imported' => $imported], $ex);
        }
    }

    abstract public function getXMLResults(DOMXPath $DOMXpath, string $XMLText);
}
