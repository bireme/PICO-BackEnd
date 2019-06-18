<?php

namespace LayerEntities;

class TreeObject {

    private $descendants;
    private $content;
    private $tree_id;

    public function __construct($tree_id) {
        $this->descendants = array();
        $this->tree_id = $tree_id;
        $this->content = array();
    }

    public function setContent($DescendantArray, $content) {
        foreach ($DescendantArray as $descendantTreeId => $TreeObject) {
            if (in_array($descendantTreeId, array_keys($this->descendants))) {
                continue;
            }
            $this->descendants[$descendantTreeId] = $TreeObject;
        }
        $this->content = array_merge($this->content, $content);
    }

    public function getExploredLanguages() {
        return array_keys($this->content);
    }

    public function getDescendants() {
        return $this->descendants;
    }

    public function getResults() {
        return array(
            'descendants' => array_keys($this->descendants),
            'content' => $this->content
        );
    }

    public function getInnerDescendantResults() {
        $descendantsResults = array();
        foreach ($this->descendants as $descendantTreeId => $TreeObject) {
            $descendantsResults[$descendantTreeId] = $TreeObject->getInnerDescendantResults();
        }
        $results = array();
        $results['descendants'] = $descendantsResults;
        $results['content'] = $this->content;
        return $results;
    }

}

?>