<?php


namespace PICOExplorer\Http\Controllers\Test;

use PICOExplorer\Facades\DeCSBIREMEFacade;
use PICOExplorer\Http\Traits\ArrayExplorerTrait;
use PICOExplorer\Http\Traits\RenderArrayAsStringTrait;

class TestController
{

    use ArrayExplorerTrait;
    use RenderArrayAsStringTrait;

    public function index()
    {
        $txt = '{"InitialData":{"PICOnum":"2","queryobject":{"PICO1":{"query":"dengue","field":null},"PICO2":{"query":"(((Dengue OR (\"Break bone fever\" OR \"Break-bone fever\" OR \"Breakbone fever\" OR \"Classical dengue\" OR \"Classical dengue fever\" OR \"Classical dengue fevers\" OR \"Classical dengues\" OR \"Dengue fever\" OR Dengue OR \"Dengue hemorrhagic fever\" OR \"Dengue shock syndrome\" OR \"Severe dengue\" OR \"Severe dengues\" OR \"Philippine hemorrhagic fever\" OR \"Singapore hemorrhagic fever\" OR \"Thai hemorrhagic fever\" OR \"Hemorrhagic dengue\" OR \"Hemorrhagic dengues\") OR (\"Breakbone fever virus\" OR \"Breakbone fever viruses\" OR \"Dengue viruses\" OR \"Dengue virus\") OR (\"Dengue hemorrhagic fever\" OR \"Dengue shock syndrome\" OR \"Severe dengue\" OR \"Severe dengues\" OR \"Philippine hemorrhagic fever\" OR \"Singapore hemorrhagic fever\" OR \"Thai hemorrhagic fever\" OR \"Hemorrhagic dengue\" OR \"Hemorrhagic dengues\") OR (\"Dengue vaccines\"))) OR (Zika OR (\"Zika Virus\" OR ZikV) OR (\"Zika Virus Disease\" OR \"Zika Fever\" OR \"ZikV Infection\" OR \"Zika Virus Infection\")))","field":null},"PICO3":{"query":null,"field":null},"PICO4":{"query":"","field":null},"PICO5":{"query":null,"field":-1}},"mainLanguage":"en"}}';
        $arr = $this->ExploreArray($txt);
        $arr['InitialData']['mainLanguage'] = new DeCSBIREMEFacade();
        $txt = json_encode($arr);
        $arr = $this->ExploreArray($txt);
        $msg = $this->RenderArrayAsString('Test',$arr);
        $msg = dd($msg);
        $msg= $msg .PHP_EOL.'</br>'.'hola';
        return $msg;
    }

}
