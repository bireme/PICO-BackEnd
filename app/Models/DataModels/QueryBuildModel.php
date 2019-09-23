<?php

namespace PICOExplorer\Models\DataModels;

use PICOExplorer\Models\MainModelsModel;

class QueryBuildModel extends MainModelsModel
{

    public static final function requestRules()
    {
        return [
            'InitialData' => 'required|array|min:1',
            'InitialData.QuerySplit' => 'required|array',
            'InitialData.OldSelectedDescriptors' => 'required|string|min:0',
            'InitialData.SelectedDescriptors' => 'required|array|min:1',
            'InitialData.ImproveSearchQuery' => 'required|array|min:1',
        ];
    }

    public static final function responseRules()
    {
        return [
            'results' => 'required|array|min:1',
            'results.newQuery' => 'required|string|min:1',
            'results.OldSelectedDescriptors' => 'required|array|min:1',
        ];
    }

    public final function ControllerTestData()
    {
        return json_encode([
            "SelectedDescriptors" => [
                "dengue" => [
                    "Dengue" => [
                        0 => "Dengue",
                        1 => "Break Bone Fever",
                        2 => "Break-Bone Fever",
                        3 => "Breakbone Fever",
                        4 => "Classical Dengue",
                        5 => "Classical Dengue Fever",
                        6 => "Classical Dengue Fevers",
                        7 => "Classical Dengues",
                        8 => "Dengue Fever",
                        9 => "Dengue",
                        10 => "Fiebre Dengue",
                        11 => "Dengue",
                        12 => "Febre Quebra-Ossos",
                        13 => "Febre da Dengue",
                        14 => "Infecção pelo Vírus da Dengue",
                        15 => "Infecção por Vírus da Dengue",
                        16 => "Infecção por Vírus de Dengue",
                    ],
                    "Dengue Virus" => [
                        0 => "Dengue Virus",
                        1 => "Breakbone Fever Virus",
                        2 => "Breakbone Fever Viruses",
                        3 => "Dengue Viruses",
                        4 => "Dengue Virus",
                        5 => "Virus del Dengue",
                        6 => "Virus de la Fiebre Rompehuesos",
                        7 => "Vírus da Dengue",
                        8 => "Vírus da Febre Quebra-Ossos",
                    ],
                    "Severe Dengue" => [
                        0 => "Severe Dengue",
                        1 => "Dengue Hemorrhagic Fever",
                        2 => "Dengue Shock Syndrome",
                        3 => "Severe Dengue",
                        4 => "Severe Dengues",
                        5 => "Philippine Hemorrhagic Fever",
                        6 => "Singapore Hemorrhagic Fever",
                        7 => "Thai Hemorrhagic Fever",
                        8 => "Hemorrhagic Dengue",
                        9 => "Hemorrhagic Dengues",
                        10 => "Dengue Grave",
                        11 => "Dengue Hemorrágico",
                        12 => "Fiebre Dengue Hemorrágica",
                        13 => "Fiebre Hemorrágica Dengue",
                        14 => "Fiebre Hemorrágica de Dengue",
                        15 => "Síndrome de Choque por Dengue",
                        16 => "Síndrome de Shock por Dengue",
                        17 => "Dengue Grave",
                        18 => "Dengue Hemorrágica",
                        19 => "Febre Hemorrágica Dengue",
                        20 => "Febre Hemorrágica da Dengue",
                        21 => "Febre Hemorrágica devida ao Vírus do Dengue",
                        22 => "Febre Hemorrágica pelo Vírus da Dengue",
                        23 => "Febre Hemorrágica pelo Vírus do Dengue",
                        24 => "Síndrome de Choque da Dengue",
                    ],
                    "Dengue Vaccines" => [
                        0 => "Dengue Vaccines",
                        1 => "Dengue Vaccines",
                        2 => "Vacunas contra el Dengue",
                        3 => "Vacinas contra Dengue",
                        4 => "Vacinas contra a Dengue",
                        5 => "Vacinas contra o Vírus da Dengue",
                    ],
                ],
                "zika" => [
                    "Zika Virus" => [
                        0 => "Zika Virus",
                        1 => "Zika Virus",
                        2 => "ZikV",
                        3 => "Virus Zika",
                        4 => "Virus de Zika",
                        5 => "Virus de la Fiebre Zika",
                        6 => "Virus del Zika",
                        7 => "ZIKV",
                        8 => "ZikV",
                        9 => "Zika virus",
                        10 => "Zikavirus",
                        11 => "Zika virus",
                        12 => "Vírus Zika",
                        13 => "Vírus da Febre Zika",
                        14 => "Vírus da Zika",
                        15 => "Vírus de Zika",
                        16 => "ZIKV",
                        17 => "ZikV",
                        18 => "Zikavirus",
                    ],
                    "Zika Virus Infection" => [
                        0 => "Zika Virus Infection",
                        1 => "Zika Virus Disease",
                        2 => "Zika Fever",
                        3 => "ZikV Infection",
                        4 => "Zika Virus Infection",
                        5 => "Infección por el Virus Zika",
                        6 => "Enfermedad del Virus Zika",
                        7 => "Enfermedad por Virus Zika",
                        8 => "Enfermedad por ZIKV",
                        9 => "Enfermedad por Zika",
                        10 => "Enfermedad por Zika virus",
                        11 => "Enfermedad por el Virus Zika",
                        12 => "Enfermedad por el Virus de Zika",
                        13 => "Enfermedad por el Virus del Zika",
                        14 => "Enfermedad por el Zikavirus",
                        15 => "Fiebre Zika",
                        16 => "Fiebre por Virus Zika",
                        17 => "Fiebre por el Virus Zika",
                        18 => "Infección del Virus Zika",
                        19 => "Infección por Virus Zika",
                        20 => "Infección por Vzika",
                        21 => "Infección por ZIKV",
                        22 => "Infección por Zika virus",
                        23 => "Infección por Zikavirus",
                        24 => "Infección por el Virus de Zika",
                        25 => "Infección por el Virus del Zika",
                        26 => "Infecção por Zika virus",
                        27 => "Doença pelo Vírus Zika",
                        28 => "Doença pelo Zika virus",
                        29 => "Doença pelo Zikavirus",
                        30 => "Doença por Vírus Zika",
                        31 => "Doença por Zika virus",
                        32 => "Febre Zika",
                        33 => "Febre pelo Vírus Zika",
                        34 => "Febre por Vírus Zika",
                        35 => "Febre por Zika",
                        36 => "Febre por Zika virus",
                        37 => "Infeccção por ZIKV",
                        38 => "Infecção pelo Zika virus",
                        39 => "Infecção pelo Zikavirus",
                        40 => "Infecção por Vírus Zika",
                        41 => "Infecção por Zika",
                        42 => "Infecção por Zikavirus",
                    ]
                ],
            ],
            "OldSelectedDescriptors" => "",
            'ImproveSearchQuery' => '',
            'mainLanguage' => 'en',
        ]);
    }

    public static final function AttributeRules()
    {
        return [
            'newQuery' => ['required|string|min:0'],
            'decodedOldDescriptors' => ['required|array|min:1'],
            'OldSelectedDescriptors' => ['required|array|min:1'],
        ];
    }

}
