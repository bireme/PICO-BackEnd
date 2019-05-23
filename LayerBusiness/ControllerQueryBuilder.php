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
        $results = json_decode($this->ObjectQuery->getPreviousResults(), true);
        $SelectedDescriptors = $this->ObjectQuery->getSelectedDescriptors();
        $ImproveSearchArr = $this->ObjectQuery->getImproveSearchArr();
        $fun = $this->CheckElements($SelectedDescriptors, $ImproveSearchArr);
        if ($fun) {
            return $fun;
        }
        $this->BuildEquation($results, $SelectedDescriptors);
        $this->ImproveSearch($ImproveSearchArr);
    }

    private function ImproveSearch($ImproveSearchArr) {
        $query = $this->ObjectQuery->getEquationNoImprovement();
        $result = $query;
        $this->ObjectQuery->setQuery($result);
    }

    private function BuildEquation($results, $SelectedDescriptors) {
        $msg = '';
        foreach ($results as $item) {
            $type = $item['type'];
            if ($type == 'key' || $type == 'keyrep') {
                $obj = $item['value'];
                $keyword = ucfirst($obj['keyword']);
                $Objkeyword = $obj['value'];
                $msgkeyword = $keyword;
                foreach ($Objkeyword as $tree_id => $ObjectDescriptor) {
                    if (!(in_array($tree_id, $SelectedDescriptors))) {
                        continue;
                    }
                    $tmp = '';
                    foreach ($ObjectDescriptor['DeCS'] as $DeCS) {
                        if (strpos($DeCS, ' ') !== false) {
                            $DeCS = '"' . $DeCS . '"';
                        }
                        if (strlen($tmp) > 0) {
                            $tmp = $tmp . ' OR ';
                        }
                        $tmp = $tmp .$DeCS;
                    }
                    if (strlen($tmp) > 0) {
                        $tmp = '(' . $tmp . ')';
                    }

                    if (strlen($msgkeyword) != 0) {
                        $msgkeyword = $msgkeyword . ' OR ';
                    }
                    $msgkeyword = $msgkeyword . $tmp;
                }
                if (strlen($msgkeyword) != 0) {
                    $msgkeyword = '(' . $msgkeyword . ')';
                }
                $msg = $msg . $msgkeyword;
            } else {
                $msg = $msg . $item['value'];
            }
        }
        if (strlen($msg) != 0) {
            $msg = '(' . $msg . ')';
        }
        $this->ObjectQuery->setEquationNoImprovement($msg);
    }

    private function CheckElements($SelectedDescriptors, $ImproveSearchArr) {
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