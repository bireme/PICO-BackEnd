<?php

namespace LayerEntities;

class DeCSDescriptorByLang {

    private $DeCSArr;
    private $term;

    public function __construct($term,$DeCSArr) {
        $this->term = $term;
        $this->DeCSArr = $DeCSArr;
    }
    
    public function getResults(){
        return array(
          'term'=>$this->term,
          'DeCSArr'=>$this->DeCSArr  
        );
    }

}

?>