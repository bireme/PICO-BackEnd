<?php

namespace PICOExplorer\Services\ServiceModels;

use DOMXPath;

interface PICOServiceExternalImporter
{

    public function ImportData(string $RequestMethod, array $data, string $url, array $headers = null, array $options = null, bool $sendAsJson = false, bool $ParseJSONReceived = false);

    public function getXMLResults(DOMXPath $DOMXpath, string $XMLText);

}
