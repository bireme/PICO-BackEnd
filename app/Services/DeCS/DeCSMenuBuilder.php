<?php

namespace PICOExplorer\Services\DeCS;

use PICOExplorer\Models\DataTransferObject;
use PICOExplorer\Services\ServiceModels\ServiceEntryPoint;

abstract class DeCSMenuBuilder extends ServiceEntryPoint
{

    protected function BuildHTML(DataTransferObject $DTO, int $PICOnum)
    {
        $DescriptorsHTML = '';
        $DeCSHTML = '';
        $ProcessedResults = $DTO->getAttr('ProcessedResults');
        $this->BuildDescriptorsHTML($ProcessedResults,$PICOnum,$DescriptorsHTML);
        $this->BuildDeCSHTML($ProcessedResults,$DeCSHTML);
        $HTMLdata =  [
            'DescriptorsHTML'=>$DescriptorsHTML,
            'DeCSHTML'=>$DeCSHTML,
        ];
        $DTO->SaveToModel(get_class($this),$HTMLdata);
    }

    ///////////////////////////////////////////////////////////////////
    //INNER FUNCTIONS
    ///////////////////////////////////////////////////////////////////

    private function BuildDeCSHTML(array $results,string &$DeCSHTML)
    {
        $DeCSHTML = $DeCSHTML . ('<form action="">');
        $this->BuildDeCSHTMLTabs($results,$DeCSHTML);
        $this->BuildDeCSHTMLContent($results,$DeCSHTML);
        $DeCSHTML = $DeCSHTML . ('</form>');
    }

    private function BuildDescriptorsHTML(array $results,int $PICOnum, string &$DescriptorsHTML)
    {
        $DescriptorsHTML = $DescriptorsHTML . ('<form action="">');
        $this->BuildDescriptorsHTMLTabs($results, $PICOnum,$DescriptorsHTML);
        $this->BuildDescriptorsHTMLContent($results,$DescriptorsHTML);
        $DescriptorsHTML = $DescriptorsHTML . ('</form>');
    }


    private function BuildDescriptorsHTMLTabs(array $results, int $PICOnum,string &$DescriptorsHTML)
    {
        $DescriptorsHTML = $DescriptorsHTML . ('<input type="hidden" id="PICONumTag" value="' . $PICOnum . '"></input>');
        $DescriptorsHTML = $DescriptorsHTML . ('<ul class = "nav nav-tabs" id = "myTab" role = "tablist">');
        $keywordNum = 1;
        foreach ($results as $keyword => $ObjDescriptor) {
            $DescriptorsHTML = $DescriptorsHTML . ('<li class="nav-item">');
            $tmp = '';
            $tmp2 = 'false';
            $NumberOfDescriptors = count($ObjDescriptor);
            if ($keywordNum == 1) {
                $tmp = ' active';
                $tmp2 = 'true';
            }
            $itemname = 'opcao' . $keywordNum;
            $msg = '<a class = "nav-link' . $tmp . '" id = "' . $itemname . '-tab"  data-toggle = "tab" href = "#' . $itemname . '" role = "tab" aria-controls = "' . $itemname . '" aria-selected = "' . $tmp2 . '">' . $keyword . ' <span class = "badge badge-info">' . $NumberOfDescriptors . '</span></a>';
            $DescriptorsHTML = $DescriptorsHTML . ($msg);

            $DescriptorsHTML = $DescriptorsHTML . ('</li>');
            $keywordNum++;
        }
        $DescriptorsHTML = $DescriptorsHTML . ('</ul>');
    }

