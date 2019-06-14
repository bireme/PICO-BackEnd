<?php

namespace LayerBusiness;

require_once(realpath(dirname(__FILE__)) . '/../SimpleLife/ManualExceptions.php');
require_once(realpath(dirname(__FILE__)) . '/../SimpleLife/SimpleLifeMessages.php');

use SimpleLife\SimpleLifeMessage;
use SimpleLife\SimpleLifeException;

class ControllerQueryBuilder {

    private $ObjectQuery;
    private $SimpleLifeMessage;

    public function __construct($ObjectQuery) {
        $this->ObjectQuery = $ObjectQuery;
    }

    public function BuildnewQuery() {
        $results = $this->ObjectQuery->getPreviousResults();
        $QuerySplit = $this->ObjectQuery->getQuerySplit();
        $SelectedDescriptors = $this->ObjectQuery->getSelectedDescriptors();
        $ImproveSearchArr = $this->ObjectQuery->getImproveSearchArr();
        $fun = $this->CheckElements($SelectedDescriptors, $ImproveSearchArr, $QuerySplit);
        if ($fun) {
            return $fun;
        }
        $this->BuildEquation($results, $SelectedDescriptors, $QuerySplit);
        $this->ImproveSearch($ImproveSearchArr);
    }

    private function ImproveSearch($ImproveSearchArr) {
        $query = $this->ObjectQuery->getEquationNoImprovement();
        $result = $query;
        $this->ObjectQuery->setQuery($result);
    }

    private function BuildTermEquation($term) {
        $txt = '';
        foreach ($term as $DeCS) {
            if (strpos($DeCS, ' ') !== false) {
                $DeCS = '"' . $DeCS . '"';
            }
            if (strlen($txt) > 0) {
                $txt = $txt . ' OR ';
            }
            $txt = $txt . ucfirst($DeCS);
        }
        if (strlen($txt) > 0) {
            $txt = '(' . $txt . ')';
        }
        return $txt;
    }

    private function BuildKeyWordEquation($keyword, $SelectedDescriptors) {
        $msgkeyword = ucfirst($keyword);
        if (!(array_key_exists($keyword, $SelectedDescriptors))) {
            throw new SimpleLifeException(new \SimpleLife\KeywordNotExistsInSelected($keyword, $SelectedDescriptors));
        }
        $KeywordObj = $SelectedDescriptors[$keyword];
        foreach ($KeywordObj as $term) {

            if (strlen($msgkeyword) != 0) {
                $msgkeyword = $msgkeyword . ' OR ';
            }
            $msgkeyword = $msgkeyword . $this->BuildTermEquation($term);
        }
        if (strlen($msgkeyword) != 0) {
            $msgkeyword = '(' . $msgkeyword . ')';
        }
        return $msgkeyword;
    }

    private function BuildEquation($results, $SelectedDescriptors, $QuerySplit) {
        $msg = '';
        $Ops = array('or', 'and', 'not');
        $Seps = array('(', ')', ' ', ':');
        //$this->TestHub($results, $SelectedDescriptors, $QuerySplit);
        foreach ($QuerySplit as $item) {
            $type = $item['type'];
            $value = $item['value'];
            if ($type == 'key' || $type == 'keyrep' || $type == 'keyexplored') {
                $msg = $msg . $this->BuildKeyWordEquation($value, $SelectedDescriptors);
            } else {
                if (in_array($value, $Ops)) {
                    $value = strtoupper($value);
                } else {
                    if (!(in_array($value, $Seps))) {
                        $value = ucfirst($value);
                        if (strpos($value, ' ') !== false) {
                            $value = '"' . $value . '"';
                        }
                    }
                }
                $msg = $msg . $value;
            }
        }
        if (strlen($msg) != 0) {
            $msg = '(' . $msg . ')';
        }
        $this->ObjectQuery->setEquationNoImprovement($msg);
    }

    private function CheckElements($SelectedDescriptors, $ImproveSearchArr, $QuerySplit) {
        try {
            if (!($SelectedDescriptors)) {
                throw new SimpleLifeException(new \SimpleLife\NullSelectedDescriptors());
            }
            if (!(is_array($SelectedDescriptors))) {
                throw new SimpleLifeException(new \SimpleLife\SelectedDescriptorsNotArray());
            }
            if (count($SelectedDescriptors) == 0) {
                throw new SimpleLifeException(new \SimpleLife\NoItemsInSelectedDescriptors());
            }
            $openpar = 0;
            $closepar = 0;
            foreach ($ImproveSearchArr as $value) {
                if ($value['value'] == '(') {
                    $openpar = $openpar + 1;
                    continue;
                }
                if ($value['value'] == '(') {
                    $closepar = $closepar + 1;
                    continue;
                }
            }

            if ($openpar != $closepar) {
                throw new SimpleLifeException(new \SimpleLife\ParenthesesNumberNotMatch('Equation @ query builder', join($ImproveSearchArr)));
            }
        } catch (SimpleLifeException $exc) {
            return $exc->PreviousUserErrorCode();
        }
    }

}

?>