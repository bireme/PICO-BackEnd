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
                        9 => "Severe Dengue",
                        10 => "Dengue Hemorrhagic Fever",
                        11 => "Dengue Shock Syndrome",
                        12 => "Severe Dengues",
                        13 => "Philippine Hemorrhagic Fever",
                        14 => "Singapore Hemorrhagic Fever",
                        15 => "Thai Hemorrhagic Fever",
                        16 => "Hemorrhagic Dengue",
                        17 => "Hemorrhagic Dengues",
                    ],
                    "Dengue Virus" => [
                        0 => "Dengue Virus",
                        1 => "Breakbone Fever Virus",
                        2 => "Breakbone Fever Viruses",
                        3 => "Dengue Viruses",
                    ],
                    "Severe Dengue" => [
                        0 => "Severe Dengue",
                        1 => "Dengue Hemorrhagic Fever",
                        2 => "Dengue Shock Syndrome",
                        3 => "Severe Dengues",
                        4 => "Philippine Hemorrhagic Fever",
                        5 => "Singapore Hemorrhagic Fever",
                        6 => "Thai Hemorrhagic Fever",
                        7 => "Hemorrhagic Dengue",
                        8 => "Hemorrhagic Dengues",
                    ],
                    "Dengue Vaccines" => [
                        0 => "Dengue Vaccines",
                    ],
                ],
                "zika" => [
                    "Zika Virus" => [
                        0 => "Zika Virus",
                        1 => "ZikV",
                    ],
                    "Zika Virus Infection" => [
                        0 => "Zika Virus Infection",
                        1 => "Zika Virus Disease",
                        2 => "Zika Fever",
                        3 => "ZikV Infection",
                    ],
                ],
            ],
            "ImproveSearchQuery" => "localhost",
            "QuerySplit" => '[{"type":"op","value":"("},{"type":"keyexplored","value":"dengue"},{"type":"op","value":" "},{"type":"sep","value":"or"},{"type":"op","value":" "},{"type":"term","value":"dengue virus"},{"type":"op","value":"("},{"type":"decs","value":"breakbone fever virus"},{"type":"decs","value":"breakbone fever viruses"},{"type":"decs","value":"dengue viruses"},{"type":"op","value":")"},{"type":"op","value":" "},{"type":"sep","value":"or"},{"type":"op","value":" "},{"type":"term","value":"severe dengue"},{"type":"op","value":"("},{"type":"decs","value":"dengue hemorrhagic fever"},{"type":"decs","value":"dengue shock syndrome"},{"type":"decs","value":"severe dengues"},{"type":"decs","value":"philippine hemorrhagic fever"},{"type":"decs","value":"singapore hemorrhagic fever"},{"type":"decs","value":"thai hemorrhagic fever"},{"type":"decs","value":"hemorrhagic dengue"},{"type":"decs","value":"hemorrhagic dengues"},{"type":"op","value":")"},{"type":"op","value":" "},{"type":"sep","value":"or"},{"type":"op","value":" "},{"type":"term","value":"dengue vaccines"},{"type":"op","value":")"},{"type":"op","value":" "},{"type":"sep","value":"and"},{"type":"op","value":" "},{"type":"op","value":"("},{"type":"keyexplored","value":"zika"},{"type":"op","value":" "},{"type":"sep","value":"or"},{"type":"op","value":" "},{"type":"term","value":"zika virus"},{"type":"decs","value":"zikv"},{"type":"op","value":" "},{"type":"sep","value":"or"},{"type":"op","value":" "},{"type":"term","value":"zika virus infection"},{"type":"op","value":"("},{"type":"decs","value":"zika virus disease"},{"type":"decs","value":"zika fever"},{"type":"decs","value":"zikv infection"},{"type":"op","value":")"},{"type":"op","value":")"},{"type":"op","value":" "},{"type":"sep","value":"or"},{"type":"op","value":" "},{"type":"improve","value":"localhost"}]',
            'mainLanguage' => 'en',
        ]);
    }

    public static final function AttributeRules()
    {
        return [
            'newQuery' => ['required|string|min:0'],
            'QuerySplit' => ['required|array|min:0'],
        ];
    }

}
