<?php

////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////
///COPYRIGHT DANIEL NIETO-GONZALEZ--- USO LIBRE, CON MODIFICACIONES PERMITIDAS//
////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////

namespace SimpleLife;

require_once('SimpleLifeMessages.php');

use Exception;

class SimpleLifeException Extends Exception {

    private $showTracing;
    private $StopExcecution;
    private $ErrorCode;
    private $AlertLevel;
    private $debugvar = true;

    public function __construct($ExceptionType, SimpleLifeException $Previous = NULL) {
        if (is_string($ExceptionType)) {
            if ($ExceptionType == 'JustReturn') {
                $ExceptionParameters = array('code' => 100, 'AlertLevel' => 4, 'showTracing' => false, 'message' => '');
            }
        } else {
            ;
            try {
                $ExceptionParameters = $ExceptionType->getParameters();
            } catch (Exception $exc) {
                throw new Exception('First parameter of SimpleLifeException is not a existant SimpleExceptionTypeModel');
            }
        }
        $this->AlertLevel = $ExceptionParameters['AlertLevel'];
        $this->showTracing = $ExceptionParameters['showTracing'];
        $this->message = $ExceptionParameters['message'];
        $code = $ExceptionParameters['code'];
        $this->ErrorCode = $code;
        if (isset($Previous)) {
            $PreviousErrorCode = $Previous->PreviousUserErrorCode();
            if (!($PreviousErrorCode == 100)) {
                $this->ErrorCode = $PreviousErrorCode;
            }
        }
        $this->StopExcecution = false;
        if ($this->AlertLevel > 1) {
            $this->StopExcecution = true;
        }
        $this->SendErrorMessage();

        parent::__construct($this->message, $this->ErrorCode);
    }

    public function PreviousUserErrorCode() {
        return $this->ErrorCode;
    }

    private function SendErrorMessage() {
        if ((strlen($this->message) > 0) || $this->debugvar == true) {
            $msg = new SimpleLifeMessage($this->message);
            if ($this->showTracing == true or $this->debugvar == true) {
                $msg->AddAsNewLine('in ' . $this->getFile() . ':' . $this->getLine());
            }
            if ($this->StopExcecution) {
                $msg->SendAsError();
            } else {
                $msg->SendAsWarning();
            }
        }
    }

}

abstract class SimpleExceptionTypeModel {

    private $Parameters;

    public function getParameters() {
        return $this->Parameters;
    }

    private function ExceptionAlertTag() {
        $levels = ['', 'Warning', 'Error', 'Fatal Error', 'Stoping Execution'];
        if ($this->AlertLevel > 0 && $this->AlertLevel < 4) {
            $leveltag = $levels[$this->AlertLevel];
            return '(' . $leveltag . ' [' . $this->code . ']): ';
        }
    }

    protected function __construct(string $message, bool $showTracing) {
        if (strlen($message) > 0) {
            $message = $this->ExceptionAlertTag() . $message;
        }
        $this->Parameters = array('code' => $this->code, 'AlertLevel' => $this->AlertLevel, 'showTracing' => $showTracing, 'message' => $message);
    }

}

?>