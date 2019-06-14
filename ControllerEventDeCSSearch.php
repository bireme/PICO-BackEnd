<?php

require_once(realpath(dirname(__FILE__)) . '/FacadeDeCSObtainer.php');

$data = filter_input(INPUT_POST, 'data');
$data = json_decode($data,true);
$Equation = $data['query'];
$langArr = $data['langs'];
$PICOnum = $data['PICOnum'];
$PICOnum = $data['PICOnum'];
$results=$data['results'];
$mainLanguage= $data['mainLanguage'];
$PICOLIST=array('Problem','Intervention','Comparison','Outcome','Tipe of Study','General');
$PICOname=$PICOLIST[$PICOnum-1];
$Obj = new ControllerEventDeCSSearch();
$Obj->ObtainDeCS($Equation, $langArr,$PICOnum,$PICOname,$results,$mainLanguage);
$Obj->EchoResults();

class ControllerEventDeCSSearch {

    private $DeCSObtainer;
    
    public function ObtainDeCS($Equation, $langArr,$PICOnum,$PICOname,$results,$mainLanguage) {
        $this->DeCSObtainer = new FacadeDeCSObtainer($Equation, $langArr,$PICOnum,$PICOname,$results,$mainLanguage);
         $this->DeCSObtainer->getKeywordDeCS();
    }

    public function EchoResults() {
        echo $this->DeCSObtainer->getResults();
    }

}

?>