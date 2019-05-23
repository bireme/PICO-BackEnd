<?php

require_once(realpath(dirname(__FILE__)) . '/FacadeResultsNumberObtainer.php');

$data = filter_input(INPUT_POST, 'data');
$data = json_decode($data,true);
$resultsData = $data['resultsData'];
$PICOnum = $data['PICOnum'];
$PICOLIST=array('Problem','Intervention','Comparison','Outcome','Tipe of Study','General');
$PICOname=$PICOLIST[$PICOnum-1];

$Obj = new ControllerEventResultsNumber();
$Obj->getResultsNumber($resultsData, $PICOnum,$PICOname);
$Obj->EchoResults();

class ControllerEventResultsNumber {

    private $ResultsNumberObtainer;


    public function getResultsNumber($resultsData, $PICOnum,$PICOname) {
        $this->ResultsNumberObtainer = new FacadeResultsNumberObtainer($resultsData, $PICOnum,$PICOname);
        $this->ResultsNumberObtainer->buildResultsNumber();
    }

    public function EchoResults() {
        echo $this->ResultsNumberObtainer->getResultsNumber();
    }

}

?>