    private function BuildDescriptorsHTMLContent(array $results,string &$DescriptorsHTML)
    {
        $DescriptorsHTML = $DescriptorsHTML . ('<div class="tab-content" id="myTabContent">');
        $keywordNum = 1;
        foreach ($results as $keyword => $ObjDescriptor) {
            $itemname = 'opcao' . $keywordNum;
            $tmp = '';
            if ($keywordNum == 1) {
                $tmp = ' show active';
            }
            $DescriptorsHTML = $DescriptorsHTML . ('<div class="tab-pane fade' . $tmp . '" id="' . $itemname . '" role="tabpanel" aria-labelledby="' . $itemname . '-tab">');

            $DescriptorNum = 1;
            $DescriptorsHTML = $DescriptorsHTML . ('<div class="container"><div class="row">');
            foreach ($ObjDescriptor as $DescriptorTag => $DeCS) {
                $DescriptorsHTML = $DescriptorsHTML . ('<div class="col-sm-12 col-md-6 col-lg-4 col-xl-3 tableModal table-hover">');
                $inputid = 'Descriptor' . $keywordNum . '-' . $DescriptorNum;
                $DescriptorsHTML = $DescriptorsHTML . ('<table style="height:100%"><tbody><tr>');
                $DescriptorsHTML = $DescriptorsHTML . ('<td><input id="' . $inputid . '" class="DescriptorCheckbox" type="checkbox" checked></td><td><label for="' . $inputid . '">' . $DescriptorTag . '</label></td>');
                $DescriptorsHTML = $DescriptorsHTML . ('</tr></tbody></table>');
                $DescriptorsHTML = $DescriptorsHTML . ('</div>');
                $DescriptorNum++;
            }
            $DescriptorsHTML = $DescriptorsHTML . ('</div></div>');

            $DescriptorsHTML = $DescriptorsHTML . ('</div>');
            $keywordNum++;
        }
        $DescriptorsHTML = $DescriptorsHTML . ('</div>');
    }

    private function BuildDeCSHTMLTabs(array $results,string &$DeCSHTML)
    {
        $DeCSHTML = $DeCSHTML . ('<ul class = "nav nav-tabs" id = "myTab2" role = "tablist">');
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
                $DeCSHTML = $DeCSHTML . ('<li class = "nav-item">');
                $DeCSHTML = $DeCSHTML . ($msg);
                $DeCSHTML = $DeCSHTML . ('</li>');
                $DescriptorOfKeywordNum++;
                $itemNum++;
            }
            $keywordNum++;
        }
        $DeCSHTML = $DeCSHTML . ('</ul>');
    }

    private function BuildDeCSHTMLContent(array $results,string &$DeCSHTML)
    {
        $DeCSHTML = $DeCSHTML . ('<div class="tab-content" id="myTabContent2">');
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
                $DeCSHTML = $DeCSHTML . ('<div class="tab-pane fade' . $tmp . '" id="' . $itemname . '" role="tabpanel" aria-labelledby="' . $itemname . '-tab">');
                $DeCSHTML = $DeCSHTML . ('<div class="container"><div class="row">');
                $DeCSNum = 1;
                foreach ($DeCSArr as $DeCS) {
                    $DeCSHTML = $DeCSHTML . ('<div class="col-sm-12 col-md-6 col-lg-4 col-xl-3 tableModal table-hover">');
                    $CheckBoxId = 'DescriptorCheckBox' . $keywordNum . '-' . $DescriptorOfKeywordNum . '-' . $DeCSNum;
                    $DeCSHTML = $DeCSHTML . ('<table style="height:100%"><tbody><tr>');
                    $DeCSHTML = $DeCSHTML . ('<td><input class="DeCSCheckBoxElement" id="' . $CheckBoxId . '" name="' . $DeCS . '" data-keyword="' . $keyword . '" data-term="' . $DescriptorTag . '" type="checkbox" checked></td>');
                    $DeCSHTML = $DeCSHTML . ('<td><label for="' . $itemNum . 'b">' . $DeCS . '</label></td>');
                    $DeCSHTML = $DeCSHTML . ('</tr></tbody></table>');
                    $DeCSNum++;
                    $DeCSHTML = $DeCSHTML . ('</div>');
                }
                $DeCSHTML = $DeCSHTML . ('</div></div></div>');
                $itemNum++;
                $DescriptorOfKeywordNum++;
            }
            $keywordNum++;
        }
        $DeCSHTML = $DeCSHTML . ('</div>');
    }

}
