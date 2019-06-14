<?php

namespace LayerBusiness;

require_once(realpath(dirname(__FILE__)) . '/../InterfaceIntegration.php');
require_once(realpath(dirname(__FILE__)) . '/../SimpleLife/ManualExceptions.php');
require_once(realpath(dirname(__FILE__)) . '/../SimpleLife/SimpleLifeMessages.php');

use SimpleLife\SimpleLifeMessage;
use InterfaceIntegration;
use SimpleLife\SimpleLifeException;

class ControllerDeCSHandler {

    private $ObjectKeyWord;

    public function __construct($ObjectKeyWord) {
        $this->ObjectKeyWord = $ObjectKeyWord;
    }

    public function retrieveDeCS($MaxImports) {
        $this->checkKeywords();

        $ObjectKeywordData = array(
            'keyword' => $this->ObjectKeyWord->getKeyword(),
            'langs' => $this->ObjectKeyWord->getLang(),
            'MaxImports' => $MaxImports
        );
        $Data = array(
            'caller' => 'main',
            'ObjectKeywordData' => $ObjectKeywordData
        );

        $info = 'Keyword= ' . $this->ObjectKeyWord->getKeyword() . ' Langs=' . json_encode($this->ObjectKeyWord->getLang());
        $DeCSExplorer = new InterfaceIntegration('[Integration Report] ' . $info);
        return $this->ProcessResults($DeCSExplorer->ImportDeCS(json_encode($Data)));
    }

    private function ProcessResults($results) {
        $results = json_decode($results, true);
        if (array_key_exists('Error', $results)) {
            try {
                $Error = $results['Error'];
                throw new SimpleLifeException(new \SimpleLife\PreviousIntegrationException($Error));
            } catch (SimpleLifeException $Ex) {
                return $Ex->PreviousUserErrorCode();
            }
        } else {
            $this->ObjectKeyWord->setBaseData($results['Data']);
        }
    }

    private function checkKeywords() {
        try {
            if (strlen($this->ObjectKeyWord->getKeyword()) == 0) {
                throw new SimpleLifeException(new \SimpleLife\EmptyKeyword());
            }
            $MaximumQuerySize = 5000;
            if (strlen($this->ObjectKeyWord->getKeyword()) > $MaximumQuerySize) {
                throw new SimpleLifeException(new \SimpleLife\KeywordTooLarge(strlen($keyword), $MaximumQuerySize));
            }
        } catch (SimpleLifeException $Ex) {
            return $Ex->PreviousUserErrorCode();
        }
    }

}

?>