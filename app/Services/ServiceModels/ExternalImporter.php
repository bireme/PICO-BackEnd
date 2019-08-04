<?php

namespace PICOExplorer\Services\ServiceModels;

use DOMDocument;
use Exception;
use DOMXPath;
use PICOExplorer\Exceptions\Exceptions\AppError\XMLDOMLoadingError;
use PICOExplorer\Exceptions\Exceptions\AppError\XMLDOMXPathError;
use PICOExplorer\Exceptions\Exceptions\AppError\XMLProcessingError;

abstract class ExternalImporter extends GuzzleProxyRequest
{

    public function ImportData(string $RequestMethod, array $data, string $url, array $headers = null, array $options = null, bool $sendAsJson = false, bool $ParseJSONReceived = false)
    {
        $imported = $this->MakeRequest($RequestMethod, $data, $url, $headers, $options, $sendAsJson, $ParseJSONReceived);
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
