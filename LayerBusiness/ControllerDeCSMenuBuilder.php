<?php

namespace LayerBusiness;

class ControllerDeCSMenuBuilder {

    private $ObjectKeywordList;

    public function __construct($ObjectKeywordList) {
        $this->ObjectKeywordList = $ObjectKeywordList;
    }

    public function BuildHTML($PICOnum) {
        $results = $this->ObjectKeywordList->getFullDeCSList();
        $this->BuildDescriptorsHTML($results, $PICOnum);
        $this->BuildDeCSHTML($results);
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
        foreach ($results as $keyword => $ObjectKeyword) {
            $this->HTMLaddLine(0, '<li class="nav-item">');
            $tmp = '';
            $tmp2 = 'false';
            $NumberOfDescriptors = count($ObjectKeyword);
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
        foreach ($results as $keyword => $ObjectKeyword) {
            $itemname = $itemsnameprexif . $keywordNum;
            $tmp = '';
            if ($keywordNum == 1) {
                $tmp = ' show active';
            }
            $this->HTMLaddLine(0, '<div class="tab-pane fade' . $tmp . '" id="' . $itemname . '" role="tabpanel" aria-labelledby="' . $itemname . '-tab">');

            $DescriptorNum = 1;
            foreach ($ObjectKeyword as $tree_id => $ObjectDescriptor) {
                $DescriptorTag = $ObjectDescriptor['term'];
                $this->HTMLaddLine(0, '<div class="form-group">');
                $inputid = 'Descriptor' . $keywordNum . '-' . $DescriptorNum;
                $this->HTMLaddLine(0, '<input id="' . $inputid . '" class="DescriptorCheckbox" type="checkbox"> <label for="' . $inputid . '">' . $DescriptorTag . '</label>');
                $this->HTMLaddLine(0, '</div>');
                $DescriptorNum++;
            }
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
        foreach ($results as $keyword => $ObjectKeyword) {
            $DescriptorOfKeywordNum = 1;
            foreach ($ObjectKeyword as $tree_id => $ObjectDescriptor) {
                $tmp = '';
                $tmp2 = 'false';
                if ($itemNum == 1) {
                    $tmp = ' active';
                    $tmp2 = 'true';
                }
                $itemname = $itemsnameprexif . $keywordNum . '-' . $DescriptorOfKeywordNum;
                $DeCSDescriptor = $ObjectDescriptor['term'];
                $NumberOfDeCS = count($ObjectDescriptor['DeCS']);
                $msg = '<a class = "nav-link' . $tmp . '" id = "' . $itemname . '-tab"  data-toggle = "tab" href = "#' . $itemname . '" role = "tab" aria-controls = "' . $itemname . '" aria-selected = "' . $tmp2 . '">' . $DeCSDescriptor . ' <span class = "badge badge-info">' . $NumberOfDeCS . '</span></a>';
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
        foreach ($results as $keyword => $ObjectKeyword) {
            $DescriptorOfKeywordNum = 1;
            foreach ($ObjectKeyword as $tree_id => $ObjectDescriptor) {
                $itemname = $itemsnameprexif . $keywordNum . '-' . $DescriptorOfKeywordNum;
                $CheckBoxId = 'DescriptorCheckBox'.$keywordNum . '-' . $DescriptorOfKeywordNum;
                $DeCS = join($ObjectDescriptor['DeCS'], ' - ');
                $tmp = '';
                if ($itemNum == 1) {
                    $tmp = ' show active';
                }
                $this->HTMLaddLine(1, '<div class="tab-pane fade DeCSCheckBoxElement' . $tmp . '" id="' . $itemname . '" role="tabpanel" aria-labelledby="' . $itemname . '-tab">');
                $this->HTMLaddLine(1, '<table class = "tableModal table-hover" ><tbody><tr>');
                $this->HTMLaddLine(1, '<td valign="top"><input id="' . $CheckBoxId . '" name="' . $tree_id . '" type="checkbox"></td>');
                $this->HTMLaddLine(1, '<td><label name="' . $itemNum . 'b" for="' . $itemNum . 'b">' . $DeCS . '</label></td>');
                $this->HTMLaddLine(1, '</tr></tbody></table>');
                $this->HTMLaddLine(1, '</div>');
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
                $this->ObjectKeywordList->AddDescriptorsHTML($txt . PHP_EOL);
                break;
            case 1:
                $this->ObjectKeywordList->AddDeCSHTML($txt . PHP_EOL);
                break;
        }
    }

}

?>