<?php

namespace PICOExplorer\Services\ResultsNumber;

use PICOExplorer\Exceptions\InternalErrors\ErrorWhileUpdatingTheModel;
use PICOExplorer\Exceptions\InternalErrors\IncongruentDataObtainedFromResultsNumberIntegration;

class ResultsNumberProcess extends ResultsNumberIntegrationHandler
{

    /**
     * @var array
     */
    private $fields = array('', 'ti', 'tw', 'mh');


    final public function Process()
    {
    }

    protected function Explore(array $arguments = null)
    {
        $ResultsClusters = $this->buildGlobalAndLocalArrays();
        $ProcessedQueries=[];
        foreach ($ResultsClusters as $key => $ResultsCluster) {
            $ProcessedQueries[$key] = $this->BuildQuery($ResultsCluster);
        }
        $ex=$this->UpdateModel(['ProcessedQueries'=>$ProcessedQueries],true);
        if($ex){
            throw new ErrorWhileUpdatingTheModel(null,$ex);
        }
        $IntegrationResults = $this->ProxyIntegrationResultsNumber($ProcessedQueries);
        $this->IntegrationResultsToObject($IntegrationResults);
    }

    ///////////////////////////////////////////////////////////////////
    //INNER FUNCTIONS
    ///////////////////////////////////////////////////////////////////

    private function buildGlobalAndLocalArrays()
    {
        $local = [];
        $global = [];
        $EqNum = $this->model->PICOnum;
        $EqNumCopy = ($EqNum==6)?5:$EqNum;
        $i = 1;
        while ($i <= $EqNumCopy) {
            $PICOEquationId = 'PICO' . $i;
            $PICOEquationArray = $this->model->Initialdata[$PICOEquationId];
            if ($i == $EqNumCopy && $EqNum != 6) {
                $local[$PICOEquationId] = $PICOEquationArray;
            }
            $global[$PICOEquationId] = $PICOEquationArray;
            $i++;
        }
        return ['global' => $global, 'local' => $local];
    }

    private function BuildQuery(array $ResultsCluster)
    {
        $query = '';
        $msg='';
        $count = 0;
        foreach ($ResultsCluster as $PICOEquationId => $PICOEquationData) {
            if($count == 0){
                $msg='';
            }else{
                $msg=$msg . ' AND ';
            }
            if (strlen($PICOEquationData['query']) > 0) {
                $msg = $msg . $this->SetSearchField($PICOEquationData);
                $query = $query . $msg;
                $count++;
            }
        }
        return $query;
    }

    private function SetSearchField(array $PICOEquationData)
    {
        $fieldnum = $PICOEquationData['field'];
        $field = ($fieldnum == -1)?null:$this->fields[$fieldnum];
        return ($field)?$PICOEquationData['query']:$field . ':(' . $PICOEquationData['query'] . ')';
    }

    private function IntegrationResultsToObject(array $data)
    {
        $keysdata = array_keys($data);
        $keysResultList = array_keys($this->model->ProcessedQueries);
        $inter = array_intersect($keysdata, $keysResultList);
        if ((count($inter) < count($keysdata)) || (count($inter) < count($keysResultList))) {
            throw new IncongruentDataObtainedFromResultsNumberIntegration(['DataFromIntegration'=>$keysdata,'DataFromBusinessLogic'=>$keysResultList],null);
        }

        $ex=$this->UpdateModel($data, false);
        if($ex){
            throw new ErrorWhileUpdatingTheModel(null,$ex);
        }
    }

}
