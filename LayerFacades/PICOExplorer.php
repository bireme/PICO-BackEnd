<?php

require_once(realpath(dirname(__FILE__)) . '/../SimpleLife/SimpleLifeMessages.php');
require_once(realpath(dirname(__FILE__)) . '/../SimpleLife/ManualExceptions.php');
require_once(realpath(dirname(__FILE__)) . '/../SimpleLife/Timer.php');
require_once('FacadeResultsNumberObtainer.php');
require_once('FacadeQueryBuilder.php');
require_once('FacadeDeCSObtainer.php');

use SimpleLife\TimeSum;
use SimpleLife\Timer;
use SimpleLife\SimpleLifeException;
use SimpleLife\SimpleLifeMessage;

class PICOExplorer {

    private $ConnectionTimer;
    private $PICOExplorerTimer;
    private $SimpleLifeMessage;
    private $OperationSimpleLifeMessage;
    private $PICOExplorerHandler;
    private $data;

    public function __construct($data) {
        $this->ConnectionTimer = new TimeSum;
        $this->PICOExplorerTimer = new Timer();
        $this->OperationSimpleLifeMessage = new SimpleLifeMessage('Business Operation');
        $this->SimpleLifeMessage = new SimpleLifeMessage('PICO Explorer');
        $this->CheckData($data);
    }

    ///////////////////////////////////////////////////////////////////
    //PUBLIC FUNCTIONS
    ///////////////////////////////////////////////////////////////////

    public function ExploreDeCS() {
        if (!($fun = $this->ErrorInData())) {
            $this->PICOExplorerHandler = new FacadeDeCSObtainer($this->data, $this->ConnectionTimer, $this->OperationSimpleLifeMessage);
        }
        return $this->BuildAndGetResults($fun);
    }

    public function QueryBuild() {
        if (!($fun = $this->ErrorInData())) {
            $this->PICOExplorerHandler = new FacadeQueryBuilder($this->data, $this->ConnectionTimer, $this->OperationSimpleLifeMessage);
        }

        return $this->BuildAndGetResults($fun);
    }

    public function ResultsNumber() {
        if (!($fun = $this->ErrorInData())) {
            $this->PICOExplorerHandler = new FacadeResultsNumberObtainer($this->data, $this->ConnectionTimer, $this->OperationSimpleLifeMessage);
        }
        return $this->BuildAndGetResults($fun);
    }

    ///////////////////////////////////////////////////////////////////
    //INNER FUNCTIONS
    ///////////////////////////////////////////////////////////////////

    private function BuildAndGetResults($fun) {
        return $this->PICOExplorerResults($fun || $this->PICOExplorerHandler->BuildPICOExplorerResults());
    }

    private function PICOExplorerResults($fun) {
        if ($fun) {
            $obj = array('Error' => $this->ErrorHandler($fun));
        } else {
            $obj = array('Data' => $this->PICOExplorerHandler->getPICOExplorerResults());
        }
        $this->PICOExplorerReport(json_decode(json_encode($obj), true));
        return json_encode($obj);
    }

    private function CheckData($data) {
        $this->data = json_decode($data, true);
        $this->SimpleLifeMessage->AddAsNewLine('Data received:' . json_encode($this->data));
    }

    private function ErrorInData() {
        if (!((!(is_array($this->data))) ?: (!(array_key_exists('Error', $this->data))))) {
            return $this->data['Error'];
        }
    }

    private function PICOExplorerReport($obj) {
        if (!((!(array_key_exists('Data', $obj))) ?: (!(array_key_exists('HTMLDescriptors', $obj['Data']))))) {
            $obj['Data']['HTMLDescriptors'] = strlen($obj['Data']['HTMLDescriptors']) . ' chars';
            $obj['Data']['HTMLDeCS'] = strlen($obj['Data']['HTMLDeCS']) . ' chars';
        }
        $time_elapsed = $this->PICOExplorerTimer->Stop();
        $timer = $this->ConnectionTimer->Stop();
        $this->SimpleLifeMessage->AddAsNewLine('Total Time: ' . $time_elapsed . ' ms');
        $this->SimpleLifeMessage->AddAsNewLine('Time Spent in connections: ' . $timer . ' ms');
        $this->SimpleLifeMessage->AddAsNewLine('Operational time: ' . ($time_elapsed - $timer) . ' ms');
        $this->SimpleLifeMessage->AddEmptyLine();
        $this->SimpleLifeMessage->AddAsNewLine('Info returned to user:');
        $this->SimpleLifeMessage->AddAsNewLine(json_encode($obj));
        $this->SimpleLifeMessage->SendAsLog();
        $this->OperationSimpleLifeMessage->sendAsLog();
    }

    private function ErrorHandler($fun) {
        return '[Error ' . $this->ErrorCode . ']Este error se traducirá y se mostrará si es relevante para el usuario';
    }

}

?>