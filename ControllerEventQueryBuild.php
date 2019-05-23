<?php

require_once(realpath(dirname(__FILE__)) . '/FacadeQueryBuilder.php');

$data = filter_input(INPUT_POST, 'data');
$data = json_decode($data, true);
$PICOnum = $data['PICOnum'];
$PICOLIST = array('Problem', 'Intervention', 'Comparison', 'Outcome', 'Tipe of Study', 'General');
$PICOname = $PICOLIST[$PICOnum - 1];
$results = $data['results'];
$SelectedDescriptors = $data['SelectedDescriptors'];
$ImproveSearch = $data['ImproveSearch'];
$Obj = new ControllerEventQueryBuild();
$Obj->BuildnewQuery($results, $SelectedDescriptors, $ImproveSearch, $PICOname);
$Obj->EchoResults();

class ControllerEventQueryBuild {

    private $QueryBuilder;

    public function BuildnewQuery($results, $SelectedDescriptors, $ImproveSearch, $PICOname) {
        $this->QueryBuilder = new FacadeQueryBuilder($results, $SelectedDescriptors, $ImproveSearch, $PICOname);
        $this->QueryBuilder->BuildnewQuery();
    }

    public function EchoResults() {
        echo $this->QueryBuilder->getResults();
    }

}

?>