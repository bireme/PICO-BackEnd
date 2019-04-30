<?php

namespace LayerBusiness;

require_once(realpath(dirname(__FILE__)) . '/../SimpleLife/ManualExceptions.php');
require_once(realpath(dirname(__FILE__)) . '/../SimpleLife/SimpleLifeMessages.php');

use SimpleLife\SimpleLifeMessage;
use SimpleLife\SimpleLifeException;

class ControllerDeCSQueryProcessor {

    private $query;
    private $ObjectKeywordList;

    public function __construct($query, $ObjectKeywordList) {
        $this->query = $query;
        $this->ObjectKeywordList = $ObjectKeywordList;
    }

    public function BuildKeywordList() {
        $KeywordList = explode(' AND ', $this->query);
        $KeywordList = $this->CleanKeywordList($KeywordList);
        $this->ObjectKeywordList->AddKeywords($KeywordList);
    }

    private function CleanKeywordList($KeywordList) {
        $KeywordList = array_unique($KeywordList);
        $res = array();
        foreach ($KeywordList as $key => $value) {
            $value = trim($value);
            if (!(empty($value) || strlen($value) == 0)) {
                array_push($res, $value);
            }
        }
        return $res;
    }

}

?>