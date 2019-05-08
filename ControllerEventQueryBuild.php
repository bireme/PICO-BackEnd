<?php

require_once(realpath(dirname(__FILE__)) . '/FacadeQueryBuilder.php');

$data = filter_input(INPUT_POST, 'data');
$data = json_decode($data,true);
$PICOnum = $data['PICOnum'];
$PICOLIST=array('Problem','Intervention','Comparison','Outcome','Tipe of Study','General');
$PICOname=$PICOLIST[$PICOnum-1];
$Equation = $data['query'];
$SelectedDescriptors = $data['SelectedDescriptors'];
$ImproveSearch = $data['ImproveSearch'];
$Obj = new ControllerEventQueryBuild();
$Obj->getNewQuery($Equation, $SelectedDescriptors,$ImproveSearch,$PICOname);
$Obj->EchoResults();

class ControllerEventQueryBuild {

    private $newQuery;

    public function __construct() {
        $this->newQuery = '';
    }

    public function getNewQuery($Equation, $SelectedDescriptors,$ImproveSearch,$PICOname) {
        $obj = new FacadeQueryBuilder($Equation, $SelectedDescriptors,$ImproveSearch,$PICOname);
        $result = $obj->getNewQuery();
        $this->newQuery = $result;
    }

    public function EchoResults() {
        $data = array(
            'newQuery' => $this->newQuery
        );
        echo json_encode($data);
    }

}

?>