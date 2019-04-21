<?php

namespace LayerIntegration;

require_once(realpath(dirname(__FILE__)) . '/../LayerIntegration/ProxyReceiveResultsNumber.php');
require_once(realpath(dirname(__FILE__)) . '/../LayerIntegration/ControllerImportModel.php');

use LayerIntegration\ProxyReceiveResultsNumber;
use LayerIntegration\ControllerImportModel;
use \DOMDocument;

class ControllerImportResultsNumber extends ControllerImportModel {

    public function InnerObtain($params) {
        $query = urlencode($params["query"]);
        $MaximumQuerySize=5000;
        if (strlen($query) == 0) {
            $Ex = new EmptyQuery();
            throw new IntegrationExceptions($Ex->build());
        }
        if (strlen($query) > $MaximumQuerySize) {
            $Ex = new QueryTooLarge(strlen($query),$MaximumQuerySize);
            throw new IntegrationExceptions($Ex->build());
        }

        try {
            $XMLObject = new ProxyReceiveResultsNumber($query);
            $XMLObject->POSTRequest();
        } catch (IntegrationExceptions $exc) {
            if ($exc->HandleError()) {
                $Ex = new JustReturnException();
                throw new IntegrationExceptions($Ex->build(),$exc->PreviousUserErrorCode());
            }
        } finally {
            $result = $XMLObject->getResultdata();
            $this->addTimer($result['timer']);
        }

        $this->setXMLObject($result = $result['result']);

        try {
            $this->ExtractFromXML($query);
        } catch (IntegrationExceptions $exc) {
            if ($exc->HandleError()) {
                $Ex = new JustReturnException();
                throw new IntegrationExceptions($Ex->build(),$exc->PreviousUserErrorCode());
            }
        }

        unset($XMLObject);
    }

    private function ExtractFromXML($query) {
        $dom = new DOMDocument();
        $xml = $this->getXMLObject();

        if (is_array($xml)) {
            if (array_key_exists('Error', $xml)) {
                $Ex = new ErrorTagInXML($query);
                throw new IntegrationExceptions($Ex->build());
            }
        }

        try {
            $dom->loadxml($xml);
        } catch (Exception $exc) {
            $Ex = new XMLLoadException($exc->ToString());
            throw new IntegrationExceptions($Ex->build());
        }
        if($dom->getElementsByTagName('response')->length == 0){
            $Ex = new XMLNotBiremeType();
            throw new IntegrationExceptions($Ex->build());
        }

        $result = $dom->getElementsByTagName('result');
        if ($result->length == 0) {
            $Ex = new NoResultsInXMLException($query);
            throw new IntegrationExceptions($Ex->build());
        }
        $FinalArr = $result->item(0)->getAttribute('numFound');
        $this->setResultVar($FinalArr);
    }

}

?>