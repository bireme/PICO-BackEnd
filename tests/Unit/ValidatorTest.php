<?php

namespace Tests\Unit;

use Tests\TestCase;
use SpecialValidatorFacade;
use Throwable;

class ValidatorTest extends TestCase
{
    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function correctInput()
    {
        $rules = [
            'InitialData' => 'required|array|min:1',
            'InitialData.PICOnum' => 'required|integer|size:1',
            'InitialData.mainLanguage' => 'required|string|in:es.pt.en.fr',
            'InitialData.queryobject' => 'required|array|size:5',
            'InitialData.queryobject.*' => 'required|array|distinct|in:PICO1.PICO2.PICO3.PICO4.PICO5',
            'InitialData.queryobject.*.*' => 'required|array|size:2|distinct|in:field.query',
            'InitialData.queryobject.*.*.query' => 'required|string|min:0',
            'InitialData.queryobject.*.*.field' => 'required|integer',
        ];

        $data = [
            'SavedData' => json_encode(["dengue" => ["C02.081.270" => ["en" => ["decs" => ["0" => "Break Bone Fever","1" => "Break-Bone Fever","2" => "Breakbone Fever","3" => "Classical Dengue","4" => "Classical Dengue Fever","5" => "Classical Dengue Fevers","6" => "Classical Dengues","7" => "Dengue Fever","8" => "Severe Dengue","9" => "Dengue Hemorrhagic Fever","10" => "Dengue Shock Syndrome","12" => "Severe Dengues","13" => "Philippine Hemorrhagic Fever","14" => "Singapore Hemorrhagic Fever","15" => "Thai Hemorrhagic Fever","16" => "Hemorrhagic Dengue","17" => "Hemorrhagic Dengues"],"term" => "Dengue"],"pt" => ["decs" => ["Febre Quebra-Ossos","Febre da Dengue","Infec\u00e7\u00e3o pelo V\u00edrus da Dengue","Infec\u00e7\u00e3o por V\u00edrus da Dengue","Infec\u00e7\u00e3o por V\u00edrus de Dengue","Dengue Grave","Dengue Hemorr\u00e1gica","Febre Hemorr\u00e1gica Dengue","Febre Hemorr\u00e1gica da Dengue","Febre Hemorr\u00e1gica devida ao V\u00edrus do Dengue","Febre Hemorr\u00e1gica pelo V\u00edrus da Dengue","Febre Hemorr\u00e1gica pelo V\u00edrus do Dengue","S\u00edndrome de Choque da Dengue"],"term" => "Dengue"],"es" => ["decs" => ["Fiebre Dengue","Dengue Grave","Dengue Hemorr\u00e1gico","Fiebre Dengue Hemorr\u00e1gica","Fiebre Hemorr\u00e1gica Dengue","Fiebre Hemorr\u00e1gica de Dengue","S\u00edndrome de Choque por Dengue","S\u00edndrome de Shock por Dengue"],"term" => "Dengue"]],"B04.820.250.350.270" => ["en" => ["decs" => ["0" => "Breakbone Fever Virus","1" => "Breakbone Fever Viruses","2" => "Dengue Viruses","6" => "Dengue Virus"],"term" => "Dengue Virus"],"pt" => ["decs" => ["V\u00edrus da Febre Quebra-Ossos"],"term" => "V\u00edrus da Dengue"],"es" => ["decs" => ["Virus de la Fiebre Rompehuesos"],"term" => "Virus del Dengue"]],"C02.081.270.200" => ["en" => ["decs" => ["Dengue Hemorrhagic Fever","Dengue Shock Syndrome","Severe Dengue","Severe Dengues","Philippine Hemorrhagic Fever","Singapore Hemorrhagic Fever","Thai Hemorrhagic Fever","Hemorrhagic Dengue","Hemorrhagic Dengues"],"term" => "Severe Dengue"],"pt" => ["decs" => ["Dengue Hemorr\u00e1gica","Febre Hemorr\u00e1gica Dengue","Febre Hemorr\u00e1gica da Dengue","Febre Hemorr\u00e1gica devida ao V\u00edrus do Dengue","Febre Hemorr\u00e1gica pelo V\u00edrus da Dengue","Febre Hemorr\u00e1gica pelo V\u00edrus do Dengue","S\u00edndrome de Choque da Dengue"],"term" => "Dengue Grave"],"es" => ["decs" => ["Dengue Hemorr\u00e1gico","Fiebre Dengue Hemorr\u00e1gica","Fiebre Hemorr\u00e1gica Dengue","Fiebre Hemorr\u00e1gica de Dengue","S\u00edndrome de Choque por Dengue","S\u00edndrome de Shock por Dengue"],"term" => "Dengue Grave"]],"D20.215.894.899.162" => ["en" => ["decs" => ["Dengue Vaccines"],"term" => "Dengue Vaccines"],"pt" => ["decs" => ["Vacinas contra a Dengue","Vacinas contra o V\u00edrus da Dengue"],"term" => "Vacinas contra Dengue"],"es" => ["decs" => [],"term" => "Vacunas contra el Dengue"]]]]),

            //"PreviousData" => ["dengue" => ["content" => ["Dengue" => ["C02.081.270" => ["en" => ["Break Bone Fever","Break-Bone Fever","Breakbone Fever","Classical Dengue","Classical Dengue Fever","Classical Dengue Fevers","Classical Dengues","Dengue Fever","Classical Dengue Fever","Classical Dengue","Break-Bone Fever","Breakbone Fever","Dengue Fever","Dengue","Dengue Hemorrhagic Fever","Dengue Shock Syndrome","Severe Dengue","Severe Dengues","Philippine Hemorrhagic Fever","Singapore Hemorrhagic Fever","Thai Hemorrhagic Fever","Hemorrhagic Dengue","Hemorrhagic Dengues","Dengue Hemorrhagic Fever","Philippine Hemorrhagic Fever","Severe Dengues","Singapore Hemorrhagic Fever","Thai Hemorrhagic Fever","Severe Dengue"],"pt" => ["Febre Quebra-Ossos","Febre da Dengue","Infec\\u00e7\\u00e3o pelo V\\u00edrus da Dengue","Infec\\u00e7\\u00e3o por V\\u00edrus da Dengue","Infec\\u00e7\\u00e3o por V\\u00edrus de Dengue","Dengue","Dengue Hemorr\\u00e1gica","Febre Hemorr\\u00e1gica Dengue","Febre Hemorr\\u00e1gica da Dengue","Febre Hemorr\\u00e1gica devida ao V\\u00edrus do Dengue","Febre Hemorr\\u00e1gica pelo V\\u00edrus da Dengue","Febre Hemorr\\u00e1gica pelo V\\u00edrus do Dengue","S\\u00edndrome de Choque da Dengue","Dengue Grave"],"es" => ["Fiebre Dengue","Dengue","Dengue Hemorr\\u00e1gico","Fiebre Dengue Hemorr\\u00e1gica","Fiebre Hemorr\\u00e1gica Dengue","Fiebre Hemorr\\u00e1gica de Dengue","S\\u00edndrome de Choque por Dengue","S\\u00edndrome de Shock por Dengue","Dengue Grave"]]],"Dengue Virus" => ["B04.820.250.350.270" => ["en" => ["Breakbone Fever Virus","Breakbone Fever Viruses","Dengue Viruses","Breakbone Fever Virus","Breakbone Fever Viruses","Breakbone Fever Virus","Dengue Virus","Breakbone Fever Viruses","Dengue Viruses","Dengue Virus"],"pt" => ["V\\u00edrus da Febre Quebra-Ossos","V\\u00edrus da Dengue"],"es" => ["Virus de la Fiebre Rompehuesos","Virus del Dengue"]]],"Severe Dengue" => ["C02.081.270.200" => ["en" => ["Dengue Hemorrhagic Fever","Dengue Shock Syndrome","Severe Dengue","Severe Dengues","Philippine Hemorrhagic Fever","Singapore Hemorrhagic Fever","Thai Hemorrhagic Fever","Hemorrhagic Dengue","Hemorrhagic Dengues","Dengue Hemorrhagic Fever","Philippine Hemorrhagic Fever","Severe Dengues","Singapore Hemorrhagic Fever","Thai Hemorrhagic Fever","Severe Dengue"],"pt" => ["Dengue Hemorr\\u00e1gica","Febre Hemorr\\u00e1gica Dengue","Febre Hemorr\\u00e1gica da Dengue","Febre Hemorr\\u00e1gica devida ao V\\u00edrus do Dengue","Febre Hemorr\\u00e1gica pelo V\\u00edrus da Dengue","Febre Hemorr\\u00e1gica pelo V\\u00edrus do Dengue","S\\u00edndrome de Choque da Dengue","Dengue Grave"],"es" => ["Dengue Hemorr\\u00e1gico","Fiebre Dengue Hemorr\\u00e1gica","Fiebre Hemorr\\u00e1gica Dengue","Fiebre Hemorr\\u00e1gica de Dengue","S\\u00edndrome de Choque por Dengue","S\\u00edndrome de Shock por Dengue","Dengue Grave"]]],"Dengue Vaccines" => ["D20.215.894.899.162" => ["en" => ["Dengue Vaccines","Dengue Vaccines"],"pt" => ["Vacinas contra a Dengue","Vacinas contra o V\\u00edrus da Dengue","Vacinas contra Dengue"],"es" => ["Vacunas contra el Dengue"]]]],"lang" => ["en"]]],
            //"query" => '((Dengue OR ("Break Bone Fever" OR "Break-Bone Fever" OR "Breakbone Fever" OR "Classical Dengue" OR "Classical Dengue Fever" OR "Classical Dengue Fevers" OR "Classical Dengues" OR "Dengue Fever" OR Dengue OR "Dengue Hemorrhagic Fever" OR "Dengue Shock Syndrome" OR "Severe Dengue" OR "Severe Dengues" OR "Philippine Hemorrhagic Fever" OR "Singapore Hemorrhagic Fever" OR "Thai Hemorrhagic Fever" OR "Hemorrhagic Dengue" OR "Hemorrhagic Dengues") OR ("Breakbone Fever Virus" OR "Breakbone Fever Viruses" OR "Dengue Viruses" OR "Dengue Virus") OR ("Dengue Hemorrhagic Fever" OR "Dengue Shock Syndrome" OR "Severe Dengue" OR "Severe Dengues" OR "Philippine Hemorrhagic Fever" OR "Singapore Hemorrhagic Fever" OR "Thai Hemorrhagic Fever" OR "Hemorrhagic Dengue" OR "Hemorrhagic Dengues") OR ("Dengue Vaccines"))) or zika',
            'query' => 'dengue',
            "langs" => ["en"],
            "PICOnum" => "2",
            "mainLanguage" => "en",
        ];

        $res = false;
        try{
            SpecialValidatorFacade::SpecialValidate($data, $rules, 'TestCorrectInput', 'TestCorrectInput');
            $res=true;
        }catch(Throwable $ex){
        }
        $this->assertTrue($res);
    }

}
