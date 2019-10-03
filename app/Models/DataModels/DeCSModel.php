<?php

namespace PICOExplorer\Models\DataModels;

use PICOExplorer\Models\MainModelsModel;

class DeCSModel extends MainModelsModel
{

    public static final function requestRules()
    {
        return [
            'InitialData' => 'required|array|min:1',
            'InitialData.query' => 'required|string|min:1',
            'InitialData.OldSelectedDescriptors' => 'required|string|min:0',
            'InitialData.ImprovedSearch' => 'required|string|min:0',
            'InitialData.langs' => 'required|array|min:1',
            'InitialData.langs.*' => 'required|string|in:es.pt.en.fr',
            'InitialData.PICOnum' => 'required|integer|size:1',
            'InitialData.mainLanguage' => 'required|string|in:es.pt.en.fr',
            'InitialData.SavedData' => 'nullable|array',
        ];
    }

    public static final function responseRules()
    {
        return [
            'results' => 'required|array|min:1',
            'results.SavedData' => 'required|string|min:1',
            'results.HTMLDescriptors' => 'required|string|min:1',
            'results.HTMLDeCS' => 'required|string|min:1',
        ];
    }

    public final function ControllerTestData()
    {
        return json_encode([
            //"SavedData" => '{"dengue":{"C02.081.270":{"descendants":[],"remaininglangs":[],"pt":{"term":"Dengue","decs":["Dengue","Febre Quebra-Ossos","Febre da Dengue","Infec\u00e7\u00e3o pelo V\u00edrus da Dengue","Infec\u00e7\u00e3o por V\u00edrus da Dengue","Infec\u00e7\u00e3o por V\u00edrus de Dengue"]},"en":{"term":"Dengue","decs":["Dengue","Break Bone Fever","Break-Bone Fever","Breakbone Fever","Classical Dengue","Classical Dengue Fever","Classical Dengue Fevers","Classical Dengues","Dengue Fever"]}},"B04.820.250.350.270":{"descendants":[],"remaininglangs":[],"pt":{"term":"V\u00edrus da Dengue","decs":["V\u00edrus da Dengue","V\u00edrus da Febre Quebra-Ossos"]},"en":{"term":"Dengue Virus","decs":["Dengue Virus","Breakbone Fever Virus","Breakbone Fever Viruses","Dengue Viruses"]}},"C02.081.270.200":{"descendants":[],"remaininglangs":[],"pt":{"term":"Dengue Grave","decs":["Dengue Grave","Dengue Hemorr\u00e1gica","Febre Hemorr\u00e1gica Dengue","Febre Hemorr\u00e1gica da Dengue","Febre Hemorr\u00e1gica devida ao V\u00edrus do Dengue","Febre Hemorr\u00e1gica pelo V\u00edrus da Dengue","Febre Hemorr\u00e1gica pelo V\u00edrus do Dengue","S\u00edndrome de Choque da Dengue"]},"en":{"term":"Severe Dengue","decs":{"0":"Severe Dengue","1":"Dengue Hemorrhagic Fever","2":"Dengue Shock Syndrome","4":"Severe Dengues","5":"Philippine Hemorrhagic Fever","6":"Singapore Hemorrhagic Fever","7":"Thai Hemorrhagic Fever","8":"Hemorrhagic Dengue","9":"Hemorrhagic Dengues"}}},"D20.215.894.899.162":{"descendants":[],"remaininglangs":[],"pt":{"term":"Vacinas contra Dengue","decs":["Vacinas contra Dengue","Vacinas contra a Dengue","Vacinas contra o V\u00edrus da Dengue"]},"en":{"term":"Dengue Vaccines","decs":["Dengue Vaccines"]}}},"zika":{"B04.820.250.350.995":{"descendants":[],"remaininglangs":[],"pt":{"term":"Zika virus","decs":["Zika virus","V\u00edrus Zika","V\u00edrus da Febre Zika","V\u00edrus da Zika","V\u00edrus de Zika","ZIKV","ZikV","Zikavirus"]},"en":{"term":"Zika Virus","decs":{"0":"Zika Virus","2":"ZikV"}}},"C02.081.990":{"descendants":[],"remaininglangs":[],"pt":{"term":"Infec\u00e7\u00e3o por Zika virus","decs":["Infec\u00e7\u00e3o por Zika virus","Doen\u00e7a pelo V\u00edrus Zika","Doen\u00e7a pelo Zika virus","Doen\u00e7a pelo Zikavirus","Doen\u00e7a por V\u00edrus Zika","Doen\u00e7a por Zika virus","Febre Zika","Febre pelo V\u00edrus Zika","Febre por V\u00edrus Zika","Febre por Zika","Febre por Zika virus","Infecc\u00e7\u00e3o por ZIKV","Infec\u00e7\u00e3o pelo Zika virus","Infec\u00e7\u00e3o pelo Zikavirus","Infec\u00e7\u00e3o por V\u00edrus Zika","Infec\u00e7\u00e3o por Zika","Infec\u00e7\u00e3o por Zikavirus"]},"en":{"term":"Zika Virus Infection","decs":["Zika Virus Infection","Zika Virus Disease","Zika Fever","ZikV Infection"]}}}}',
            //"query" => '(((dengue OR Dengue OR "Break Bone Fever" OR "Break-Bone Fever" OR "Breakbone Fever" OR "Classical Dengue" OR "Classical Dengue Fever" OR "Classical Dengue Fevers" OR "Classical Dengues" OR "Dengue Fever" OR Dengue OR "Fiebre Dengue" OR Dengue OR "Febre Quebra-Ossos" OR "Febre da Dengue" OR "Infecção pelo Vírus da Dengue" OR "Infecção por Vírus da Dengue" OR "Infecção por Vírus de Dengue" OR "Dengue Virus" OR "Breakbone Fever Virus" OR "Breakbone Fever Viruses" OR "Dengue Viruses" OR "Dengue Virus" OR "Virus del Dengue" OR "Virus de la Fiebre Rompehuesos" OR "Vírus da Dengue" OR "Vírus da Febre Quebra-Ossos" OR "Severe Dengue" OR "Dengue Hemorrhagic Fever" OR "Dengue Shock Syndrome" OR "Severe Dengue" OR "Severe Dengues" OR "Philippine Hemorrhagic Fever" OR "Singapore Hemorrhagic Fever" OR "Thai Hemorrhagic Fever" OR "Hemorrhagic Dengue" OR "Hemorrhagic Dengues" OR "Dengue Grave" OR "Dengue Hemorrágico" OR "Fiebre Dengue Hemorrágica" OR "Fiebre Hemorrágica Dengue" OR "Fiebre Hemorrágica de Dengue" OR "Síndrome de Choque por Dengue" OR "Síndrome de Shock por Dengue" OR "Dengue Grave" OR "Dengue Hemorrágica" OR "Febre Hemorrágica Dengue" OR "Febre Hemorrágica da Dengue" OR "Febre Hemorrágica devida ao Vírus do Dengue" OR "Febre Hemorrágica pelo Vírus da Dengue" OR "Febre Hemorrágica pelo Vírus do Dengue" OR "Síndrome de Choque da Dengue" OR "Dengue Vaccines" OR "Dengue Vaccines" OR "Vacunas contra el Dengue" OR "Vacinas contra Dengue" OR "Vacinas contra a Dengue" OR "Vacinas contra o Vírus da Dengue")) and ((zika OR "Zika Virus" OR "Zika Virus" OR ZikV OR "Virus Zika" OR "Virus de Zika" OR "Virus de la Fiebre Zika" OR "Virus del Zika" OR ZIKV OR ZikV OR "Zika virus" OR Zikavirus OR "Zika virus" OR "Vírus Zika" OR "Vírus da Febre Zika" OR "Vírus da Zika" OR "Vírus de Zika" OR ZIKV OR ZikV OR Zikavirus OR "Zika Virus Infection" OR "Zika Virus Disease" OR "Zika Fever" OR "ZikV Infection" OR "Zika Virus Infection" OR "Infección por el Virus Zika" OR "Enfermedad del Virus Zika" OR "Enfermedad por Virus Zika" OR "Enfermedad por ZIKV" OR "Enfermedad por Zika" OR "Enfermedad por Zika virus" OR "Enfermedad por el Virus Zika" OR "Enfermedad por el Virus de Zika" OR "Enfermedad por el Virus del Zika" OR "Enfermedad por el Zikavirus" OR "Fiebre Zika" OR "Fiebre por Virus Zika" OR "Fiebre por el Virus Zika" OR "Infección del Virus Zika" OR "Infección por Virus Zika" OR "Infección por Vzika" OR "Infección por ZIKV" OR "Infección por Zika virus" OR "Infección por Zikavirus" OR "Infección por el Virus de Zika" OR "Infección por el Virus del Zika" OR "Infecção por Zika virus" OR "Doença pelo Vírus Zika" OR "Doença pelo Zika virus" OR "Doença pelo Zikavirus" OR "Doença por Vírus Zika" OR "Doença por Zika virus" OR "Febre Zika" OR "Febre pelo Vírus Zika" OR "Febre por Vírus Zika" OR "Febre por Zika" OR "Febre por Zika virus" OR "Infeccção por ZIKV" OR "Infecção pelo Zika virus" OR "Infecção pelo Zikavirus" OR "Infecção por Vírus Zika" OR "Infecção por Zika" OR "Infecção por Zikavirus"))) OR (alfabeta)',

            'SavedData' => '{"zika":{"B04.820.250.350.995":{"en":{"term":"Zika Virus","decs":["ZikV"]}},"C02.081.990":{"en":{"term":"Zika Virus Infection","decs":["Zika Virus Disease","Zika Fever","ZikV Infection"]}}}}',
            "query" => '(zika OR "Zika Virus" OR ZikV OR "Zika Virus Infection" OR "Zika Virus Disease" OR "Zika Fever" OR "ZikV Infection") OR (alfabeta)',
            "ImprovedSearch" => 'alfabeta or "termino compuesto"',
            "langs" => [
                0 => "en",
            ],
            "PICOnum" => 1,
            "mainLanguage" => "en",
        ]);
    }

    public static final function AttributeRules()
    {
        return [
            'decodedKeywordList' => ['required|array|min:0'],
            'PreviousData' => ['required|array|min:0'],
            'SavedData' => ['required|array|min:0'],
            'decodedOldDescriptors' => ['required|array|min:1'],
            'ProcessedDescriptors' => ['required|array|min:0'],
            'QuerySplit' => ['required|array'],
            'KeywordList' => ['array|min:0'],
            'AllKeywords' => ['array|min:0'],
            'ProcessedDeCS' => ['required|array|min:0'],
            'DescriptorsHTML' => ['required|string|min:1'],
            'DeCSHTML' => ['required|string|min:1'],
        ];
    }
}
