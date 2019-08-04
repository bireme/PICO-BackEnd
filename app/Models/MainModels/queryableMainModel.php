<?php


namespace PICOExplorer\Models\MainModels;


interface queryableMainModel
{

    public function AddToItemList(array $QuerySplitBySepsWithValues);
    public function getKeywordList();
    public function CreateKeywordObject($value);

}
