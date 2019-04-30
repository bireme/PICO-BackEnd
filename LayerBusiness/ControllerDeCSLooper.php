<?php

namespace LayerBusiness;

require_once(realpath(dirname(__FILE__)) . '/../LayerBusiness/ControllerDeCSHandler.php');
require_once(realpath(dirname(__FILE__)) . '/../SimpleLife/ManualExceptions.php');
require_once(realpath(dirname(__FILE__)) . '/../SimpleLife/SimpleLifeMessages.php');
require_once(realpath(dirname(__FILE__)) . '/../LayerEntities/ObjectKeyword.php');

use SimpleLife\SimpleLifeMessage;
use SimpleLife\SimpleLifeException;

class ControllerDeCSLooper {

    private $ObjectKeywordList;
    private $SimpleLifeMessage;

    public function __construct($ObjectKeywordList) {
        $this->ObjectKeywordList = $ObjectKeywordList;
        $this->SimpleLifeMessage = new SimpleLifeMessage('DeCS Manager');
    }

    public function BuildDeCSList() {
        $fun = $this->CheckLangsAndKeywords();
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

    private function CheckLangsAndKeywords() {
        $langArr = $this->ObjectKeywordList->getLang();
        try {
            if (is_array($langArr) == false) {
                throw new SimpleLifeException(new \SimpleLife\LangArrNotArray());
            }

            if (count($langArr) == 0) {
                throw new SimpleLifeException(new \SimpleLife\NoArrLanguages());
            }
            foreach ($langArr as $lang) {
                if (!($lang == "es" || $lang == "en" || $lang == "pt")) {
                    throw new SimpleLifeException(new \SimpleLife\UnrecognizedLanguage($lang));
                }
            }

            if (count($this->ObjectKeywordList->getKeywordList()) == 0) {
                throw new SimpleLifeException(new \SimpleLife\NoKeywords());
            }
        } catch (SimpleLifeException $exc) {
            return $exc->PreviousUserErrorCode();
        }
    }
}

?>