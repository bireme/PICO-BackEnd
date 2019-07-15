<?php

namespace PICOExplorer\Services\DeCSIntegration;


class DeCSBIREMECoordinator extends ExtractDeCSFromXML
{

    protected $AllLangs = array('en', 'pt', 'es');

    protected function ExploreTreeId($key, $IsMainTree, $ExploredTrees, &$results, $langs = NULL)
    {
        $langs=$IsMainTree?($this->AllLangs):($langs ?? $this->model->langs);
        foreach ($langs as $lang) {
            $this->ExploreTreeIdOneLang($key, $lang, $IsMainTree, $ExploredTrees, $results);
        }
    }

///////////////////////////////////////////////////////////////////
//INNER FUNCTIONS
///////////////////////////////////////////////////////////////////

    private function ExploreTreeIdOneLang($key, $lang, $IsMainTree, $ExploredTrees, &$results)
    {
        $XML = $this->ProxyDeCS($key, $lang, $IsMainTree);
        $results = $this->getXMLResults(['XML' => $XML, 'lang' => $lang, 'ExploredTrees' => $ExploredTrees, '$results' => $results]);
    }

}
