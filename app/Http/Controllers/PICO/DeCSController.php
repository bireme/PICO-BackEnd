<?php

namespace PICOExplorer\Http\Controllers\PICO;

use PICOExplorer\Models\MainModels\DeCSModel;
use PICOExplorer\Facades\DeCSProcessFacade;
use PICOExplorer\Models\MainModels\MainModelsModel;
use PICOExplorer\Services\AdvancedLogger\Services\Timer;

class DeCSController extends BaseMainController implements MainControllerInterface
{

    public static final function requestRules(){
        return [

        ];
    }

    public static final function responseRules(){
        return [

        ];
    }
    public function create()
    {
        return new DeCSModel();
    }

    public function MainOperation(MainModelsModel $model,array $responseRules, $globalTimer)
    {
        DeCSProcessFacade::get($model, $responseRules,$globalTimer);
    }

    public function TestData()
    {
        return [
            ["PreviousData" => '["dengue" => ["content" => ["Dengue" => ["C02.081.270" => ["en" => ["Break Bone Fever","Break-Bone Fever","Breakbone Fever","Classical Dengue","Classical Dengue Fever","Classical Dengue Fevers","Classical Dengues","Dengue Fever","Classical Dengue Fever","Classical Dengue","Break-Bone Fever","Breakbone Fever","Dengue Fever","Dengue","Dengue Hemorrhagic Fever","Dengue Shock Syndrome","Severe Dengue","Severe Dengues","Philippine Hemorrhagic Fever","Singapore Hemorrhagic Fever","Thai Hemorrhagic Fever","Hemorrhagic Dengue","Hemorrhagic Dengues","Dengue Hemorrhagic Fever","Philippine Hemorrhagic Fever","Severe Dengues","Singapore Hemorrhagic Fever","Thai Hemorrhagic Fever","Severe Dengue"],"pt" => ["Febre Quebra-Ossos","Febre da Dengue","Infec\\u00e7\\u00e3o pelo V\\u00edrus da Dengue","Infec\\u00e7\\u00e3o por V\\u00edrus da Dengue","Infec\\u00e7\\u00e3o por V\\u00edrus de Dengue","Dengue","Dengue Hemorr\\u00e1gica","Febre Hemorr\\u00e1gica Dengue","Febre Hemorr\\u00e1gica da Dengue","Febre Hemorr\\u00e1gica devida ao V\\u00edrus do Dengue","Febre Hemorr\\u00e1gica pelo V\\u00edrus da Dengue","Febre Hemorr\\u00e1gica pelo V\\u00edrus do Dengue","S\\u00edndrome de Choque da Dengue","Dengue Grave"],"es" => ["Fiebre Dengue","Dengue","Dengue Hemorr\\u00e1gico","Fiebre Dengue Hemorr\\u00e1gica","Fiebre Hemorr\\u00e1gica Dengue","Fiebre Hemorr\\u00e1gica de Dengue","S\\u00edndrome de Choque por Dengue","S\\u00edndrome de Shock por Dengue","Dengue Grave"]]],"Dengue Virus" => ["B04.820.250.350.270" => ["en" => ["Breakbone Fever Virus","Breakbone Fever Viruses","Dengue Viruses","Breakbone Fever Virus","Breakbone Fever Viruses","Breakbone Fever Virus","Dengue Virus","Breakbone Fever Viruses","Dengue Viruses","Dengue Virus"],"pt" => ["V\\u00edrus da Febre Quebra-Ossos","V\\u00edrus da Dengue"],"es" => ["Virus de la Fiebre Rompehuesos","Virus del Dengue"]]],"Severe Dengue" => ["C02.081.270.200" => ["en" => ["Dengue Hemorrhagic Fever","Dengue Shock Syndrome","Severe Dengue","Severe Dengues","Philippine Hemorrhagic Fever","Singapore Hemorrhagic Fever","Thai Hemorrhagic Fever","Hemorrhagic Dengue","Hemorrhagic Dengues","Dengue Hemorrhagic Fever","Philippine Hemorrhagic Fever","Severe Dengues","Singapore Hemorrhagic Fever","Thai Hemorrhagic Fever","Severe Dengue"],"pt" => ["Dengue Hemorr\\u00e1gica","Febre Hemorr\\u00e1gica Dengue","Febre Hemorr\\u00e1gica da Dengue","Febre Hemorr\\u00e1gica devida ao V\\u00edrus do Dengue","Febre Hemorr\\u00e1gica pelo V\\u00edrus da Dengue","Febre Hemorr\\u00e1gica pelo V\\u00edrus do Dengue","S\\u00edndrome de Choque da Dengue","Dengue Grave"],"es" => ["Dengue Hemorr\\u00e1gico","Fiebre Dengue Hemorr\\u00e1gica","Fiebre Hemorr\\u00e1gica Dengue","Fiebre Hemorr\\u00e1gica de Dengue","S\\u00edndrome de Choque por Dengue","S\\u00edndrome de Shock por Dengue","Dengue Grave"]]],"Dengue Vaccines" => ["D20.215.894.899.162" => ["en" => ["Dengue Vaccines","Dengue Vaccines"],"pt" => ["Vacinas contra a Dengue","Vacinas contra o V\\u00edrus da Dengue","Vacinas contra Dengue"],"es" => ["Vacunas contra el Dengue"]]]],"lang" => ["en"]]]',"query" => '((Dengue OR ("Break Bone Fever" OR "Break-Bone Fever" OR "Breakbone Fever" OR "Classical Dengue" OR "Classical Dengue Fever" OR "Classical Dengue Fevers" OR "Classical Dengues" OR "Dengue Fever" OR Dengue OR "Dengue Hemorrhagic Fever" OR "Dengue Shock Syndrome" OR "Severe Dengue" OR "Severe Dengues" OR "Philippine Hemorrhagic Fever" OR "Singapore Hemorrhagic Fever" OR "Thai Hemorrhagic Fever" OR "Hemorrhagic Dengue" OR "Hemorrhagic Dengues") OR ("Breakbone Fever Virus" OR "Breakbone Fever Viruses" OR "Dengue Viruses" OR "Dengue Virus") OR ("Dengue Hemorrhagic Fever" OR "Dengue Shock Syndrome" OR "Severe Dengue" OR "Severe Dengues" OR "Philippine Hemorrhagic Fever" OR "Singapore Hemorrhagic Fever" OR "Thai Hemorrhagic Fever" OR "Hemorrhagic Dengue" OR "Hemorrhagic Dengues") OR ("Dengue Vaccines"))) or zika',"langs" => ["en"],"PICOnum" => "2","mainLanguage" => "en"]
        ];
    }

}
