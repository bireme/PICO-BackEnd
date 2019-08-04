<?php

namespace PICOExplorer\Models\MainModels;

use PICOExplorer\Exceptions\Exceptions\AppError\OverwrittingError;

class TreeObject
{

    private $descendants;
    private $content;
    private $tree_id;

    public function __construct(string $tree_id)
    {
        $this->descendants = array();
        $this->tree_id = $tree_id;
        $this->content = array();
    }

    public function setDescendants(array $DescendantArray)
    {
        foreach ($DescendantArray as $descendantTreeId => $TreeObject) {
            $this->descendants[$descendantTreeId] = $TreeObject;
        }
    }

    public function setContent(string $lang, string $term,array $decs,array $info)
    {
        if(array_key_exists($lang,$this->content)){
            throw new OverwrittingError(['currentContent'=>$this->content,'lang'=>$lang,'term'=>$term,'decs'=>$decs,'tree_id'=>$info['tree_id'],'referer'=>$info['referer']],null);
        }
        $this->content[$lang] =['decs'=>$decs, 'term'=>$term];
    }

    public function getExploredLanguages()
    {
        return array_keys($this->content);
    }

    public function getDescendants()
    {
        return $this->descendants;
    }

    public function getResults()
    {
        return array(
            'descendants' => array_keys($this->descendants),
            'content' => $this->content
        );
    }

}
