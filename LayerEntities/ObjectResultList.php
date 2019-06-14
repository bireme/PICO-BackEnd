<?php

namespace LayerEntities;

require_once('ResultQuery.php');

use LayerEntities\ResultQuery;

class ObjectResultList {

    private $PICONum;
    private $ResultQueryList;
    private $PICOname;
    private $queryobject;
    
    public function getInitialData(){
        return $this->queryobject;
    }

    public function __construct($PICONum, $queryobject, $PICOname) {
        $this->PICONum = $PICONum;
        $this->PICOname = $PICOname;
        $this->queryobject = $queryobject;
        $this->ResultQueryList=array();
    }
    
    function getPICONum() {
        return $this->PICONum;
    }

    function getPICOname() {
        return $this->PICOname;
    }

    
    public function getConnectionTimeSum() {
        $res = 0;
        foreach ($this->ResultQueryList as $ResultObj) {
            $res += $ResultObj->getConnectionTime();
        }
        return $res;
    }
    
    public function AddIntegrationResultsToResultQuery($key,$resultsNumber,$resultsURL){
        $ResultsObj = $this->ResultQueryList[$key];
        $ResultsObj->setResultsNumber($resultsNumber);
        $ResultsObj->setResultsURL($resultsURL);
    }

    public function AddResultQuery($key,$query) {
        if(array_key_exists($key,$this->ResultQueryList)){
            return NULL;
        }else{
            $ObjResult = new ResultQuery($query);
            $this->ResultQueryList[$key]=$ObjResult;
            return $ObjResult;
        }
    }
    
    public function getAllResultQueryObjects() {
        return $this->ResultQueryList;
    }

    public function getResults() {
        $result = array();
        foreach ($this->ResultQueryList as $key => $ResultObj) {
            $result[$key]=$ResultObj->getResults();
        }
        return $result;
    }
}

?>