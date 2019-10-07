<?php


namespace PICOExplorer\Http\Traits;


trait CorrectQueryTrait
{

    protected function FixEquation(String $iniquery)
    {
        $iniquery='('.$iniquery.')';
        $error = true;
        while ($error) {
            $error = false;
            $query = $iniquery;
            $query = $this->FixPars($query);
            $query = $this->FixParsOps($query);
            $query = $this->FixPars($query);
            if ($query !== $iniquery) {
                $error = true;
                $iniquery = $query;
            }
        }
        if(strlen($query)){
            $query=substr($query,1,-1);
        }
        return $query;
    }

    private function FixParsOps(String $iniquery)
    {
        $error = true;
        while ($error) {
            $error = false;
            $query = $iniquery;
            $query = str_replace('  ', ' ', $query);
            $query = trim($query, ' ');
            $query = str_ireplace('(OR ', '(', $query);
            $query = str_ireplace('(AND ', '(', $query);
            $query = str_ireplace('(NOT ', '(NOT ', $query);
            $query = str_ireplace('(OR(', '( OR (', $query);
            $query = str_ireplace('(AND(', '( AND (', $query);
            $query = str_ireplace('(NOT(', '(NOT (', $query);
            $query = str_ireplace(' OR)', ')', $query);
            $query = str_ireplace(' AND)', ')', $query);
            $query = str_ireplace(' NOT)', ')', $query);
            $query = str_ireplace(' OR ', ' OR ', $query);
            $query = str_ireplace(' AND ', ' AND ', $query);
            $query = str_ireplace(' NOT ', ' NOT ', $query);
            $query = str_ireplace(')OR)', '))', $query);
            $query = str_ireplace(')AND)', '))', $query);
            $query = str_ireplace(')NOT)', '))', $query);
            $query = str_ireplace('(OR)', '', $query);
            $query = str_ireplace('(AND)', '', $query);
            $query = str_ireplace('(NOT)', '', $query);

            $query = str_replace(' NOT OR ', ' NOT ', $query);
            $query = str_replace(' NOT AND ', ' NOT ', $query);
            $query = str_replace('(NOT OR ', 'NOT OR', $query);
            $query = str_replace('(NOT AND ', ' NOT ', $query);


            $query = str_replace(' AND OR ', ' OR ', $query);
            $query = str_replace(' OR AND ', ' OR ', $query);
            $query = str_replace(' OR OR ', ' OR ', $query);
            $query = str_replace(' AND AND ', ' AND ', $query);
            $query = str_replace(' NOT NOT ', ' NOT ', $query);
            $query = str_replace('  ', ' ', $query);
            $query = trim($query, ' ');


            if ($query !== $iniquery) {
                $error = true;
                $iniquery = $query;
            }
        }
        return $query;
    }

    private function FixPars(String $iniquery)
    {
        $error = true;
        while ($error) {
            $error = false;
            $query = $iniquery;
            $query = str_replace('  ', ' ', $query);
            $query = trim($query, ' ');
            $query = str_replace('( ', '(', $query);
            $query = str_replace(' )', ')', $query);
            $query = str_replace('( )', '()', $query);
            $query = str_replace('()', '', $query);
            $query = str_replace(')(', ') (', $query);
            $query = str_replace('( (', '((', $query);
            $query = str_replace(') )', '))', $query);
            $query = str_replace(') (', ') OR (', $query);
            $query = trim($query, ' ');

            if ($query !== $iniquery) {
                $error = true;
                $iniquery = $query;
            }
        }
        return $query;
    }

}
