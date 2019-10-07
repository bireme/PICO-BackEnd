<?php

namespace PICOExplorer\Models\DataModels;

use PICOExplorer\Models\MainModelsModel;

class QueryBuildModel extends MainModelsModel
{

    public static final function requestRules()
    {
        return [
            'InitialData' => 'required|array|min:1',
            'InitialData.QuerySplit' => 'required|string',
            'InitialData.SelectedDescriptors' => 'required|array|min:1',
            'InitialData.ImproveSearchQuery' => 'required|array|min:1',
        ];
    }

    public static final function responseRules()
    {
        return [
            'results' => 'required|array|min:1',
            'results.newQuery' => 'required|string|min:1',
            'results.ImproveSearchWords' => 'required|string|min:0',
            'results.OldSelectedDescriptors' => 'required|string|min:0',
            'ImproveSearchQuery' => 'required|string|min:0',
        ];
    }

    public final function ControllerTestData()
    {
        return $this->TestZeroData();
    }

    public static final function AttributeRules()
    {
        return [
            'newQuery' => ['required|string|min:0'],
            'QuerySplit' => ['required|array|min:0'],
            'ImproveSearchWords' => ['required|array|min:0'],
            'ImproveSearchQuery' => ['required|string|min:0'],
        ];
    }

    private function TestZeroData(){
        return json_encode([
          "QuerySplit" => '[{"type":"keyword","value":"dengue"},{"type":"op","value":" "},{"type":"sep","value":"and"},{"type":"op","value":" "},{"type":"keyword","value":"zika"}]',
          "SelectedDescriptors" => [
            "Dengue" => [
              "Dengue" => [],
              "Dengue Virus" => [],
              "Severe Dengue" => [],
              "Dengue Vaccines" => [],
            ],
            "Zika" => [
              "Zika Virus" => [],
              "Zika Virus Infection" => [],
            ],
          ],
          "ImproveSearchQuery" => "alfabeta",
          "mainLanguage" => "en",
        ]);
    }

    private function PlusZeroData()
    {
        return json_encode([
                "QuerySplit" => '[{"type":"keyword","value":"dengue"},{"type":"op","value":" "},{"type":"sep","value":"and"},{"type":"op","value":" "},{"type":"keyword","value":"zika"}]',
                "SelectedDescriptors" => [
                    "Dengue" => [
                        "Dengue" => [
                            0 => "Break Bone Fever",
                            1 => "Dengue",
                            2 => "Febre Quebra-Ossos",
                        ],
                        "Dengue Virus" => [
                            0 => "Virus de la Fiebre Rompehuesos",
                        ],
                        "Severe Dengue" => [
                            0 => "Severe Dengue",
                            1 => "Severe Dengue",
                            2 => "Dengue Hemorrhagic Fever",
                            3 => "Dengue Shock Syndrome",
                            4 => "Severe Dengues",
                            5 => "Philippine Hemorrhagic Fever",
                            6 => "Singapore Hemorrhagic Fever",
                            7 => "Thai Hemorrhagic Fever",
                            8 => "Hemorrhagic Dengue",
                            9 => "Hemorrhagic Dengues",
                            10 => "Dengue Grave",
                            11 => "Dengue Grave",
                            12 => "Dengue Hemorrágico",
                            13 => "Fiebre Dengue Hemorrágica",
                            14 => "Fiebre Hemorrágica Dengue",
                            15 => "Fiebre Hemorrágica de Dengue",
                            16 => "Síndrome de Choque por Dengue",
                            17 => "Síndrome de Shock por Dengue",
                            18 => "Dengue Grave",
                            19 => "Dengue Grave",
                            20 => "Dengue Hemorrágica",
                            21 => "Febre Hemorrágica Dengue",
                            22 => "Febre Hemorrágica da Dengue",
                            23 => "Febre Hemorrágica devida ao Vírus do Dengue",
                            24 => "Febre Hemorrágica pelo Vírus da Dengue",
                            25 => "Febre Hemorrágica pelo Vírus do Dengue",
                            26 => "Síndrome de Choque da Dengue",
                        ],
                        "Dengue Vaccines" => [
                            0 => "Vacinas contra a Dengue",
                        ],
                    ],
                    "Zika" => [
                        "Zika Virus" => [
                            0 => "Zika Virus",
                            1 => "Zika Virus",
                            2 => "ZikV",
                            3 => "Virus Zika",
                            4 => "Virus Zika",
                            5 => "Virus de Zika",
                            6 => "Virus de la Fiebre Zika",
                            7 => "Virus del Zika",
                            8 => "ZIKV",
                            9 => "ZikV",
                            10 => "Zika virus",
                            11 => "Zikavirus",
                            12 => "Zika virus",
                            13 => "Zika virus",
                            14 => "Vírus Zika",
                            15 => "Vírus da Febre Zika",
                            16 => "Vírus da Zika",
                            17 => "Vírus de Zika",
                            18 => "ZIKV",
                            19 => "ZikV",
                            20 => "Zikavirus",
                        ],
                        "Zika Virus Infection" => [
                            0 => "Zika Virus Infection",
                            1 => "Zika Virus Infection",
                            2 => "Zika Virus Disease",
                            3 => "Zika Fever",
                            4 => "ZikV Infection",
                            5 => "Infección por el Virus Zika",
                            6 => "Infección por el Virus Zika",
                            7 => "Enfermedad del Virus Zika",
                            8 => "Enfermedad por Virus Zika",
                            9 => "Enfermedad por ZIKV",
                            10 => "Enfermedad por Zika",
                            11 => "Enfermedad por Zika virus",
                            12 => "Enfermedad por el Virus Zika",
                            13 => "Enfermedad por el Virus de Zika",
                            14 => "Enfermedad por el Virus del Zika",
                            15 => "Enfermedad por el Zikavirus",
                            16 => "Fiebre Zika",
                            17 => "Fiebre por Virus Zika",
                            18 => "Fiebre por el Virus Zika",
                            19 => "Infección del Virus Zika",
                            20 => "Infección por Virus Zika",
                            21 => "Infección por Vzika",
                            22 => "Infección por ZIKV",
                            23 => "Infección por Zika virus",
                            24 => "Infección por Zikavirus",
                            25 => "Infección por el Virus de Zika",
                            26 => "Infección por el Virus del Zika",
                            27 => "Infecção por Zika virus",
                            28 => "Infecção por Zika virus",
                            29 => "Doença pelo Vírus Zika",
                            30 => "Doença pelo Zika virus",
                            31 => "Doença pelo Zikavirus",
                            32 => "Doença por Vírus Zika",
                            33 => "Doença por Zika virus",
                            34 => "Febre Zika",
                            35 => "Febre pelo Vírus Zika",
                            36 => "Febre por Vírus Zika",
                            37 => "Febre por Zika",
                            38 => "Febre por Zika virus",
                            39 => "Infeccção por ZIKV",
                            40 => "Infecção pelo Zika virus",
                            41 => "Infecção pelo Zikavirus",
                            42 => "Infecção por Vírus Zika",
                            43 => "Infecção por Zika",
                            44 => "Infecção por Zikavirus",
                        ],
                    ],
                ],
                "ImproveSearchQuery" => "alfabeta",
                "mainLanguage" => "en",
            ]

        );
    }


}
