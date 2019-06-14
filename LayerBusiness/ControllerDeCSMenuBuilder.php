<?php

namespace LayerBusiness;

require_once(realpath(dirname(__FILE__)) . '/../SimpleLife/ManualExceptions.php');

use SimpleLife\SimpleLifeException;

class ControllerDeCSMenuBuilder {

    private $ObjectQuerySplitList;

    public function __construct($ObjectQuerySplitList) {
        $this->ObjectQuerySplitList = $ObjectQuerySplitList;
    }

    public function BuildHTML($PICOnum) {
        $langs = $this->ObjectQuerySplitList->getUsedLangs();
        $mainlanguage = $this->ObjectQuerySplitList->getMainLanguage();
        $ItemList = $this->ObjectQuerySplitList->getItemList();
        $results = $this->ObjectQuerySplitList->getObjectKeywordList()->getKeywordListResults();

        $results = $this->ProcessResults($results, $mainlanguage, $langs, $ItemList);
        try {
            if (count($results) == 0) {
                throw new SimpleLifeException(new \SimpleLife\NoNewKeywordsInEquation());
            }
        } catch (SimpleLifeException $exc) {
            return $exc->PreviousUserErrorCode();
        }

        $this->BuildDescriptorsHTML($results, $PICOnum);
        $this->BuildDeCSHTML($results);
    }

    private function ProcessResults($results, $mainlanguage, $langs, $ItemList) {
        $Result = array();
        $keylist = array();
        foreach ($ItemList as $item) {
            if ($item['type'] == 'key' || $item['type'] == 'keyrep' || $item['type'] == 'keyexplored') {
                array_push($keylist, $item['value']);
            }
        }

        foreach ($results as $keyword => $ObjKeyword) {
            if (!(in_array($keyword, $keylist))) {
                continue;
            }
            $ObjectKeyword = $ObjKeyword['content'];
            $Result[$keyword] = array();
            foreach ($ObjectKeyword as $term => $TermObj) {
                if (!(array_key_exists($term, $Result[$keyword]))) {
                    $Result[$keyword][$term] = array();
                }
                foreach ($TermObj as $tree_id => $DeCSLanObj) {
                    foreach ($DeCSLanObj as $lang => $DeCSArr) {
                        if (!(in_array($lang, $langs))) {
                            continue;
                        }
                        $Result[$keyword][$term] = array_merge($Result[$keyword][$term], $DeCSArr);
                    }
                }
                $Result[$keyword][$term] = array_unique($Result[$keyword][$term]);
            }
        }
        return $Result;
    }

    private function BuildDescriptorsHTML($results, $PICOnum) {
        $titletabname = 'myTab';
        $contenttabname = 'myTabContent';
        $itemsnameprexif = 'opcao';
        $this->HTMLaddLine(0, '<form action="">');

///////////////////////////////////////////////////////////
////TAB HEADER
/////////////////////////////////////////////////////////////
        $this->HTMLaddLine(0, '<input type="hidden" id="PICONumTag" value="' . $PICOnum . '"></input>');
        $this->HTMLaddLine(0, '<ul class = "nav nav-tabs" id = "' . $titletabname . '" role = "tablist">');
        $keywordNum = 1;
        foreach ($results as $keyword => $ObjKeyword) {
            $this->HTMLaddLine(0, '<li class="nav-item">');
            $tmp = '';
            $tmp2 = 'false';
            $NumberOfDescriptors = count($ObjKeyword);
            if ($keywordNum == 1) {
                $tmp = ' active';
                $tmp2 = 'true';
            }
            $itemname = $itemsnameprexif . $keywordNum;
            $msg = '<a class = "nav-link' . $tmp . '" id = "' . $itemname . '-tab"  data-toggle = "tab" href = "#' . $itemname . '" role = "tab" aria-controls = "' . $itemname . '" aria-selected = "' . $tmp2 . '">' . $keyword . ' <span class = "badge badge-info">' . $NumberOfDescriptors . '</span></a>';
            $this->HTMLaddLine(0, $msg);

            $this->HTMLaddLine(0, '</li>');
            $keywordNum++;
        }
        $this->HTMLaddLine(0, '</ul>');

///////////////////////////////////////////////////////////
////TAB CONTENT
/////////////////////////////////////////////////////////////

        $this->HTMLaddLine(0, '<div class="tab-content" id="' . $contenttabname . '">');
        $keywordNum = 1;
        foreach ($results as $keyword => $ObjKeyword) {
            $itemname = $itemsnameprexif . $keywordNum;
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
        $this->HTMLaddLine(0, '</form>');
    }

    private function BuildDeCSHTML($results) {
        $titletabname = 'myTab2';
        $contenttabname = 'myTabContent2';
        $itemsnameprexif = 'opcao';

        $this->HTMLaddLine(1, '<form action="">');

///////////////////////////////////////////////////////////
////TAB HEADER
/////////////////////////////////////////////////////////////

        $this->HTMLaddLine(1, '<ul class = "nav nav-tabs" id = "' . $titletabname . '" role = "tablist">');
        $keywordNum = 1;
        $itemNum = 1;
        foreach ($results as $keyword => $ObjKeyword) {
            $DescriptorOfKeywordNum = 1;
            foreach ($ObjKeyword as $DescriptorTag => $DeCSArr) {
                $tmp = '';
                $tmp2 = 'false';
                if ($itemNum == 1) {
                    $tmp = ' active';
                    $tmp2 = 'true';
                }
                $itemname = $itemsnameprexif . $keywordNum . '-' . $DescriptorOfKeywordNum;
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

///////////////////////////////////////////////////////////
////TAB CONTENT
/////////////////////////////////////////////////////////////

        $this->HTMLaddLine(1, '<div class="tab-content" id="' . $contenttabname . '">');
        $keywordNum = 1;

        $itemNum = 1;
        foreach ($results as $keyword => $ObjKeyword) {
            $DescriptorOfKeywordNum = 1;
            foreach ($ObjKeyword as $DescriptorTag => $DeCSArr) {
                $itemname = $itemsnameprexif . $keywordNum . '-' . $DescriptorOfKeywordNum;
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
        $this->HTMLaddLine(1, '</form>');
    }

    private function HTMLaddLine($HTMLfile, $txt) {
        switch ($HTMLfile) {
            case 0:
                $this->ObjectQuerySplitList->AddDescriptorsHTML($txt . PHP_EOL);
                break;
            case 1:
                $this->ObjectQuerySplitList->AddDeCSHTML($txt . PHP_EOL);
                break;
        }
    }

}

?>