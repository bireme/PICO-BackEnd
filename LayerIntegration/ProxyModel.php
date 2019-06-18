<?php

namespace LayerIntegration;

use SimpleLife\SimpleLifeException;
use SimpleLife\SimpleLifeMessage;

abstract class ProxyModel {

    private $baseURL;
    private $POSTFields;
    protected $timeSum;
    private $SimpleLifeMessage;

    ///////////////////////////////////////////////////////////////////
    //PUBLIC FUNCTIONS
    /////////////////////////////////////////////////////////////////// 

    public function POSTRequest(&$Result) {
        return $this->PerformPOSTRequest($Result);
    }

    ///////////////////////////////////////////////////////////////////
    //INNER FUNCTIONS
    /////////////////////////////////////////////////////////////////// 

    protected function setBaseURLAndTimer($baseURL, &$timeSum) {
        $this->baseURL = $baseURL;
        $this->timeSum = $timeSum;
    }

    protected function setPOSTFields($txt) {
        $this->POSTFields = $txt;
    }

    private function PerformPOSTRequest(&$Result) {
        $timer = new \Simplelife\Timer();
        $POSTquery = http_build_query($this->POSTFields);
        $this->SimpleLifeMessage = new SimpleLifeMessage('(POST) Connecting to "' . $this->baseURL . $POSTquery . '" : ');
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->baseURL);
        curl_setopt($ch, CURLOPT_POST, 1);
        $HeaderArr = array(
            'Content-Type: application/x-www-form-urlencoded'
        );
        curl_setopt($ch, CURLOPT_POSTFIELDS, $POSTquery);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $HeaderArr);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $data = curl_exec($ch);
        $time_elapsed_secs = $timer->Stop();
        $this->SimpleLifeMessage->Add('(' . $time_elapsed_secs . 'ms )');
        $this->timeSum->AddTime($time_elapsed_secs);
        $Result = $data;
        try {
            if (curl_error($ch)) {
                throw new SimpleLifeException(new \SimpleLife\ProxyConnectionException(curl_error($ch), $this->baseURL, $POSTquery));
            }
            $this->SimpleLifeMessage->SendAsLog();
            if (!($data and strlen($data) > 0)) {
                throw new SimpleLifeException(new \SimpleLife\ProxyDownloadException($this->baseURL, $POSTquery));
            }
        } catch (SimpleLifeException $exc) {
            return $exc->PreviousUserErrorCode();
        } finally {
            curl_close($ch);
        }
    }

}

?>