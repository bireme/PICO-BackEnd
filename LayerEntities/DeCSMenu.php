<?php

namespace LayerEntities;

class DeCSMenu {

    private $HTMLDescriptors;
    private $HTMLDeCS;
    private $results;

    public function __construct() {
        $this->HTMLDescriptors = '';
        $this->HTMLDeCS = '';
    }

    public function setResults($results) {
        $this->results = $results;
    }

}

?>