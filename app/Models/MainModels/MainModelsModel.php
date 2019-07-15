<?php

namespace PICOExplorer\Models\MainModels;

use Jenssegers\Model\Model;
use PICOExplorer\Exceptions\Exceptions\AppErrors\ErrorWhileUpdatingTheModel;

abstract class MainModelsModel extends Model
{
    public function getResults(bool $asJson = true)
    {
        if($asJson){
            return $this->jsonSerialize();
        }else{
            return $this->toArray();
        }
    }

    public function UpdateModel(array $data, bool $replace = true)
    {
        $ex = $this->fill($data);
        if($ex){
            throw new ErrorWhileUpdatingTheModel(['Model' => get_class($this), 'Data'=>$data]);
        }
    }


}
