<?php

namespace LayerBusiness;

class ControllerQueryBuilder {

    public function BuildFinalQuery($DeCSKeywordList) {

        if (!isset($DeCSKeywordList)) {
            throw new SimpleLifeException(new \SimpleLife\NullDeCSKeyWordList());
        }
        $msg = '(';

        foreach ($DeCSKeywordList as $DeCSKeyword) {
            $CurrentKeyword = $DeCSKeyword;
            $msg = $msg . '(' . join($DeCSKeyword, ' OR ') . ')';
        }
        $msg = $msg . ')';
        return $msg;
    }

}

?>