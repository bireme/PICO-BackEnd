<?php

namespace LayerIntegration;

/**
 * @access public
 * @author Daniel Nieto
 * @package LayerIntegration
 */
abstract class ProxyModel {

    /**
     * @AttributeType string
     */
    private $baseURL;

    /**
     * @AttributeType int
     */
    private $POSTFields;
    private $resultdata;

    /**
     * 
     * 
     * @return type
     * @access private
     * @return JSON
     * @ReturnType JSON
     */
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

    /**
     * @access private
     * @return XML
     * @ReturnType XML
     */
    protected function GETRequest() {
        //API URL
//create a new cURL resource
        echo '</br>Connecting to "' . $this->getBaseURL() . ' --> ' . $this->getPOSTFields() . '" : ';
        $result = file_get_contents($this->getBaseURL() . $this->getPOSTFields());
        if ($result and strlen($result) > 0) {
            echo "(" . strlen($result) . " chars) </br>";
            $this->setResultdata($result);
        } else {
            echo "error in GET request";
        }
    }

    protected function POSTRequest() {
        //API URL
//create a new cURL resource
        $POSTquery = http_build_query($this->getPOSTFields());

        echo '</br>(POST) Connecting to "' . $this->getBaseURL() . $POSTquery . '" : ';
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
        curl_close($ch);

// further processing ....
//if ($server_output == "OK") { ... } else { ... }

        if ($result and strlen($result) > 0) {
            echo "(" . strlen($result) . " chars) </br>";
            $this->setResultdata($result);
        } else {
            echo "cURL error " . curl_strerror(curl_errno($ch));
            $this->setResultdata(Strval(-1));
        }
    }

}

?>