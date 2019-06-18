<?php

namespace LayerBusiness;

class ControllerDeCSMenuBuilder {

    private $ObjectQuerySplitList;
    private $Result;

    public function __construct($ObjectQuerySplitList) {
        $this->ObjectQuerySplitList = $ObjectQuerySplitList;
    }

    ///////////////////////////////////////////////////////////////////
    //PUBLIC FUNCTIONS
    ///////////////////////////////////////////////////////////////////

    public function BuildHTML() {
        return $results = $this->ProcessResults() ||
                $this->BuildDescriptorsHTML() ||
                $this->BuildDeCSHTML();
    }

    ///////////////////////////////////////////////////////////////////
    //INNER FUNCTIONS
    /////////////////////////////////////////////////////////////////// 

    private function BuildDeCSHTML() {
        $this->HTMLaddLine(1, '<form action="">');
        $this->BuildDeCSHTMLTabs();
        $this->BuildDeCSHTMLContent();
        $this->HTMLaddLine(1, '</form>');
    }

    private function BuildDescriptorsHTML() {
        $this->HTMLaddLine(0, '<form action="">');
        $this->BuildDescriptorsHTMLTabs();
        $this->BuildDescriptorsHTMLContent();
        $this->HTMLaddLine(0, '</form>');
    }

    private function HTMLaddLine($HTMLfile, $txt) {
        if ($HTMLfile == 0) {
            $this->ObjectQuerySplitList->AddDescriptorsHTML($txt . PHP_EOL);
        } else {
            $this->ObjectQuerySplitList->AddDeCSHTML($txt . PHP_EOL);
        }
    }

    private function ProcessResults() {
        $langs = $this->ObjectQuerySplitList->getUsedLangs();
        $ItemQuerySplitList = $this->ObjectQuerySplitList->getItemList();
        $results = $this->ObjectQuerySplitList->getObjectKeywordList()->getKeywordListResults();
        $Result = array();
        $keylist = $this->BuildUsedKeyList($ItemQuerySplitList);
        foreach ($results as $keyword => $ObjKeyword) {
            if (!(in_array($keyword, $keylist))) {
                continue;
            }
            $Result[$keyword] = array();
            $this->ProcessInnerObjectKeyword($ObjKeyword['content'], $Result[$keyword], $langs);
        }
        $this->Result = $Result;
    }

    private function BuildUsedKeyList($ItemQuerySplitList) {
        $keylist = array();
        foreach ($ItemQuerySplitList as $item) {
            if ($item['type'] == 'key' || $item['type'] == 'keyrep' || $item['type'] == 'keyexplored') {
                array_push($keylist, $item['value']);
            }
        }
        return $keylist;
    }

    private function ProcessInnerObjectKeyword($ObjectKeyword, &$ResultKeyword, $langs) {
        
        foreach ($ObjectKeyword as $term => $TermObj) {
            if (!(array_key_exists($term, $ResultKeyword))) {
                $ResultKeyword[$term] = array();
            }
            foreach ($TermObj as $tree_id => $DeCSLanObj) {
                foreach ($DeCSLanObj as $lang => $DeCSArr) {
                    if (!(in_array($lang, $langs))) {
                        continue;
                    }
                    $ResultKeyword[$term] = array_merge($ResultKeyword[$term], $DeCSArr);
                }
            }
            $ResultKeyword[$term] = array_unique($ResultKeyword[$term]);
        }
    }

    private function BuildDescriptorsHTMLTabs() {
        $this->HTMLaddLine(0, '<input type="hidden" id="PICONumTag" value="' . $this->ObjectQuerySplitList->getPICOnum() . '"></input>');
        $this->HTMLaddLine(0, '<ul class = "nav nav-tabs" id = "myTab" role = "tablist">');
        $keywordNum = 1;
        foreach ($this->Result as $keyword => $ObjKeyword) {
            $this->HTMLaddLine(0, '<li class="nav-item">');
            $tmp = '';
            $tmp2 = 'false';
            $NumberOfDescriptors = count($ObjKeyword);
            if ($keywordNum == 1) {
                $tmp = ' active';
                $tmp2 = 'true';
            }
            $itemname = 'opcao' . $keywordNum;
            $msg = '<a class = "nav-link' . $tmp . '" id = "' . $itemname . '-tab"  data-toggle = "tab" href = "#' . $itemname . '" role = "tab" aria-controls = "' . $itemname . '" aria-selected = "' . $tmp2 . '">' . $keyword . ' <span class = "badge badge-info">' . $NumberOfDescriptors . '</span></a>';
            $this->HTMLaddLine(0, $msg);

            $this->HTMLaddLine(0, '</li>');
            $keywordNum++;
        }
        $this->HTMLaddLine(0, '</ul>');
    }

