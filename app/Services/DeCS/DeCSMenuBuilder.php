<?php

namespace PICOExplorer\Services\DeCS;

abstract class DeCSMenuBuilder extends DeCSInfoProcessor
{

    protected $attributes=[];

    protected function BuildHTML(array $ProcessedResults)
    {
        $this->BuildDescriptorsHTML($ProcessedResults);
        $this->BuildDeCSHTML($ProcessedResults);
    }

    ///////////////////////////////////////////////////////////////////
    //INNER FUNCTIONS
    ///////////////////////////////////////////////////////////////////

    private function BuildDescriptorsHTMLTabs(array $results, int $PICOnum)
    {
        $this->HTMLaddLine(0, '<input type="hidden" id="PICONumTag" value="' . $PICOnum . '"></input>');
        $this->HTMLaddLine(0, '<ul class = "nav nav-tabs" id = "myTab" role = "tablist">');
        $keywordNum = 1;
        foreach ($results as $keyword => $ObjDescriptor) {
            $this->HTMLaddLine(0, '<li class="nav-item">');
            $tmp = '';
            $tmp2 = 'false';
            $NumberOfDescriptors = count($ObjDescriptor);
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

    private function BuildDescriptorsHTMLContent(array $results)
    {
        $this->HTMLaddLine(0, '<div class="tab-content" id="myTabContent">');
        $keywordNum = 1;
        foreach ($results as $keyword => $ObjDescriptor) {
            $itemname = 'opcao' . $keywordNum;
            $tmp = '';
            if ($keywordNum == 1) {
                $tmp = ' show active';
            }
            $this->HTMLaddLine(0, '<div class="tab-pane fade' . $tmp . '" id="' . $itemname . '" role="tabpanel" aria-labelledby="' . $itemname . '-tab">');

            $DescriptorNum = 1;
            $this->HTMLaddLine(0, '<div class="container"><div class="row">');
            foreach ($ObjDescriptor as $DescriptorTag => $DeCS) {
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

    private function BuildDeCSHTMLTabs(array $results)
    {
        $this->HTMLaddLine(1, '<ul class = "nav nav-tabs" id = "myTab2" role = "tablist">');
        $keywordNum = 1;
        $itemNum = 1;
        foreach ($results as $keyword => $ObjDescriptor) {
            $DescriptorOfKeywordNum = 1;
            foreach ($ObjDescriptor as $DescriptorTag => $DeCSArr) {
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

    private function BuildDeCSHTMLContent(array $results)
    {
        $this->HTMLaddLine(1, '<div class="tab-content" id="myTabContent2">');
        $keywordNum = 1;
        $itemNum = 1;
        foreach ($results as $keyword => $ObjDescriptor) {
            $DescriptorOfKeywordNum = 1;
            foreach ($ObjDescriptor as $DescriptorTag => $DeCSArr) {
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

    private function HTMLaddLine(int $HTMLfile, string $txt)
    {
        if ($HTMLfile == 0) {
            $this->attributes['DescriptorsHTML']=$this->attributes['DescriptorsHTML'].$txt . PHP_EOL;
        } else {
            $this->attributes['DeCSHTML']=$this->attributes['DeCSHTML'].$txt . PHP_EOL;
        }
    }
    private function BuildDeCSHTML(array $results)
    {
        $this->HTMLaddLine(1, '<form action="">');
        $this->BuildDeCSHTMLTabs($results);
        $this->BuildDeCSHTMLContent($results);
        $this->HTMLaddLine(1, '</form>');
    }

    private function BuildDescriptorsHTML(array $results)
    {
        $this->HTMLaddLine(0, '<form action="">');
        $PICOnum=$this->DTO->getInitialData()['PICOnum'];
        $this->BuildDescriptorsHTMLTabs($results, $PICOnum);
        $this->BuildDescriptorsHTMLContent($results);
        $this->HTMLaddLine(0, '</form>');
    }


}
