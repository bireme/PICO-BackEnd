<?php

require_once(realpath(dirname(__FILE__)) . '/FacadeDeCSObtainer.php');

$data = filter_input(INPUT_POST, 'data');
$data = json_decode($data,true);
$Equation = $data['query'];
$langArr = $data['langs'];
$PICOnum = $data['PICOnum'];
$PICOLIST=array('Problem','Intervention','Comparison','Outcome','Tipe of Study','General');
$PICOname=$PICOLIST[$PICOnum-1];
$Obj = new ControllerEventDeCSSearch();
$Obj->ObtainDeCS($Equation, $langArr,$PICOnum,$PICOname);
$Obj->EchoResults();

class ControllerEventDeCSSearch {

    private $DeCSObtainer;
    
    public function ObtainDeCS($Equation, $langArr,$PICOnum,$PICOname) {
        $this->DeCSObtainer = new FacadeDeCSObtainer($Equation, $langArr,$PICOnum,$PICOname);
         $this->DeCSObtainer->getKeywordDeCS();
    }

    public function EchoResults() {
        echo $this->DeCSObtainer->getResults();
    }

}

?>