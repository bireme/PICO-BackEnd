<?php

namespace PICOExplorer\Models\DataModels;

use PICOExplorer\Models\MainModelsModel;

class KeywordManagerModel extends MainModelsModel
{

    public static final function requestRules()
    {
        return [
            'InitialData' => 'required|array|min:1',
            'InitialData.query' => 'required|string|min:1',
            'InitialData.langs' => 'required|array|min:1',
            'InitialData.langs.*' => 'required|string|in:es.pt.en.fr',
        ];
    }

    public static final function responseRules()
    {
        return [
            'results' => 'required|array|min:1',
            'results.KeywordList' => 'array|min:0',
        ];
    }

    public final function ControllerTestData()
    {
        return json_encode([
            'SavedData' => '{"dengue":{"C02.081.270":{"descendants":["C02.081.270.200"],"en":{"term":"Dengue","decs":["Break Bone Fever","Break-Bone Fever","Breakbone Fever","Classical Dengue","Classical Dengue Fever","Classical Dengue Fevers","Classical Dengues","Dengue Fever"]},"es":{"term":"Dengue","decs":["Fiebre Dengue"]},"pt":{"term":"Dengue","decs":["Febre Quebra-Ossos","Febre da Dengue","Infec\u00e7\u00e3o pelo V\u00edrus da Dengue","Infec\u00e7\u00e3o por V\u00edrus da Dengue","Infec\u00e7\u00e3o por V\u00edrus de Dengue"]}},"B04.820.250.350.270":{"descendants":[],"en":{"term":"Dengue Virus","decs":{"0":"Breakbone Fever Virus","1":"Breakbone Fever Viruses","2":"Dengue Viruses","6":"Dengue Virus"}},"es":{"term":"Virus del Dengue","decs":["Virus de la Fiebre Rompehuesos"]},"pt":{"term":"V\u00edrus da Dengue","decs":["V\u00edrus da Febre Quebra-Ossos"]}},"C02.081.270.200":{"descendants":[],"en":{"term":"Severe Dengue","decs":["Dengue Hemorrhagic Fever","Dengue Shock Syndrome","Severe Dengue","Severe Dengues","Philippine Hemorrhagic Fever","Singapore Hemorrhagic Fever","Thai Hemorrhagic Fever","Hemorrhagic Dengue","Hemorrhagic Dengues"]},"es":{"term":"Dengue Grave","decs":["Dengue Hemorr\u00e1gico","Fiebre Dengue Hemorr\u00e1gica","Fiebre Hemorr\u00e1gica Dengue","Fiebre Hemorr\u00e1gica de Dengue","S\u00edndrome de Choque por Dengue","S\u00edndrome de Shock por Dengue"]},"pt":{"term":"Dengue Grave","decs":["Dengue Hemorr\u00e1gica","Febre Hemorr\u00e1gica Dengue","Febre Hemorr\u00e1gica da Dengue","Febre Hemorr\u00e1gica devida ao V\u00edrus do Dengue","Febre Hemorr\u00e1gica pelo V\u00edrus da Dengue","Febre Hemorr\u00e1gica pelo V\u00edrus do Dengue","S\u00edndrome de Choque da Dengue"]}},"D20.215.894.899.162":{"descendants":[],"en":{"term":"Dengue Vaccines","decs":["Dengue Vaccines"]},"es":{"term":"Vacunas contra el Dengue","decs":[]},"pt":{"term":"Vacinas contra Dengue","decs":["Vacinas contra a Dengue","Vacinas contra o V\u00edrus da Dengue"]}}},"zika":{"B04.820.250.350.995":{"descendants":[],"en":{"term":"Zika Virus","decs":["Zika Virus","ZikV"]},"es":{"term":"Virus Zika","decs":["Virus de Zika","Virus de la Fiebre Zika","Virus del Zika","ZIKV","ZikV","Zika virus","Zikavirus"]},"pt":{"term":"Zika virus","decs":["V\u00edrus Zika","V\u00edrus da Febre Zika","V\u00edrus da Zika","V\u00edrus de Zika","ZIKV","ZikV","Zikavirus"]}},"C02.081.990":{"descendants":[],"en":{"term":"Zika Virus Infection","decs":["Zika Virus Disease","Zika Fever","ZikV Infection","Zika Virus Infection"]},"es":{"term":"Infecci\u00f3n por el Virus Zika","decs":["Enfermedad del Virus Zika","Enfermedad por Virus Zika","Enfermedad por ZIKV","Enfermedad por Zika","Enfermedad por Zika virus","Enfermedad por el Virus Zika","Enfermedad por el Virus de Zika","Enfermedad por el Virus del Zika","Enfermedad por el Zikavirus","Fiebre Zika","Fiebre por Virus Zika","Fiebre por el Virus Zika","Infecci\u00f3n del Virus Zika","Infecci\u00f3n por Virus Zika","Infecci\u00f3n por Vzika","Infecci\u00f3n por ZIKV","Infecci\u00f3n por Zika virus","Infecci\u00f3n por Zikavirus","Infecci\u00f3n por el Virus de Zika","Infecci\u00f3n por el Virus del Zika"]},"pt":{"term":"Infec\u00e7\u00e3o por Zika virus","decs":["Doen\u00e7a pelo V\u00edrus Zika","Doen\u00e7a pelo Zika virus","Doen\u00e7a pelo Zikavirus","Doen\u00e7a por V\u00edrus Zika","Doen\u00e7a por Zika virus","Febre Zika","Febre pelo V\u00edrus Zika","Febre por V\u00edrus Zika","Febre por Zika","Febre por Zika virus","Infecc\u00e7\u00e3o por ZIKV","Infec\u00e7\u00e3o pelo Zika virus","Infec\u00e7\u00e3o pelo Zikavirus","Infec\u00e7\u00e3o por V\u00edrus Zika","Infec\u00e7\u00e3o por Zika","Infec\u00e7\u00e3o por Zikavirus"]}}}}',
            'query' => '((dengue OR Dengue OR "Break Bone Fever" OR "Break-Bone Fever" OR "Breakbone Fever" OR "Classical Dengue" OR "Classical Dengue Fever" OR "Classical Dengue Fevers" OR "Classical Dengues" OR "Dengue Fever" OR Dengue OR "Fiebre Dengue" OR Dengue OR "Febre Quebra-Ossos" OR "Febre da Dengue" OR "Infecção pelo Vírus da Dengue" OR "Infecção por Vírus da Dengue" OR "Infecção por Vírus de Dengue" OR "Dengue Virus" OR "Breakbone Fever Virus" OR "Breakbone Fever Viruses" OR "Dengue Viruses" OR "Dengue Virus" OR "Virus del Dengue" OR "Virus de la Fiebre Rompehuesos" OR "Vírus da Dengue" OR "Vírus da Febre Quebra-Ossos" OR "Severe Dengue" OR "Dengue Hemorrhagic Fever" OR "Dengue Shock Syndrome" OR "Severe Dengue" OR "Severe Dengues" OR "Philippine Hemorrhagic Fever" OR "Singapore Hemorrhagic Fever" OR "Thai Hemorrhagic Fever" OR "Hemorrhagic Dengue" OR "Hemorrhagic Dengues" OR "Dengue Grave" OR "Dengue Hemorrágico" OR "Fiebre Dengue Hemorrágica" OR "Fiebre Hemorrágica Dengue" OR "Fiebre Hemorrágica de Dengue" OR "Síndrome de Choque por Dengue" OR "Síndrome de Shock por Dengue" OR "Dengue Grave" OR "Dengue Hemorrágica" OR "Febre Hemorrágica Dengue" OR "Febre Hemorrágica da Dengue" OR "Febre Hemorrágica devida ao Vírus do Dengue" OR "Febre Hemorrágica pelo Vírus da Dengue" OR "Febre Hemorrágica pelo Vírus do Dengue" OR "Síndrome de Choque da Dengue" OR "Dengue Vaccines" OR "Dengue Vaccines" OR "Vacunas contra el Dengue" OR "Vacinas contra Dengue" OR "Vacinas contra a Dengue" OR "Vacinas contra o Vírus da Dengue") OR (zika OR "Zika Virus" OR "Zika Virus" OR ZikV OR "Virus Zika" OR "Virus de Zika" OR "Virus de la Fiebre Zika" OR "Virus del Zika" OR ZIKV OR ZikV OR "Zika virus" OR Zikavirus OR "Zika virus" OR "Vírus Zika" OR "Vírus da Febre Zika" OR "Vírus da Zika" OR "Vírus de Zika" OR ZIKV OR ZikV OR Zikavirus OR "Zika Virus Infection" OR "Zika Virus Disease" OR "Zika Fever" OR "ZikV Infection" OR "Zika Virus Infection" OR "Infección por el Virus Zika" OR "Enfermedad del Virus Zika" OR "Enfermedad por Virus Zika" OR "Enfermedad por ZIKV" OR "Enfermedad por Zika" OR "Enfermedad por Zika virus" OR "Enfermedad por el Virus Zika" OR "Enfermedad por el Virus de Zika" OR "Enfermedad por el Virus del Zika" OR "Enfermedad por el Zikavirus" OR "Fiebre Zika" OR "Fiebre por Virus Zika" OR "Fiebre por el Virus Zika" OR "Infección del Virus Zika" OR "Infección por Virus Zika" OR "Infección por Vzika" OR "Infección por ZIKV" OR "Infección por Zika virus" OR "Infección por Zikavirus" OR "Infección por el Virus de Zika" OR "Infección por el Virus del Zika" OR "Infecção por Zika virus" OR "Doença pelo Vírus Zika" OR "Doença pelo Zika virus" OR "Doença pelo Zikavirus" OR "Doença por Vírus Zika" OR "Doença por Zika virus" OR "Febre Zika" OR "Febre pelo Vírus Zika" OR "Febre por Vírus Zika" OR "Febre por Zika" OR "Febre por Zika virus" OR "Infeccção por ZIKV" OR "Infecção pelo Zika virus" OR "Infecção pelo Zikavirus" OR "Infecção por Vírus Zika" OR "Infecção por Zika" OR "Infecção por Zikavirus"))',
            'langs' => [
                0 => 'en',
            ],
            'PICOnum' => 1,
            'mainLanguage' => 'en',
        ]);
    }

    public static final function AttributeRules()
    {
        return [
            'ExploreData' => ['array|min:0'],
            //'FormData' => ['array|min:0'],
            'PreviousData' => ['required|array|min:0'],

            //'HTML' => ['string|min:1'],
        ];
    }

}
