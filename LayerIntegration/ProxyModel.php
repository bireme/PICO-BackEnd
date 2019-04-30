<?php

namespace LayerIntegration;

use SimpleLife\SimpleLifeException;
use SimpleLife\SimpleLifeMessage;

abstract class ProxyModel {

    private $baseURL;
    private $POSTFields;
    private $resultdata;
    protected $timeSum;
    private $SimpleLifeMessage;

    protected function getBaseURL() {
        return $this->baseURL;
    }

    protected function getPOSTFields() {
        return $this->POSTFields;
    }

    protected function setBaseURL($baseURL) {
        $this->baseURL = $baseURL;
    }

    protected function setPOSTFields($txt) {
        $this->POSTFields = $txt;
    }

    public function getResultdata() {
        return $this->resultdata;
    }

    protected function setResultdata($resultdata) {
        $this->resultdata = $resultdata;
    }

    public function POSTRequest() {

        $timer = new \Simplelife\Timer();
        $POSTquery = http_build_query($this->getPOSTFields());
        $this->SimpleLifeMessage = new SimpleLifeMessage('(POST) Connecting to "' . $this->getBaseURL() . $POSTquery . '" : ');
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->getBaseURL());
        curl_setopt($ch, CURLOPT_POST, 1);
        $HeaderArr = array(
            'Content-Type: application/x-www-form-urlencoded'
        );
        curl_setopt($ch, CURLOPT_POSTFIELDS, $POSTquery);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $HeaderArr);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $result = curl_exec($ch);
        $time_elapsed_secs = $timer->Stop();
        $this->SimpleLifeMessage->Add('(' . $time_elapsed_secs . 'ms )');
        $this->timeSum->AddTime($time_elapsed_secs);
        $this->setResultdata(array('timer' => $time_elapsed_secs, 'result' => $result));
        try {
            if (curl_error($ch)) {
                throw new SimpleLifeException(new \SimpleLife\ProxyConnectionException(curl_error($ch), $this->getBaseURL(), $POSTquery));
            }
            $this->SimpleLifeMessage->SendAsLog();
            if (!($result and strlen($result) > 0)) {
                throw new SimpleLifeException(new \SimpleLife\ProxyDownloadException($this->getBaseURL(), $POSTquery));
            }
        } catch (SimpleLifeException $exc) {
            return $exc->PreviousUserErrorCode();
        } finally {
            curl_close($ch);
        }
    }

}

?>