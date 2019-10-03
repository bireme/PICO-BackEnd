<?php

namespace PICOExplorer\Services\ServiceModels;

use PICOExplorer\Exceptions\Exceptions\ClientError\UnmatchingParentheses;
use PICOExplorer\Exceptions\Exceptions\ClientError\UnmatchingQuotes;

trait PICOQueryProcessorTrait
{

    protected function ProcessQuery(string $query)
    {
        if ($query === null || strlen($query) === 0) {
            return [];
        }

        $query = str_replace('  ', ' ', $query);
        $query = str_replace("'", '"', $query);
        $query = strtolower($query);
        $InititalSplit = $this->SplitByQuotes($query);
        $QuerySplitBySeparators = $this->SplitArrayByOps(' or ', $InititalSplit);
        $QuerySplitBySeparators = $this->SplitArrayByOps(' and ', $QuerySplitBySeparators);
        $QuerySplitBySeparators = $this->SplitArrayByOps('not ', $QuerySplitBySeparators);
        $res=[];
        foreach($QuerySplitBySeparators as $obj){
            $inside = $obj['inside']??null;
            $value = $obj['value']??null;
            if($value!== null && strlen($value)){
                if($inside===true){
                    array_push($res,$value);
                }else{
                    $pattern = '/([()": ])/';
                    $QuerySplitBySeparators = preg_split($pattern, $value, -1, PREG_SPLIT_DELIM_CAPTURE | PREG_SPLIT_NO_EMPTY);
                    $res= array_merge($res,$QuerySplitBySeparators);
                }
            }
        }
        return $res;
    }

    ///////////////////////////////////////////////////////////////////
    //INNER FUNCTIONS
    ///////////////////////////////////////////////////////////////////

    private function SplitArrayByOps(string $delimiter, array $array)
    {
        $res = [];
        foreach ($array as $obj) {
            if (!($obj)) {
                continue;
            }
            $inside = $obj['inside']??null;
            $value = $obj['value']??null;
            if ($inside === null) {
                continue;
            }
            if ($inside === true) {
                array_push($res, $obj);
            }else{
                $tmp = explode($delimiter,$value);
                foreach($tmp as $index => $localvalue){
                    if($localvalue!==null && strlen($localvalue)){
                        array_push($res, ['inside'=>false,'value'=>$localvalue]);
                        if($index<count($tmp)-1){
                            array_push($res, ['inside'=>false,'value'=>$delimiter]);
                        }
                    }
                }
            }
        }
        return $res;
    }

    private function SplitByQuotes(string $query)
    {
        $open = substr_count($query, '(');
        $close = substr_count($query, ')');
        if ($open !== $close) {
            throw new UnmatchingParentheses();
        }
        $QuerySplitByComma = explode('"',$query);
        if (count($QuerySplitByComma) % 2 === 0) {
            throw new UnmatchingQuotes();
        }

        $InParenthesis = false;
        $res = [];
        foreach ($QuerySplitByComma as $value) {
            if($InParenthesis){
                $local=true;
            }else{
                $local=false;
            }
            array_push($res, ['inside' => $local, 'value' => $value]);
            $InParenthesis = !($InParenthesis);
        }
        return $res;
    }


}
