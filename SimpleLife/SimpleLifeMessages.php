<?php

////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////
///COPYRIGHT DANIEL NIETO-GONZALEZ--- USO LIBRE, CON MODIFICACIONES PERMITIDAS//
////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////

namespace SimpleLife;

class SimpleLifeConfig {

    public function __construct($timezone) {
        date_default_timezone_set($timezone);
    }

    protected function getTimeStamp() {
        return '[' . date('d/m/y H:i', time()) . ']';
    }

}

class SimpleLifeMessage Extends SimpleLifeConfig {

    private $prefix;
    private $message;
    Private $LineOperator = '</br>'; //PHP.EOL;

    public function __construct(string $txt = '', $prefix = '') {
        $this->prefix = $prefix;
        $this->message = $this->getTimeStamp() . $txt;
    }

    private function HeadersAndPrefixes() {
        $inisep = '---------------------------------';
        $msg = $this->LineOperator . $inisep . $this->LineOperator;
        $this->message = $msg . $this->message;
        $this->message = str_replace($this->LineOperator, $this->LineOperator . $this->prefix, $this->message);
    }

    public function Add(string $txt) {
        $this->message = $this->message . $txt;
    }

    public function AddBefore(string $txt) {
        $this->message = $txt . $this->message;
    }

    public function AddAsNewLine(string $txt) {
        $this->message = $this->message . $this->LineOperator . $txt;
    }

    public function AddEmptyLine() {
        $this->message = $this->message . $this->LineOperator;
    }

    public function AddSeparatorLine(int $length = 1) {
        $msg = '';
        if ($length < 1) {
            $length = 1;
        }
        if ($length > 3) {
            $length = 3;
        }
        while ($length > 0) {
            $msg = $msg . '---------------------------------';
            $length--;
        }

        $this->message = $this->message . $this->LineOperator . $msg;
    }

    private function MessageColor($color) {
        $this->message = '<font color="' . $color . '" >' . $this->message . '</font>';
    }

    public function SaveToLog($prefix, $color,$isError) {
        $this->AddBefore($prefix);
        $this->HeadersAndPrefixes();
        $this->MessageColor($color);
        $prefix='log';
        if($isError==true){
            $prefix='error';
        }
        file_put_contents('./public_html/var/log/'.$prefix.'/' . date("d-m-y") . '.html', $this->message, FILE_APPEND);
    }

    public function SendAsLog() {
        $this->SaveToLog('[L]', 'black',false);
    }

    public function SendAsWarning() {
        $this->SaveToLog('[W]', 'orange',true);
    }

    public function SendAsError() {
        $this->SaveToLog('[E]', 'red',true);
    }

}
