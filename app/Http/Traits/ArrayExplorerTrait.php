<?php


namespace PICOExplorer\Http\Traits;


trait ArrayExplorerTrait
{

    protected function ExploreArray($data){
        if(!($data)){
            return null;
        }
        if(is_array($data)){
            foreach($data as &$val){
                $val = $this->ExploreArray($val);
            }
        }elseif(is_string($data)) {
            $data = $this->JSONattempt($data);
        }

        return $data;
    }

    protected function JSONattempt($cont)
    {
        if (is_numeric($cont)) return $cont;
        $res = json_decode($cont,true);
        if(json_last_error() == JSON_ERROR_NONE){
            return $res;
        }else{
            return $cont;
        }
    }


}
