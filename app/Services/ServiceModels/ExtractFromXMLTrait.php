<?php

namespace PICOExplorer\Services\ServiceModels;

use PICOExplorer\Exceptions\InternalErrors\XMLProcessingError;
use \DOMDocument;

trait ExtractFromXMLTrait
{

    /**
     * @var \DOMDocument;
     */
    protected $DOM;

    protected function getXMLResults(array $arguments=null)
    {
        $results = $this->ExploreXML($arguments);
        $ex = $this->ValidateData($results,$this->XMLResultsRules);
        if($ex){
            throw new XMLProcessingError(null,$ex);
        }
        return $results;
    }

    protected function ProcessXML($XML,array $arguments=null)
    {
        $this->LoadXML($XML);
        return $this->getXMLResults($arguments);
    }

    protected function LoadXML($XML)
    {
        $this->DOM = new DOMDocument();
        $this->DOM->loadxml($XML);
    }

}
