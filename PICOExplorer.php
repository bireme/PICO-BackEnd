<?php

require_once(realpath(dirname(__FILE__)) . '/SimpleLife/SimpleLifeMessages.php');
require_once(realpath(dirname(__FILE__)) . '/SimpleLife/ManualExceptions.php');
require_once(realpath(dirname(__FILE__)) . '/SimpleLife/Timer.php');
require_once('FacadeResultsNumberObtainer.php');
require_once('FacadeQueryBuilder.php');
require_once('FacadeResultsNumberObtainer.php');

use SimpleLife\TimeSum;
use SimpleLife\Timer;
use SimpleLife\SimpleLifeException;
use SimpleLife\SimpleLifeMessage;

class PICOExplorer {

    private $ConnectionTimer;
    private $PICOExplorerTimer;
    private $SimpleLifeMessage;
    private $BussinessOperationSimpleLifeMessage;
    private $PICOExplorerHandler;
    private $data;

    public function __construct($data) {
        $this->ConnectionTimer = new TimeSum;
        $this->PICOExplorerTimer = new Timer();
        $this->BussinessOperationSimpleLifeMessage = new SimpleLifeMessage('Business Operation');
        $this->SimpleLifeMessage = new SimpleLifeMessage('PICO Explorer');
        $this->CheckData($data);
    }

    private function CheckData($data) {
        $this->data = json_decode($data, true);
        $this->SimpleLifeMessage->AddAsNewLine('Data received:'.json_encode($this->data));
    }

    private function ErrorInData() {
        if(!(is_array($this->data))){
            return;
        }
        if (array_key_exists('Error', $this->data)) {
            return $this->data['Error'];
        }
    }

    public function ExploreDeCS() {
        if ($fun = $this->ErrorInData()) {
            return $fun;
        }
        $this->PICOExplorerHandler = new FacadeDeCSObtainer($this->data, $this->ConnectionTimer, $this->BussinessOperationSimpleLifeMessage);
        $fun = $this->PICOExplorerHandler->BuildPICOExplorerResults();
        return $this->IntegrationResults($fun);
    }

    public function QueryBuild() {
        if ($fun = $this->ErrorInData()) {
            return $fun;
        }
        $this->PICOExplorerHandler = new FacadeQueryBuilder($this->data, $this->ConnectionTimer, $this->BussinessOperationSimpleLifeMessage);
        $fun = $this->PICOExplorerHandler->BuildPICOExplorerResults();
        return $this->IntegrationResults($fun);
    }

    public function ResultsNumber() {
        if ($fun = $this->ErrorInData()) {
            return $fun;
        }
        $this->PICOExplorerHandler = new FacadeResultsNumberObtainer($this->data, $this->ConnectionTimer, $this->BussinessOperationSimpleLifeMessage);
        $fun = $this->PICOExplorerHandler->BuildPICOExplorerResults();
        return $this->PICOExplorerResults($fun);
    }

    private function PICOExplorerResults($fun) {
        if ($fun) {
            $obj = array('Error' => $this->ErrorHandler($fun));
        } else {
            $obj = array('Data' => $this->PICOExplorerHandler->getPICOExplorerResults());
        }
        $this->PICOExplorerReport($obj);
        return json_encode($obj);
    }

    private function PICOExplorerReport($obj) {
        $time_elapsed = $this->PICOExplorerTimer->Stop();
        $timer =$this->ConnectionTimer->Stop();
        $this->SimpleLifeMessage->AddAsNewLine('Total Time: ' . $time_elapsed . ' ms');
        $this->SimpleLifeMessage->AddAsNewLine('Time Spent in connections: ' . $timer . ' ms');
        $this->SimpleLifeMessage->AddAsNewLine('Operational time: ' . ($time_elapsed - $timer) . ' ms');
        $this->SimpleLifeMessage->AddEmptyLine();
        $this->SimpleLifeMessage->AddAsNewLine('Info returned to user:');
        $this->SimpleLifeMessage->AddAsNewLine(json_encode($obj));
        $this->SimpleLifeMessage->SendAsLog();
        $this->BussinessOperationSimpleLifeMessage->sendAsLog();
    }

    private function ErrorHandler($fun) {
        return '[Error ' . $this->ErrorCode . ']Este error se traducirá y se mostrará si es relevante para el usuario';
    }

}

?>