<?php

namespace LayerIntegration;

use SimpleLife\SimpleLifeException;
use SimpleLife\SimpleLifeMessage;

abstract class ProxyModel {

    private $baseURL;
    private $POSTFields;
    protected $timeSum;
    private $SimpleLifeMessage;

    protected function setBaseURL($baseURL) {
        $this->baseURL = $baseURL;
    }

    protected function setPOSTFields($txt) {
        $this->POSTFields = $txt;
    }

    public function POSTRequest(&$Result) {

        $timer = new \Simplelife\Timer();
        $POSTquery = http_build_query($this->POSTFields);
        $this->SimpleLifeMessage = new SimpleLifeMessage('(POST) Connecting to "' .$this->baseURL . $POSTquery . '" : ');
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
        $obj=array('timer' => $time_elapsed_secs, 'data' => $data);
        $Result=$obj;
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