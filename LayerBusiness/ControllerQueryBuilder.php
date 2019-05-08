<?php

namespace LayerBusiness;

require_once(realpath(dirname(__FILE__)) . '/../SimpleLife/ManualExceptions.php');
require_once(realpath(dirname(__FILE__)) . '/../SimpleLife/SimpleLifeMessages.php');
require_once(realpath(dirname(__FILE__)) . '/../LayerEntities/ObjectQuery.php');

use SimpleLife\SimpleLifeMessage;
use SimpleLife\SimpleLifeException;

class ControllerQueryBuilder {

    private $ObjectQuery;
    private $SimpleLifeMessage;

    public function __construct($ObjectQuery) {
        $this->ObjectQuery = $ObjectQuery;
        $this->SimpleLifeMessage = new SimpleLifeMessage('Query Builder Manager');
    }

    public function BuildnewQuery($SelectedDescriptors, $ImproveSearch) {
        $fun = $this->CheckElements($SelectedDescriptors, $ImproveSearch);
        if ($fun) {
            return $fun;
        }
        $DeCSList = array();

        foreach ($this->ObjectKeywordList->getKeywordList() as $ObjectKeyword) {
            $obj = new ControllerDeCSHandler($ObjectKeyword);
            $fun = $obj->getDeCS();
            if ($fun) {
                return $fun;
            }
        }

        $this->ReportMessage();
    }

    private function ReportMessage() {
        $this->ObjectKeywordList->KeyWordListInfo($this->SimpleLifeMessage);
        $this->SimpleLifeMessage->SendAsLog();
    }

    private function CheckElements($SelectedDescriptors, $ImproveSearch) {
        $langArr = $this->ObjectKeywordList->getLang();
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
            foreach ($SelectedDescriptors as $item) {
                if (!($item)) {
                    throw new SimpleLifeException(new \SimpleLife\NullItemInSelectedDescriptors());
                }
                if (!(is_array($item))) {
                    throw new SimpleLifeException(new \SimpleLife\ItemInSelectedDescriptorsNotArray());
                }
                if (count($item) == 0) {
                    throw new SimpleLifeException(new \SimpleLife\NoItemsInItemInSelectedDescriptor());
                }
            }
            if (substr_count($ImproveSearch,'(')!=substr_count($ImproveSearch,'(')) {
                throw new SimpleLifeException(new \SimpleLife\NullImproveSearch());
            }
            
            
        } catch (SimpleLifeException $exc) {
            return $exc->PreviousUserErrorCode();
        }
    }

}

?>