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

    private function ClearMessage() {
        $this->message = '';
    }

    private function MessageColor($color) {
        $this->message = '<font color="' . $color . '" >' . $this->message . '</font>';
    }

    public function SendAsLog() {
        $this->AddBefore('[L]');
        $this->HeadersAndPrefixes();
        //syslog($this->message);
        $this->MessageColor('black');
        echo $this->message;
        $this->ClearMessage();
    }

    public function SendAsWarning() {
        $this->AddBefore('[W]');
        $this->HeadersAndPrefixes();
        $this->MessageColor('orange');
//syslog($this->message);
        echo $this->message;
        $this->ClearMessage();
    }

    public function SendAsError() {
        $this->AddBefore('[E]');
        $this->HeadersAndPrefixes();
        //syslog($this->message);
        $this->MessageColor('red');
        echo $this->message;
        $this->ClearMessage();
    }

}
