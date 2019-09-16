<?php

namespace PICOExplorer\Services\ServiceModels;

use DOMXPath;

interface PICOServiceExternalImporter
{

    public function ImportData(string $RequestMethod, array $data, string $fullURL, array $headers, float $timeout=30.0, int $maxAttempts=3,bool $sendAsJson = false, bool $ParseJSONReceived = false);

    public function getXMLResults(DOMXPath $DOMXpath, string $XMLText);

}
