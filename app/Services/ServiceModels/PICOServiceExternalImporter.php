<?php

namespace PICOExplorer\Services\ServiceModels;

use DOMXPath;

interface PICOServiceExternalImporter
{

    public function ImportData(string $RequestMethod, array $data, string $url, array $headers = [], array $settings = [],int $timeout=500, int $maxAttempts=3,bool $sendAsJson = false, bool $ParseJSONReceived = false);

    public function getXMLResults(DOMXPath $DOMXpath, string $XMLText);

}
