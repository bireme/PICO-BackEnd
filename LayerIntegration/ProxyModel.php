<?php

namespace LayerIntegration;

require_once 'vendor/autoload.php';
require_once(realpath(dirname(__FILE__)) . '/../LayerIntegration/IntegrationExceptions.php');

abstract class ProxyModel {

    private $baseURL;
    private $POSTFields;
    private $resultdata;

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
        $start = microtime(true);

        $POSTquery = http_build_query($this->getPOSTFields());
        echo '</br></br>(POST) Connecting to "' . $this->getBaseURL() . $POSTquery . '" : ';
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
        
        $time_elapsed_secs = (int)((microtime(true) - $start)*1000);
        echo ' ('. $time_elapsed_secs .' ms)';
        $this->setResultdata(array('timer'=>$time_elapsed_secs,'result'=>$result));
        
        if (curl_error($ch)) {
            $Ex = new ProxyConnectionException(curl_error($ch), $this->getBaseURL(), $POSTquery);
            throw new IntegrationExceptions($Ex->build());
        }
        curl_close($ch);        

        if (!($result and strlen($result) > 0)) {
            $Ex = new ProxyDownloadException($this->getBaseURL(), $POSTquery);
            throw new IntegrationExceptions($Ex->build());
        }
        
    }

}

?>