    private function BuildDescriptorsHTMLContent() {
        $this->HTMLaddLine(0, '<div class="tab-content" id="myTabContent">');
        $keywordNum = 1;
        foreach ($this->Result as $keyword => $ObjKeyword) {
            $itemname = 'opcao' . $keywordNum;
            $tmp = '';
            if ($keywordNum == 1) {
                $tmp = ' show active';
            }
            $this->HTMLaddLine(0, '<div class="tab-pane fade' . $tmp . '" id="' . $itemname . '" role="tabpanel" aria-labelledby="' . $itemname . '-tab">');

            $DescriptorNum = 1;
            $this->HTMLaddLine(0, '<div class="container"><div class="row">');
            foreach ($ObjKeyword as $DescriptorTag => $DeCS) {
                $this->HTMLaddLine(0, '<div class="col-sm-12 col-md-6 col-lg-4 col-xl-3 tableModal table-hover">');
                $inputid = 'Descriptor' . $keywordNum . '-' . $DescriptorNum;
                $this->HTMLaddLine(0, '<table style="height:100%"><tbody><tr>');
                $this->HTMLaddLine(0, '<td><input id="' . $inputid . '" class="DescriptorCheckbox" type="checkbox" checked></td><td><label for="' . $inputid . '">' . $DescriptorTag . '</label></td>');
                $this->HTMLaddLine(0, '</tr></tbody></table>');
                $this->HTMLaddLine(0, '</div>');
                $DescriptorNum++;
            }
            $this->HTMLaddLine(0, '</div></div>');

            $this->HTMLaddLine(0, '</div>');
            $keywordNum++;
        }
        $this->HTMLaddLine(0, '</div>');
    }

    private function BuildDeCSHTMLTabs() {
        $this->HTMLaddLine(1, '<ul class = "nav nav-tabs" id = "myTab2" role = "tablist">');
        $keywordNum = 1;
        $itemNum = 1;
        foreach ($this->Result as $keyword => $ObjKeyword) {
            $DescriptorOfKeywordNum = 1;
            foreach ($ObjKeyword as $DescriptorTag => $DeCSArr) {
                $tmp = '';
                $tmp2 = 'false';
                if ($itemNum == 1) {
                    $tmp = ' active';
                    $tmp2 = 'true';
                }
                $itemname = 'opcao' . $keywordNum . '-' . $DescriptorOfKeywordNum;
                $NumberOfDeCS = count($DeCSArr);
                $msg = '<a class = "nav-link' . $tmp . '" id = "' . $itemname . '-tab"  data-toggle = "tab" href = "#' . $itemname . '" role = "tab" aria-controls = "' . $itemname . '" aria-selected = "' . $tmp2 . '">' . $DescriptorTag . ' <span class = "badge badge-info">' . $NumberOfDeCS . '</span></a>';
                $this->HTMLaddLine(1, '<li class = "nav-item">');
                $this->HTMLaddLine(1, $msg);
                $this->HTMLaddLine(1, '</li>');
                $DescriptorOfKeywordNum++;
                $itemNum++;
            }
            $keywordNum++;
        }
        $this->HTMLaddLine(1, '</ul>');
    }

    private function BuildDeCSHTMLContent() {
        $this->HTMLaddLine(1, '<div class="tab-content" id="myTabContent2">');
        $keywordNum = 1;

        $itemNum = 1;
        foreach ($this->Result as $keyword => $ObjKeyword) {
            $DescriptorOfKeywordNum = 1;
            foreach ($ObjKeyword as $DescriptorTag => $DeCSArr) {
                $itemname = 'opcao' . $keywordNum . '-' . $DescriptorOfKeywordNum;
                $tmp = '';
                if ($itemNum == 1) {
                    $tmp = ' show active';
                }
                $this->HTMLaddLine(1, '<div class="tab-pane fade' . $tmp . '" id="' . $itemname . '" role="tabpanel" aria-labelledby="' . $itemname . '-tab">');
                $this->HTMLaddLine(1, '<div class="container"><div class="row">');
                $DeCSNum = 1;
                foreach ($DeCSArr as $DeCS) {
                    $this->HTMLaddLine(1, '<div class="col-sm-12 col-md-6 col-lg-4 col-xl-3 tableModal table-hover">');
                    $CheckBoxId = 'DescriptorCheckBox' . $keywordNum . '-' . $DescriptorOfKeywordNum . '-' . $DeCSNum;
                    $this->HTMLaddLine(1, '<table style="height:100%"><tbody><tr>');
                    $this->HTMLaddLine(1, '<td><input class="DeCSCheckBoxElement" id="' . $CheckBoxId . '" name="' . $DeCS . '" data-keyword="' . $keyword . '" data-term="' . $DescriptorTag . '" type="checkbox" checked></td>');
                    $this->HTMLaddLine(1, '<td><label for="' . $itemNum . 'b">' . $DeCS . '</label></td>');
                    $this->HTMLaddLine(1, '</tr></tbody></table>');
                    $DeCSNum++;
                    $this->HTMLaddLine(1, '</div>');
                }
                $this->HTMLaddLine(1, '</div></div></div>');
                $itemNum++;
                $DescriptorOfKeywordNum++;
            }
            $keywordNum++;
        }
        $this->HTMLaddLine(1, '</div>');
    }

}

?>