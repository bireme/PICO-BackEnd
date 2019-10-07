<?php

namespace PICOExplorer\Services\ResultsNumber;

use PICOExplorer\Exceptions\Exceptions\ClientError\EmptyQuery;
use PICOExplorer\Http\Traits\CorrectQueryTrait;
use PICOExplorer\Models\DataTransferObject;
use PICOExplorer\Services\ServiceModels\PICOQueryProcessorTrait;
use PICOExplorer\Services\ServiceModels\ServiceEntryPoint;

abstract class ResultsNumberSupport extends ServiceEntryPoint
{

    /**
     * @var array
     */
    private $fields = ['', 'ti', 'tw', 'mh'];
    private $piconames =['P','I','C','0'];

    use PICOQueryProcessorTrait;
    use CorrectQueryTrait;

    protected function BuildQueries(DataTransferObject $DTO, $InitialData)
    {
        $PICOnum = $InitialData['PICOnum'];
        $GlobalTitle='';
        $QueryObject = $InitialData['queryobject'];
        $EquationTmp = [];
        foreach ($QueryObject as $index => $arrayitem) {
            if ($index >= $PICOnum) {
                break;
            }
            $query = $arrayitem['query'] ?? '';
            $field = $arrayitem['field'] ?? 0;
            if (($index + 1) === $PICOnum) {
                if (!(strlen($query))) {
                    new EmptyQuery();
                }
            }
            if (strlen($query)) {
                $QueryObject[$index]['query'] = $this->processEquation($query);
            }
            $localequation = '';
            if (strlen($QueryObject[$index]['query'])) {
                if($PICOnum<5 && $PICOnum>1){
                    if(strlen($GlobalTitle)){
                        $GlobalTitle=$GlobalTitle.'+';
                    }
                    $GlobalTitle=$GlobalTitle.$this->piconames[$index];
                }
                $localequation = $QueryObject[$index]['query'];
                $fieldtext = $this->getFieldText($field);
                if (strlen($fieldtext)) {
                    $localequation = $fieldtext . ':(' . $localequation . ')';
                }
            }
            $EquationTmp[$index] = $localequation;
        }
        $ToExplore = [];
        if ($PICOnum < 5) {
            $ToExplore['local'] = $EquationTmp[$PICOnum - 1];
        }
        if ($PICOnum > 1) {
            $tmp = '';
            foreach ($EquationTmp as $txt) {
                if (strlen($txt)) {
                    if (strlen($tmp)) {
                        $tmp = $tmp . ' AND (' . $txt . ')';
                    } else {
                        $tmp = $txt;
                    }
                }
            }
            $ToExplore['global'] = $tmp;
        }
        if ($PICOnum === 6) {
            $NewEquation = $ToExplore['global'];
        } else {
            $NewEquation = $QueryObject[$PICOnum - 1]['query'];
        }

        $DTO->SaveToModel(get_class($this), ['NewEquation' => $NewEquation,'GlobalTitle' => $GlobalTitle]);
        return $ToExplore;
    }


    ///////////////////////////////////////////////////////////////////
    //INNER FUNCTIONS
    ///////////////////////////////////////////////////////////////////

    private function getFieldText(int $fieldnum)
    {
        if ($fieldnum === -1) {
            $fieldnum = 0;
        }
        return $this->fields[$fieldnum];
    }

    private function processEquation(String $query = null)
    {
        if (!($query)) {
            return '';
        }
        $query = $this->FixEquation($query);
        $ProcessedQuery = $this->ProcessQuery($query);
        foreach ($ProcessedQuery as $index => $value) {
            if($value!==' '){
                if (strpos($value, ' ') !== false) {
                    $ProcessedQuery[$index] = '"' . $value . '"';
                }
            }
        }

        return join('', $ProcessedQuery);
    }

}
