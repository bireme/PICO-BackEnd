<?php

namespace PICOExplorer\Services\ServiceModels;

use PICOExplorer\Exceptions\Exceptions\ClientError\UnmatchingParentheses;
use PICOExplorer\Exceptions\Exceptions\ClientError\UnmatchingQuotes;

trait PICOQueryProcessorTrait
{

    protected function ProcessQuery(string $query)
    {
        $query = strtolower($query);
        if ($query === null || strlen($query) === 0) {
            return [];
        }
        $query = str_replace('  ', ' ', $query);
        $QuerySplitByQuotes = $this->SplitByQuotes($query);
        return $QuerySplitByQuotes;
    }

    ///////////////////////////////////////////////////////////////////
    //INNER FUNCTIONS
    ///////////////////////////////////////////////////////////////////


    private function SplitByQuotes(string $query)
    {
        $open = substr_count($query, '(');
        $close = substr_count($query, ')');
        if ($open !== $close) {
            throw new UnmatchingParentheses(['$query'=>$query]);
        }
        $QuerySplitByComma = explode('"', $query);
        if (count($QuerySplitByComma) % 2 === 0) {
            throw new UnmatchingQuotes();
        }
        $OpsExtra = [' and ', ' or ', 'not '];
        $Ops = ['and', 'or', 'not'];
        $InQueryCluster = false;
        $res = [];
        foreach ($QuerySplitByComma as $value) {
            if ($InQueryCluster) {
                array_push($res, ucwords(strtolower($value)));
            } else {
                $valuex = $this->SplitArrayByOps($value,$OpsExtra,$Ops);
                $res = array_merge($res, $valuex);
            }
            $InQueryCluster = !($InQueryCluster);
        }
        return $res;
    }

    private function SplitArrayByOps(string $value,array $OpsExtra,array $Ops)
    {
        $pattern = '/( and | or |not )/';
        $Split = preg_split($pattern, $value, -1, PREG_SPLIT_DELIM_CAPTURE);
        $res = [];
        foreach ($Split as $localv) {
            if(in_array($localv,$OpsExtra)){
                $pattern = '/([ ])/';
                $minisplit = preg_split($pattern, $localv, -1, PREG_SPLIT_DELIM_CAPTURE | PREG_SPLIT_NO_EMPTY);
                $minisplit = array_map('strtoupper', $minisplit);
            } else {
                $pattern = '/([():])/';
                $minisplit = preg_split($pattern, $localv, -1, PREG_SPLIT_DELIM_CAPTURE | PREG_SPLIT_NO_EMPTY);
                $minisplit = array_map('strtolower', $minisplit);
                $minisplit = array_map('ucwords', $minisplit);
            }
            $res = array_merge($res, $minisplit);
        }
        foreach($res as $index => $val){
            if(in_array($val,$Ops)){
                $Ops[$index]=strtoupper($val);
            }else{
                $Ops[$index]=ucwords(strtolower(($val)));
            }
        }
        return $res;
    }
}
