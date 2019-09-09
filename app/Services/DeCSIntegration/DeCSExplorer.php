<?php

namespace PICOExplorer\Services\DeCSIntegration;

abstract class DeCSExplorer extends DeCSImporter
{

    public function ExploreTreeId(string $key, bool $IsMainTree, array $langs = NULL)
    {
        if ($IsMainTree) {
            $this->ExploreMainKeywordByLang($key);
        } else {
            $this->ExploreTreeIdByLang($key, $langs);
        }
    }

///////////////////////////////////////////////////////////////////
//INNER FUNCTIONS
///////////////////////////////////////////////////////////////////

    private function ExploreMainKeywordByLang(string $key)
    {
        foreach ($this->attributes['AllLangs'] as $lang) {
            $this->Explore($key, $lang, true);
        }
    }

    private function ExploreTreeIdByLang(string $key, array $langs = NULL)
    {
        if (!(isset($langs))) {
            $langs = $this->DTO->getInitialData()['langs'];
        }
        foreach ($langs as $lang) {
            $this->Explore($key, $lang, false);
        }
    }

    private function Explore(string $key, string $lang, bool $IsMainTree)
    {
        $FirstArgument = 'tree_id';
        $mainref='';
        if ($IsMainTree == true) {
            $FirstArgument = 'words';
            $mainref=' [Main Tree]  -';
        }
        $query = [
            $FirstArgument => $key,
            'lang' => $lang,
        ];
        $TmpResults= $this->ImportDeCS($query);
        $referer='query='.$key.$mainref.__METHOD__ . '@' . get_class($this);
        $this->ProcessImportResults($TmpResults,$lang, $IsMainTree,$referer);
    }

}
