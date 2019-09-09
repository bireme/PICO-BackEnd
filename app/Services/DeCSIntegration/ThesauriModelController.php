<?php


namespace PICOExplorer\Models;


use PICOExplorer\Exceptions\Exceptions\AppError\OverwrittingError;
use PICOExplorer\Models\DataModels\TreeModel;

class ThesauriModelController
{


    protected function TreeSeeker(string $tree_id)
    {
        $AllTrees = $this->AllTrees;
        if (array_key_exists($tree_id, $AllTrees)) {
            $TreeObject = $AllTrees[$tree_id];
        } else {
            $TreeObject = new TreeModel($tree_id);
            $AllTrees[$tree_id] = $TreeObject;
            $this->setAttribute('AllTrees', $AllTrees);
        }
        return $TreeObject;
    }

    public function TreeManager(string $tree_id, string $lang, string $term, array $decs, string $referer, bool $isMain = null)
    {
        $TreeObject = $this->TreeSeeker($tree_id);
        $MainTrees = $this->MainTreeList;
        if ($isMain && (!(array_key_exists($tree_id, $MainTrees)))) {
            $MainTrees[$tree_id] = $TreeObject;
            $this->setAttribute('MainTreeList', $MainTrees);
        }
        $info = [
            'tree_id' => $tree_id,
            'referer' => $referer,
        ];
        $TreeObject->setContent($lang, $term, $decs, $info);

    }

    public function TreeDescendants(string $tree_id, array $descendants)
    {
        $TreeObject = $this->AllTrees[$tree_id];
        $DescendantTrees = [];
        foreach ($descendants as $descendantTreeId) {
            if (in_array($descendantTreeId, array_keys($TreeObject->getDescendants()))) {
                continue;
            }
            $DescendantTrees[$descendantTreeId] = $this->TreeSeeker($descendantTreeId);
        }
        $TreeObject->setDescendants($DescendantTrees);
    }

    public function FindTreeIdLevelIntoTreesArray($TreeArray, $tree_id, $level, &$result)
    {
        $level++;
        if (in_array($tree_id, array_keys($TreeArray))) {
            if ($level < $result) {
                $result = $level;
            }
            return;
        }
        foreach ($TreeArray as $TreeArraytree_id => $MainTreeObject) {
            $descendants = $MainTreeObject->getDescendants();
            if (count($descendants) == 0) {
                continue;
            }
            $this->FindTreeIdLevelIntoTreesArray($descendants, $tree_id, $level, $result);
        }
    }

    public function getInnerDescendantResults(string $tree_id)
    {
        $TmpResults = $this->AllTrees[$tree_id]->getResults();
        $results = $TmpResults['content'];
        foreach ($TmpResults['descendants'] as $descendantTree_id) {
            $descendantsResults = $this->getInnerDescendantResults($descendantTree_id);
            foreach ($descendantsResults as $lang => $resdata) {
                if (!(array_key_exists($lang, $results))) {
                    $results[$lang] = [
                        'decs' => [],
                        'term' => null,
                    ];
                }
                $results[$lang]['decs'] = array_unique(array_merge($results[$lang]['decs'], [$resdata['term']], $resdata['decs']));
            }
        }
        return $results;
    }

    public function getResults()
    {
        $results = [];
        foreach ($this->MainTreeList as $tree_id => $MainTree) {
            $results[$tree_id] = $this->getInnerDescendantResults($tree_id);
        }
        return $results;
    }

    public function setDescendants(array $DescendantArray)
    {
        foreach ($DescendantArray as $descendantTreeId => $TreeObject) {
            $this->descendants[$descendantTreeId] = $TreeObject;
        }
    }

    public function setContent(string $lang, string $term, array $decs, array $info)
    {
        if (array_key_exists($lang, $this->content)) {
            throw new OverwrittingError(['currentContent' => $this->content, 'lang' => $lang, 'term' => $term, 'decs' => $decs, 'tree_id' => $info['tree_id'], 'referer' => $info['referer']], null);
        }
        $this->content[$lang] = ['decs' => $decs, 'term' => $term];
    }

    public function getResults()
    {
        return array(
            'descendants' => array_keys($this->descendants),
            'content' => $this->content
        );
    }

}
