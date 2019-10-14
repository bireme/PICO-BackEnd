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
        return $this->FullBrachialSearch();
    }

    public static final function AttributeRules()
    {
        return [
            'newQuery' => ['required|string|min:0'],
            'QuerySplit' => ['required|array|min:0'],
            'ImproveSearchWords' => ['required|array|min:0'],
            'ImproveSearchQuery' => ['required|string|min:0'],
            'ImproveSearchSplit' => ['required|array|min:0'],
        ];
    }

    private function TestZeroData()
    {
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

    private function IssuePost57()
    {
        return json_encode([
            'ImproveSearchQuery' => "",
            'QuerySplit' => '[{"type":"op","value":"("},{"type":"op","value":"("},{"type":"op","value":"("},{"type":"op","value":"("},{"type":"op","value":"("},{"type":"op","value":"("},{"type":"keyexplored","value":"dengue"},{"type":"sep","value":" "},{"type":"op","value":"OR"},{"type":"sep","value":" "},{"type":"op","value":"("},{"type":"term","value":"dengue vaccines"},{"type":"op","value":")"},{"type":"op","value":")"},{"type":"op","value":")"},{"type":"op","value":")"},{"type":"op","value":")"},{"type":"op","value":")"},{"type":"op","value":")"}]',
            'SelectedDescriptors' => [
                'Dengue' => [
                    'Dengue' => [],
                    'Dengue Vaccines' => [],
                    'Dengue Virus' => [],
                    'Severe Dengue' => [],
                ],
            ],
            'mainLanguage' => "en",
        ]);

    }

    private function Issue57()
    {
        return json_encode([
            'ImproveSearchQuery' => "",
            'QuerySplit' => '[{"type":"op","value":"("},{"type":"op","value":"("},{"type":"op","value":"("},{"type":"op","value":"("},{"type":"op","value":"("},{"type":"keyexplored","value":"dengue"},{"type":"op","value":")"},{"type":"op","value":")"},{"type":"op","value":")"},{"type":"op","value":")"},{"type":"op","value":")"}]',
            'SelectedDescriptors' => [
                'Dengue' => [
                    'Dengue' => [],
                    'Dengue Vaccines' => [
                        0 => "Vacunas Contra El Dengue",
                        1 => "Vacinas Contra Dengue",
                        2 => "Vacinas Contra A Dengue",
                        3 => "Vacinas Contra O Vírus Da Dengue",
                    ],
                    'Dengue Virus' => [],
                    'Severe Dengue' => [],

                ],
            ],
            'mainLanguage' => "en",
        ]);
    }

    private function Issue39()
    {
        return json_encode([
            'ImproveSearchQuery' => "",
            'QuerySplit' => '[{"type":"op","value":"("},{"type":"op","value":"("},{"type":"keyexplored","value":"dengue"},{"type":"sep","value":" "},{"type":"op","value":"OR"},{"type":"sep","value":" "},{"type":"op","value":"("},{"type":"term","value":"dengue vaccines"},{"type":"sep","value":" "},{"type":"op","value":"OR"},{"type":"sep","value":" "},{"type":"term","value":"vacunas contra el dengue"},{"type":"sep","value":" "},{"type":"op","value":"OR"},{"type":"sep","value":" "},{"type":"term","value":"vacinas contra dengue"},{"type":"sep","value":" "},{"type":"op","value":"OR"},{"type":"sep","value":" "},{"type":"decs","value":"vacinas contra a dengue"},{"type":"sep","value":" "},{"type":"op","value":"OR"},{"type":"sep","value":" "},{"type":"decs","value":"vacinas contra o v\u00edrus da dengue"},{"type":"op","value":")"},{"type":"op","value":")"},{"type":"op","value":")"}]',
            'SelectedDescriptors' => [
                'Dengue' => [
                    'Dengue' => [],
                    'Dengue Vaccines' => [],
                    'Dengue Virus' => [],
                    'Severe Dengue' => [],

                ],
            ],
            'mainLanguage' => "en",
        ]);
    }

    private function PlusZeroData()
    {
        return json_encode([
                "QuerySplit" => '[{
        "type":"op","value":"("},{
        "type":"op","value":"("},{
        "type":"keyexplored","value":"dengue"},{
        "type":"sep","value":" "},{
        "type":"op","value":"OR"},{
        "type":"sep","value":" "},{
        "type":"op","value":"("},{
        "type":"decs","value":"break bone fever"},{
        "type":"sep","value":" "},{
        "type":"op","value":"OR"},{
        "type":"sep","value":" "},{
        "type":"decs","value":"break-bone fever"},{
        "type":"sep","value":" "},{
        "type":"op","value":"OR"},{
        "type":"sep","value":" "},{
        "type":"decs","value":"breakbone fever"},{
        "type":"sep","value":" "},{
        "type":"op","value":"OR"},{
        "type":"sep","value":" "},{
        "type":"decs","value":"classical dengue"},{
        "type":"sep","value":" "},{
        "type":"op","value":"OR"},{
        "type":"sep","value":" "},{
        "type":"decs","value":"classical dengue fever"},{
        "type":"sep","value":" "},{
        "type":"op","value":"OR"},{
        "type":"sep","value":" "},{
        "type":"decs","value":"classical dengue fevers"},{
        "type":"sep","value":" "},{
        "type":"op","value":"OR"},{
        "type":"sep","value":" "},{
        "type":"decs","value":"classical dengues"},{
        "type":"sep","value":" "},{
        "type":"op","value":"OR"},{
        "type":"sep","value":" "},{
        "type":"decs","value":"dengue fever"},{
        "type":"sep","value":" "},{
        "type":"op","value":"OR"},{
        "type":"sep","value":" "},{
        "type":"decs","value":"fiebre dengue"},{
        "type":"sep","value":" "},{
        "type":"op","value":"OR"},{
        "type":"sep","value":" "},{
        "type":"decs","value":"febre quebra-ossos"},{
        "type":"sep","value":" "},{
        "type":"op","value":"OR"},{
        "type":"sep","value":" "},{
        "type":"decs","value":"febre da dengue"},{
        "type":"sep","value":" "},{
        "type":"op","value":"OR"},{
        "type":"sep","value":" "},{
        "type":"decs","value":"infec\u00e7\u00e3o pelo v\u00edrus da dengue"},{
        "type":"sep","value":" "},{
        "type":"op","value":"OR"},{
        "type":"sep","value":" "},{
        "type":"decs","value":"infec\u00e7\u00e3o por v\u00edrus da dengue"},{
        "type":"sep","value":" "},{
        "type":"op","value":"OR"},{
        "type":"sep","value":" "},{
        "type":"decs","value":"infec\u00e7\u00e3o por v\u00edrus de dengue"},{
        "type":"op","value":")"},{
        "type":"sep","value":" "},{
        "type":"op","value":"OR"},{
        "type":"sep","value":" "},{
        "type":"op","value":"("},{
        "type":"term","value":"dengue virus"},{
        "type":"sep","value":" "},{
        "type":"op","value":"OR"},{
        "type":"sep","value":" "},{
        "type":"decs","value":"breakbone fever virus"},{
        "type":"sep","value":" "},{
        "type":"op","value":"OR"},{
        "type":"sep","value":" "},{
        "type":"decs","value":"breakbone fever viruses"},{
        "type":"sep","value":" "},{
        "type":"op","value":"OR"},{
        "type":"sep","value":" "},{
        "type":"decs","value":"dengue viruses"},{
        "type":"sep","value":" "},{
        "type":"op","value":"OR"},{
        "type":"sep","value":" "},{
        "type":"term","value":"virus del dengue"},{
        "type":"sep","value":" "},{
        "type":"op","value":"OR"},{
        "type":"sep","value":" "},{
        "type":"decs","value":"virus de la fiebre rompehuesos"},{
        "type":"sep","value":" "},{
        "type":"op","value":"OR"},{
        "type":"sep","value":" "},{
        "type":"term","value":"v\u00edrus da dengue"},{
        "type":"sep","value":" "},{
        "type":"op","value":"OR"},{
        "type":"sep","value":" "},{
        "type":"decs","value":"v\u00edrus da febre quebra-ossos"},{
        "type":"op","value":")"},{
        "type":"sep","value":" "},{
        "type":"op","value":"OR"},{
        "type":"sep","value":" "},{
        "type":"op","value":"("},{
        "type":"term","value":"severe dengue"},{
        "type":"sep","value":" "},{
        "type":"op","value":"OR"},{
        "type":"sep","value":" "},{
        "type":"decs","value":"dengue hemorrhagic fever"},{
        "type":"sep","value":" "},{
        "type":"op","value":"OR"},{
        "type":"sep","value":" "},{
        "type":"decs","value":"dengue shock syndrome"},{
        "type":"sep","value":" "},{
        "type":"op","value":"OR"},{
        "type":"sep","value":" "},{
        "type":"decs","value":"severe dengues"},{
        "type":"sep","value":" "},{
        "type":"op","value":"OR"},{
        "type":"sep","value":" "},{
        "type":"decs","value":"philippine hemorrhagic fever"},{
        "type":"sep","value":" "},{
        "type":"op","value":"OR"},{
        "type":"sep","value":" "},{
        "type":"decs","value":"singapore hemorrhagic fever"},{
        "type":"sep","value":" "},{
        "type":"op","value":"OR"},{
        "type":"sep","value":" "},{
        "type":"decs","value":"thai hemorrhagic fever"},{
        "type":"sep","value":" "},{
        "type":"op","value":"OR"},{
        "type":"sep","value":" "},{
        "type":"decs","value":"hemorrhagic dengue"},{
        "type":"sep","value":" "},{
        "type":"op","value":"OR"},{
        "type":"sep","value":" "},{
        "type":"decs","value":"hemorrhagic dengues"},{
        "type":"sep","value":" "},{
        "type":"op","value":"OR"},{
        "type":"sep","value":" "},{
        "type":"term","value":"dengue grave"},{
        "type":"sep","value":" "},{
        "type":"op","value":"OR"},{
        "type":"sep","value":" "},{
        "type":"decs","value":"dengue hemorr\u00e1gico"},{
        "type":"sep","value":" "},{
        "type":"op","value":"OR"},{
        "type":"sep","value":" "},{
        "type":"decs","value":"fiebre dengue hemorr\u00e1gica"},{
        "type":"sep","value":" "},{
        "type":"op","value":"OR"},{
        "type":"sep","value":" "},{
        "type":"decs","value":"fiebre hemorr\u00e1gica dengue"},{
        "type":"sep","value":" "},{
        "type":"op","value":"OR"},{
        "type":"sep","value":" "},{
        "type":"decs","value":"fiebre hemorr\u00e1gica de dengue"},{
        "type":"sep","value":" "},{
        "type":"op","value":"OR"},{
        "type":"sep","value":" "},{
        "type":"decs","value":"s\u00edndrome de choque por dengue"},{
        "type":"sep","value":" "},{
        "type":"op","value":"OR"},{
        "type":"sep","value":" "},{
        "type":"decs","value":"s\u00edndrome de shock por dengue"},{
        "type":"sep","value":" "},{
        "type":"op","value":"OR"},{
        "type":"sep","value":" "},{
        "type":"decs","value":"dengue hemorr\u00e1gica"},{
        "type":"sep","value":" "},{
        "type":"op","value":"OR"},{
        "type":"sep","value":" "},{
        "type":"decs","value":"febre hemorr\u00e1gica dengue"},{
        "type":"sep","value":" "},{
        "type":"op","value":"OR"},{
        "type":"sep","value":" "},{
        "type":"decs","value":"febre hemorr\u00e1gica da dengue"},{
        "type":"sep","value":" "},{
        "type":"op","value":"OR"},{
        "type":"sep","value":" "},{
        "type":"decs","value":"febre hemorr\u00e1gica devida ao v\u00edrus do dengue"},{
        "type":"sep","value":" "},{
        "type":"op","value":"OR"},{
        "type":"sep","value":" "},{
        "type":"decs","value":"febre hemorr\u00e1gica pelo v\u00edrus da dengue"},{
        "type":"sep","value":" "},{
        "type":"op","value":"OR"},{
        "type":"sep","value":" "},{
        "type":"decs","value":"febre hemorr\u00e1gica pelo v\u00edrus do dengue"},{
        "type":"sep","value":" "},{
        "type":"op","value":"OR"},{
        "type":"sep","value":" "},{
        "type":"decs","value":"s\u00edndrome de choque da dengue"},{
        "type":"op","value":")"},{
        "type":"sep","value":" "},{
        "type":"op","value":"OR"},{
        "type":"sep","value":" "},{
        "type":"op","value":"("},{
        "type":"term","value":"dengue vaccines"},{
        "type":"sep","value":" "},{
        "type":"op","value":"OR"},{
        "type":"sep","value":" "},{
        "type":"term","value":"vacunas contra el dengue"},{
        "type":"sep","value":" "},{
        "type":"op","value":"OR"},{
        "type":"sep","value":" "},{
        "type":"term","value":"vacinas contra dengue"},{
        "type":"sep","value":" "},{
        "type":"op","value":"OR"},{
        "type":"sep","value":" "},{
        "type":"decs","value":"vacinas contra a dengue"},{
        "type":"sep","value":" "},{
        "type":"op","value":"OR"},{
        "type":"sep","value":" "},{
        "type":"decs","value":"vacinas contra o v\u00edrus da dengue"},{
        "type":"op","value":")"},{
        "type":"op","value":")"},{
        "type":"sep","value":" "},{
        "type":"op","value":"AND"},{
        "type":"sep","value":" "},{
        "type":"op","value":"("},{
        "type":"keyexplored","value":"zika"},{
        "type":"sep","value":" "},{
        "type":"op","value":"OR"},{
        "type":"sep","value":" "},{
        "type":"op","value":"("},{
        "type":"term","value":"zika virus"},{
        "type":"sep","value":" "},{
        "type":"op","value":"OR"},{
        "type":"sep","value":" "},{
        "type":"decs","value":"zikv"},{
        "type":"sep","value":" "},{
        "type":"op","value":"OR"},{
        "type":"sep","value":" "},{
        "type":"term","value":"virus zika"},{
        "type":"sep","value":" "},{
        "type":"op","value":"OR"},{
        "type":"sep","value":" "},{
        "type":"decs","value":"virus de zika"},{
        "type":"sep","value":" "},{
        "type":"op","value":"OR"},{
        "type":"sep","value":" "},{
        "type":"decs","value":"virus de la fiebre zika"},{
        "type":"sep","value":" "},{
        "type":"op","value":"OR"},{
        "type":"sep","value":" "},{
        "type":"decs","value":"virus del zika"},{
        "type":"sep","value":" "},{
        "type":"op","value":"OR"},{
        "type":"sep","value":" "},{
        "type":"decs","value":"zikavirus"},{
        "type":"sep","value":" "},{
        "type":"op","value":"OR"},{
        "type":"sep","value":" "},{
        "type":"decs","value":"v\u00edrus zika"},{
        "type":"sep","value":" "},{
        "type":"op","value":"OR"},{
        "type":"sep","value":" "},{
        "type":"decs","value":"v\u00edrus da febre zika"},{
        "type":"sep","value":" "},{
        "type":"op","value":"OR"},{
        "type":"sep","value":" "},{
        "type":"decs","value":"v\u00edrus da zika"},{
        "type":"sep","value":" "},{
        "type":"op","value":"OR"},{
        "type":"sep","value":" "},{
        "type":"decs","value":"v\u00edrus de zika"},{
        "type":"op","value":")"},{
        "type":"sep","value":" "},{
        "type":"op","value":"OR"},{
        "type":"sep","value":" "},{
        "type":"op","value":"("},{
        "type":"term","value":"zika virus infection"},{
        "type":"sep","value":" "},{
        "type":"op","value":"OR"},{
        "type":"sep","value":" "},{
        "type":"decs","value":"zika virus disease"},{
        "type":"sep","value":" "},{
        "type":"op","value":"OR"},{
        "type":"sep","value":" "},{
        "type":"decs","value":"zika fever"},{
        "type":"sep","value":" "},{
        "type":"op","value":"OR"},{
        "type":"sep","value":" "},{
        "type":"decs","value":"zikv infection"},{
        "type":"sep","value":" "},{
        "type":"op","value":"OR"},{
        "type":"sep","value":" "},{
        "type":"term","value":"infecci\u00f3n por el virus zika"},{
        "type":"sep","value":" "},{
        "type":"op","value":"OR"},{
        "type":"sep","value":" "},{
        "type":"decs","value":"enfermedad del virus zika"},{
        "type":"sep","value":" "},{
        "type":"op","value":"OR"},{
        "type":"sep","value":" "},{
        "type":"decs","value":"enfermedad por virus zika"},{
        "type":"sep","value":" "},{
        "type":"op","value":"OR"},{
        "type":"sep","value":" "},{
        "type":"decs","value":"enfermedad por zikv"},{
        "type":"sep","value":" "},{
        "type":"op","value":"OR"},{
        "type":"sep","value":" "},{
        "type":"decs","value":"enfermedad por zika"},{
        "type":"sep","value":" "},{
        "type":"op","value":"OR"},{
        "type":"sep","value":" "},{
        "type":"decs","value":"enfermedad por zika virus"},{
        "type":"sep","value":" "},{
        "type":"op","value":"OR"},{
        "type":"sep","value":" "},{
        "type":"decs","value":"enfermedad por el virus zika"},{
        "type":"sep","value":" "},{
        "type":"op","value":"OR"},{
        "type":"sep","value":" "},{
        "type":"decs","value":"enfermedad por el virus de zika"},{
        "type":"sep","value":" "},{
        "type":"op","value":"OR"},{
        "type":"sep","value":" "},{
        "type":"decs","value":"enfermedad por el virus del zika"},{
        "type":"sep","value":" "},{
        "type":"op","value":"OR"},{
        "type":"sep","value":" "},{
        "type":"decs","value":"enfermedad por el zikavirus"},{
        "type":"sep","value":" "},{
        "type":"op","value":"OR"},{
        "type":"sep","value":" "},{
        "type":"decs","value":"fiebre zika"},{
        "type":"sep","value":" "},{
        "type":"op","value":"OR"},{
        "type":"sep","value":" "},{
        "type":"decs","value":"fiebre por virus zika"},{
        "type":"sep","value":" "},{
        "type":"op","value":"OR"},{
        "type":"sep","value":" "},{
        "type":"decs","value":"fiebre por el virus zika"},{
        "type":"sep","value":" "},{
        "type":"op","value":"OR"},{
        "type":"sep","value":" "},{
        "type":"decs","value":"infecci\u00f3n del virus zika"},{
        "type":"sep","value":" "},{
        "type":"op","value":"OR"},{
        "type":"sep","value":" "},{
        "type":"decs","value":"infecci\u00f3n por virus zika"},{
        "type":"sep","value":" "},{
        "type":"op","value":"OR"},{
        "type":"sep","value":" "},{
        "type":"decs","value":"infecci\u00f3n por vzika"},{
        "type":"sep","value":" "},{
        "type":"op","value":"OR"},{
        "type":"sep","value":" "},{
        "type":"decs","value":"infecci\u00f3n por zikv"},{
        "type":"sep","value":" "},{
        "type":"op","value":"OR"},{
        "type":"sep","value":" "},{
        "type":"decs","value":"infecci\u00f3n por zika virus"},{
        "type":"sep","value":" "},{
        "type":"op","value":"OR"},{
        "type":"sep","value":" "},{
        "type":"decs","value":"infecci\u00f3n por zikavirus"},{
        "type":"sep","value":" "},{
        "type":"op","value":"OR"},{
        "type":"sep","value":" "},{
        "type":"decs","value":"infecci\u00f3n por el virus de zika"},{
        "type":"sep","value":" "},{
        "type":"op","value":"OR"},{
        "type":"sep","value":" "},{
        "type":"decs","value":"infecci\u00f3n por el virus del zika"},{
        "type":"sep","value":" "},{
        "type":"op","value":"OR"},{
        "type":"sep","value":" "},{
        "type":"term","value":"infec\u00e7\u00e3o por zika virus"},{
        "type":"sep","value":" "},{
        "type":"op","value":"OR"},{
        "type":"sep","value":" "},{
        "type":"decs","value":"doen\u00e7a pelo v\u00edrus zika"},{
        "type":"sep","value":" "},{
        "type":"op","value":"OR"},{
        "type":"sep","value":" "},{
        "type":"decs","value":"doen\u00e7a pelo zika virus"},{
        "type":"sep","value":" "},{
        "type":"op","value":"OR"},{
        "type":"sep","value":" "},{
        "type":"decs","value":"doen\u00e7a pelo zikavirus"},{
        "type":"sep","value":" "},{
        "type":"op","value":"OR"},{
        "type":"sep","value":" "},{
        "type":"decs","value":"doen\u00e7a por v\u00edrus zika"},{
        "type":"sep","value":" "},{
        "type":"op","value":"OR"},{
        "type":"sep","value":" "},{
        "type":"decs","value":"doen\u00e7a por zika virus"},{
        "type":"sep","value":" "},{
        "type":"op","value":"OR"},{
        "type":"sep","value":" "},{
        "type":"decs","value":"febre zika"},{
        "type":"sep","value":" "},{
        "type":"op","value":"OR"},{
        "type":"sep","value":" "},{
        "type":"decs","value":"febre pelo v\u00edrus zika"},{
        "type":"sep","value":" "},{
        "type":"op","value":"OR"},{
        "type":"sep","value":" "},{
        "type":"decs","value":"febre por v\u00edrus zika"},{
        "type":"sep","value":" "},{
        "type":"op","value":"OR"},{
        "type":"sep","value":" "},{
        "type":"decs","value":"febre por zika"},{
        "type":"sep","value":" "},{
        "type":"op","value":"OR"},{
        "type":"sep","value":" "},{
        "type":"decs","value":"febre por zika virus"},{
        "type":"sep","value":" "},{
        "type":"op","value":"OR"},{
        "type":"sep","value":" "},{
        "type":"decs","value":"infecc\u00e7\u00e3o por zikv"},{
        "type":"sep","value":" "},{
        "type":"op","value":"OR"},{
        "type":"sep","value":" "},{
        "type":"decs","value":"infec\u00e7\u00e3o pelo zika virus"},{
        "type":"sep","value":" "},{
        "type":"op","value":"OR"},{
        "type":"sep","value":" "},{
        "type":"decs","value":"infec\u00e7\u00e3o pelo zikavirus"},{
        "type":"sep","value":" "},{
        "type":"op","value":"OR"},{
        "type":"sep","value":" "},{
        "type":"decs","value":"infec\u00e7\u00e3o por v\u00edrus zika"},{
        "type":"sep","value":" "},{
        "type":"op","value":"OR"},{
        "type":"sep","value":" "},{
        "type":"decs","value":"infec\u00e7\u00e3o por zika"},{
        "type":"sep","value":" "},{
        "type":"op","value":"OR"},{
        "type":"sep","value":" "},{
        "type":"decs","value":"infec\u00e7\u00e3o por zikavirus"},{
        "type":"op","value":")"},{
        "type":"op","value":")"},{
        "type":"op","value":")"}]',
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
                "ImproveSearchQuery" => "alfabeta or testeo or error",
                "mainLanguage" => "en",
            ]

        );
    }

    private function IssueRepeatedQuery()
    {
        return json_encode([
            'ImproveSearchQuery' => "",
            'QuerySplit' => '[{"type":"op","value":"("},{"type":"op","value":"("},{"type":"op","value":"("},{"type":"keyexplored","value":"dengue"},{"type":"sep","value":" "},{"type":"op","value":"OR"},{"type":"sep","value":" "},{"type":"op","value":"("},{"type":"term","value":"dengue vaccines"},{"type":"sep","value":" "},{"type":"op","value":"OR"},{"type":"sep","value":" "},{"type":"decs","value":"vacinas contra a dengue"},{"type":"sep","value":" "},{"type":"op","value":"OR"},{"type":"sep","value":" "},{"type":"decs","value":"vacinas contra o v\u00edrus da dengue"},{"type":"op","value":")"},{"type":"op","value":")"},{"type":"op","value":")"},{"type":"op","value":")"}]',
            'SelectedDescriptors' => [
                'Dengue' => [
                    'Dengue' => [], 'Dengue Virus' => [], 'Severe Dengue' => [], 'Dengue Vaccines' => ["Vacinas Contra A Dengue", "Vacinas Contra O Vírus Da Dengue"],
                ],
            ],
            'mainLanguage' => "en",
        ]);
    }


    private function RemoveTerm()
    {
        return json_encode([
            'ImproveSearchQuery' => "",
            'QuerySplit' => '[{"type":"keyexplored","value":"dengue"},{"type":"sep","value":" "},{"type":"op","value":"OR"},{"type":"sep","value":" "},{"type":"op","value":"("},{"type":"term","value":"vacunas contra el dengue"},{"type":"op","value":")"},{"type":"sep","value":" "},{"type":"op","value":"OR"},{"type":"sep","value":" "},{"type":"op","value":"("},{"type":"term","value":"dengue vaccines"},{"type":"op","value":")"},{"type":"sep","value":" "},{"type":"op","value":"OR"},{"type":"sep","value":" "},{"type":"op","value":"("},{"type":"free","value":"dengue vacines"},{"type":"op","value":")"},{"type":"sep","value":" "},{"type":"op","value":"OR"},{"type":"sep","value":" "},{"type":"keyexplored","value":"zika"},{"type":"sep","value":" "},{"type":"op","value":"OR"},{"type":"sep","value":" "},{"type":"op","value":"("},{"type":"term","value":"zika virus infection"},{"type":"sep","value":" "},{"type":"op","value":"OR"},{"type":"sep","value":" "},{"type":"decs","value":"zika fever"},{"type":"sep","value":" "},{"type":"op","value":"OR"},{"type":"sep","value":" "},{"type":"decs","value":"enfermedad por virus zika"},{"type":"sep","value":" "},{"type":"op","value":"OR"},{"type":"sep","value":" "},{"type":"decs","value":"enfermedad por zika"},{"type":"sep","value":" "},{"type":"op","value":"OR"},{"type":"sep","value":" "},{"type":"decs","value":"enfermedad por zika"},{"type":"op","value":")"}]',
            'SelectedDescriptors' => [
                'Dengue' =>
                    [
                        'Dengue' => [],
                        'Dengue Vaccines' => ["Vacunas Contra El Dengue"],
                        'Dengue Virus' => [],
                        'Severe Dengue' => [],
                    ],
                'Not Found' => [],
                'Zika' =>
                    [
                        'Zika Virus' => [],
                        'Zika Virus Infection' => [],
                    ],
            ],
            'mainLanguage' => "en",
        ]);
    }


    private function DissapearContentIssue()
    {
        return json_encode([
            'ImproveSearchQuery' => "",
            'QuerySplit' => '[{"type":"keyexplored","value":"dengue"},{"type":"sep","value":" "},{"type":"op","value":"OR"},{"type":"sep","value":" "},{"type":"op","value":"("},{"type":"term","value":"dengue vaccines"},{"type":"sep","value":" "},{"type":"op","value":"OR"},{"type":"sep","value":" "},{"type":"term","value":"vacinas contra dengue"},{"type":"sep","value":" "},{"type":"op","value":"OR"},{"type":"sep","value":" "},{"type":"decs","value":"vacinas contra a dengue"},{"type":"op","value":")"},{"type":"sep","value":" "},{"type":"op","value":"AND"},{"type":"sep","value":" "},{"type":"keyexplored","value":"zika"},{"type":"sep","value":" "},{"type":"op","value":"OR"},{"type":"sep","value":" "},{"type":"op","value":"("},{"type":"term","value":"zika virus infection"},{"type":"sep","value":" "},{"type":"op","value":"OR"},{"type":"sep","value":" "},{"type":"decs","value":"zika fever"},{"type":"sep","value":" "},{"type":"op","value":"OR"},{"type":"sep","value":" "},{"type":"term","value":"infecci\u00f3n por el virus zika"},{"type":"sep","value":" "},{"type":"op","value":"OR"},{"type":"sep","value":" "},{"type":"decs","value":"enfermedad por virus zika"},{"type":"sep","value":" "},{"type":"op","value":"OR"},{"type":"sep","value":" "},{"type":"decs","value":"enfermedad por zika"},{"type":"op","value":")"}]',
            'SelectedDescriptors' => [
                'Dengue' => [
                    'Dengue' => [],
                    'Dengue Vaccines' => ["Vacinas Contra Dengue", "Vacinas Contra A Dengue"],
                    'Dengue Virus' => [],
                    'Severe Dengue' => [],
                ],
                'Zika' => [
                    'Zika Virus' => [],
                    'Zika Virus Infection' => [
                        0 => "Zika Fever",
                        1 => "Infección Por El Virus Zika",
                        2 => "Enfermedad Por Virus Zika",
                        3 => "Enfermedad Por Zika",
                    ],
                ],
            ],
            'mainLanguage' => "en",

        ]);
    }

    private function DissapearTermIssue()
    {
        return json_encode([
            'ImproveSearchQuery' => "",
            'QuerySplit' => '[{"type":"op","value":"("},{"type":"keyexplored","value":"dengue"},{"type":"sep","value":" "},{"type":"op","value":"OR"},{"type":"sep","value":" "},{"type":"op","value":"("},{"type":"term","value":"dengue vaccines"},{"type":"sep","value":" "},{"type":"op","value":"OR"},{"type":"sep","value":" "},{"type":"term","value":"vacinas contra dengue"},{"type":"sep","value":" "},{"type":"op","value":"OR"},{"type":"sep","value":" "},{"type":"decs","value":"vacinas contra a dengue"},{"type":"op","value":")"},{"type":"op","value":")"},{"type":"sep","value":" "},{"type":"op","value":"AND"},{"type":"sep","value":" "},{"type":"op","value":"("},{"type":"keyexplored","value":"zika"},{"type":"sep","value":" "},{"type":"op","value":"OR"},{"type":"sep","value":" "},{"type":"op","value":"("},{"type":"term","value":"zika virus"},{"type":"sep","value":" "},{"type":"op","value":"OR"},{"type":"sep","value":" "},{"type":"decs","value":"zikv"},{"type":"sep","value":" "},{"type":"op","value":"OR"},{"type":"sep","value":" "},{"type":"decs","value":"virus del zika"},{"type":"sep","value":" "},{"type":"op","value":"OR"},{"type":"sep","value":" "},{"type":"decs","value":"v\u00edrus zika"},{"type":"op","value":")"},{"type":"op","value":")"}]',
            'SelectedDescriptors' => [
                'Dengue' => [
                    'Dengue' => [],
                    'Dengue Vaccines' => ["Vacinas Contra Dengue", "Vacinas Contra A Dengue"],
                    'Dengue Virus' => [],
                    'Severe Dengue' => [],
                ],
                'Zika' => [
                    'Zika Virus' => ["Virus Del Zika", "Zika Virus", "Vírus Zika"],
                    'Zika Virus Infection' => [],
                ],
            ],
            'mainLanguage' => "en",
        ]);

    }


    private function FullBrachialSearch()
    {
        return json_encode([
            "SelectedDescriptors" => [
            "Brachial Plexus" => [
              "Brachial Plexus" => [
                0 => "Plexo Braquial",
              ],
              "Brachial Plexus Neuritis" => [
                0 => "Amyotrophic Neuralgia",
                1 => "Amyotrophic Neuralgias",
                2 => "Hereditary Neuralgic Amyotrophies",
                3 => "Neuralgic Amyotrophies",
                4 => "Hereditary Neuralgic Amyotrophy",
                5 => "With Predilection For Brachial Plexus Hereditary Neuralgic Amyotrophy",
                6 => "Neuralgic Amyotrophy",
                7 => "Brachial Neuralgia",
                8 => "Brachial Neuralgias",
                9 => "Brachial Neuritides",
                10 => "Brachial Neuritis",
                11 => "Brachial Plexus Neuritides",
                12 => "Hereditary Brachial Plexus Neuropathy",
                13 => "Cervico Brachial Neuralgia",
                14 => "Cervico-brachial Neuralgia",
                15 => "Cervico-brachial Neuralgias",
                16 => "Cervicobrachial Neuralgia",
                17 => "Cervicobrachial Neuralgias",
                18 => "Familial Brachial Plexus Neuritis",
                19 => "Shoulder Girdle Neuropathies",
                20 => "Shoulder Girdle Neuropathy",
                21 => "Heredofamilial Neuritis With Brachial Plexus Predilection",
                22 => "Neuritis With Brachial Predilection",
                23 => "Shoulder-girdle Neuropathies",
                24 => "Shoulder-girdle Neuropathy",
                25 => "Parsonage Aldren Turner Syndrome",
                26 => "Parsonage Turner Syndrome",
                27 => "Parsonage-aldren-turner Syndrome",
                28 => "Parsonage-turner Syndrome",
                29 => "Neurite Do Plexo Braquial",
                30 => "Amiotrofia Neurálgica",
                31 => "Neuralgia Amiotrófica",
                32 => "Neuralgia Cervicobraquial",
                33 => "Neuropatia Da Cintura Escapular",
                34 => "Síndrome De Parsonage-turner",
                35 => "Neuritis Del Plexo Braquial",
                36 => "Neuralgia Cervico-braquial",
                37 => "Neuropatía De La Cintura Escapular",
              ],
              "Brachial Plexus Neuropathies" => [
                0 => "Brachial Plexopathy",
                1 => "Brachial Plexus Disease",
                2 => "Brachial Plexus Diseases",
                3 => "Brachial Plexus Disorder",
                4 => "Brachial Plexus Disorders",
                5 => "Brachial Plexus Neuropathy",
                6 => "Dejerine Klumpke Palsy",
                7 => "Dejerine-klumpke Palsy",
                8 => "Erb Duchenne Paralysis",
                9 => "Erb Palsy",
                10 => "Erb Paralyses",
                11 => "Erb Paralysis",
                12 => "Erb's Palsies",
                13 => "Erb's Palsy",
                14 => "Erb-duchenne Paralyses",
                15 => "Erb-duchenne Paralysis",
                16 => "Erbs Palsy",
                17 => "Klumpke Palsy",
                18 => "Klumpke Paralysis",
                19 => "Klumpke's Palsy",
                20 => "Klumpkes Palsy",
                21 => "Lower Brachial Plexus Neuropathy",
                22 => "Lower Brachial Plexus Palsy",
                23 => "Middle Brachial Plexus Neuropathy",
                24 => "Paralysis Of The Lower Brachial Plexus",
                25 => "Brachial Plexopathies",
                26 => "Upper Brachial Plexus Neuropathy",
                27 => "Neuropatias Do Plexo Braquial",
                28 => "Paralisia De Erb",
                29 => "Paralisia De Klumpke",
                30 => "Plexopatia Braquial",
                31 => "Neuropatías Del Plexo Braquial",
                32 => "Parálisis Erb",
                33 => "Parálisis De Klumpke",
                34 => "Plexopatía Braquial",
              ],
              "Brachial Plexus Block" => [
                0 => "Brachial Plexus Anesthesia",
                1 => "Brachial Plexus Blockade",
                2 => "Brachial Plexus Blockades",
                3 => "Brachial Plexus Blocks",
                4 => "Bloqueio Do Plexo Braquial",
                5 => "Bloqueio Anestésico Do Plexo Braquial",
                6 => "Bloqueio De Plexo Braquial",
                7 => "Bloqueo Del Plexo Braquial",
              ],
              "Neonatal Brachial Plexus Palsy" => [
                0 => "Obstetrical Brachial Plexus Lesion",
                1 => "Obstetrical Brachial Plexus Palsy",
                2 => "Paralisia Do Plexo Braquial Neonatal",
                3 => "Lesão Obstétrica Do Plexo Braquial",
                4 => "Paralisia Obstétrica Do Plexo Braquial",
                5 => "Parálisis Neonatal Del Plexo Braquial",
                6 => "Lesión Obstétrica Del Plexo Braquial",
                7 => "Parálisis Obstétrica Del Plexo Braquial",
              ],
              "Median Nerve" => [
                0 => "Median Nerves",
                1 => "Nervo Mediano",
                2 => "Nervio Mediano",
              ],
            ],
            "Adults" => [
              "Adult" => [
                0 => "Adults",
                1 => "Adulto",
                2 => "Adultos",
              ],
              "Middle Aged" => [],
              "Frail Elderly" => [],
              "Young Adult" => [
                0 => "Young Adults",
                1 => "Adulto Jovem",
                2 => "Adultos Jovens",
                3 => "Jovem Adulto",
                4 => "Adulto Joven",
                5 => "Adultos Jóvenes",
                6 => "Joven Adulto",
              ],
              "Latent Autoimmune Diabetes In Adults" => [
                0 => "Latent Autoimmune Diabetes In Adults",
                1 => "Diabetes Mellitus Type 1.5",
                2 => "Type 1.5 Diabetes",
                3 => "Latent Autoimmune Diabetes In Adults Lada",
                4 => "Latent Autoimmune Diabetes Of Adults",
                5 => "Type 1.5 Diabetes Mellitus",
                6 => "Diabetes Autoimune Latente Em Adultos",
                7 => "Diabetes Autoimune Latente De Adultos",
                8 => "Diabetes Autoimune Latente Em Adultos (lada)",
                9 => "Diabetes Mellitus Tipo 1.5",
                10 => "Diabetes Mellitus Do Tipo 1.5",
                11 => "Diabetes Do Tipo 1.5",
                12 => "Diabetes Autoinmune Latente Del Adulto",
                13 => "Diabetes Autoinmune Latente En Adultos",
                14 => "Diabetes Tipo 1.5",
                15 => "Diabetes Autoinmune Latente Del Adulto Lada",
                16 => "Diabetes Autoinmune Latente En Adultos Lada",
              ],
              "Aged" => [
                0 => "Elderly",
                1 => "Idoso",
                2 => "Idosos",
                3 => "Pessoa Idosa",
                4 => "Pessoa De Idade",
                5 => "Pessoas Idosas",
                6 => "Pessoas De Idade",
                7 => "População Idosa",
                8 => "Anciano",
                9 => "Adulto Mayor",
                10 => "Ancianos",
                11 => "Persona Mayor",
                12 => "Persona De Edad",
                13 => "Personas Mayores",
                14 => "Personas De Edad",
              ]
            ],
            "Obstetric" => [
              "Obstetrical Anesthesia" => [
                0 => "Gynecologic Anesthesia",
                1 => "Gynecological Anesthesia",
                2 => "Obstetric Anesthesia",
                3 => "Paracervical Block",
                4 => "Paracervical Blocks",
                5 => "Anestesia Obstétrica",
              ],
              "Obstetric Delivery" => [
                0 => "Obstetric Deliveries",
                1 => "Parto Obstétrico",
                2 => "Liberação Obstétrica",
                3 => "Liberación Obstétrica",
              ],
              "Obstetrical And Gynecological Diagnostic Techniques" => [
                0 => "Obstetrical And Gynecological Diagnostic Techniques",
                1 => "Obstetric And Gynecologic Diagnostic Technic",
                2 => "Obstetrical And Gynecological Diagnostic Technic",
                3 => "Obstetric And Gynecologic Diagnostic Technics",
                4 => "Obstetrical And Gynecological Diagnostic Technics",
                5 => "Obstetric And Gynecologic Diagnostic Technique",
                6 => "Obstetrical And Gynecological Diagnostic Technique",
                7 => "Obstetric And Gynecologic Diagnostic Techniques",
                8 => "Técnicas De Diagnóstico Obstétrico E Ginecológico",
                9 => "Técnicas De Diagnóstico Em Ginecologia E Obstetrícia",
                10 => "Técnicas De Diagnóstico Obstétrico Y Ginecológico",
              ],
              "Obstetrical Extraction" => [
                0 => "Obstetric Extraction",
                1 => "Obstetric Extractions",
                2 => "Obstetrical Extractions",
                3 => "Extração Obstétrica",
                4 => "Extracción Obstétrica",
              ],
              "Obstetric Labor Complications" => [
                0 => "Labor Complication",
                1 => "Obstetric Labor Complication",
                2 => "Labor Complications",
                3 => "Complicações Do Trabalho De Parto",
                4 => "Complicações Do Parto",
                5 => "Complicaciones Del Trabajo De Parto",
                6 => "Complicaciones Del Parto",
              ],
              "Premature Obstetric Labor" => [
                0 => "Premature Labor",
                1 => "Preterm Labor",
                2 => "Trabalho De Parto Prematuro",
                3 => "Parto Prematuro",
                4 => "Parto Pré-termo",
                5 => "Trabalho De Parto Pré-termo",
                6 => "Trabajo De Parto Prematuro",
                7 => "Parto Pretérmino",
                8 => "Trabajo De Parto Pretérmino",
              ],
              "Obstetrical Forceps" => [
                0 => "Obstetric Forcep",
                1 => "Obstetrical Forcep",
                2 => "Obstetric Forceps",
                3 => "Forceps Obstétrico",
              ],
              "Obstetric Nursing" => [
                0 => "Obstetrical Nursing",
                1 => "Obstetric Nursings",
                2 => "Obstetrical Nursings",
                3 => "Enfermagem Obstétrica",
                4 => "Enfermería Obstétrica",
              ],
              "Obstetric Paralysis" => [
                0 => "Obstetric Paralyses",
                1 => "Obstetrical Paralyses",
                2 => "Obstetrical Paralysis",
                3 => "Paralisia Obstétrica",
                4 => "Parálisis Obstétrica",
              ],
              "Obstetric Surgical Procedures" =>[
                0 => "Obstetric Surgeries",
                1 => "Obstetric Surgery",
                2 => "Obstetric Surgical Procedure",
                3 => "Obstetrical Surgeries",
                4 => "Obstetrical Surgery",
                5 => "Obstetrical Surgical Procedure",
                6 => "Obstetrical Surgical Procedures",
                7 => "Procedimentos Cirúrgicos Obstétricos",
                8 => "Procedimientos Quirúrgicos Obstétricos",
              ],
              "Obstetrical Vacuum Extraction" => [
                0 => "Vacuum Extraction Deliveries",
                1 => "Vacuum Extraction Delivery",
                2 => "Obstetric Vacuum Extraction",
                3 => "Obstetric Vacuum Extractions",
                4 => "Obstetrical Vacuum Extractions",
              ],
              "Obstetric Labor" => [
                0 => "Obstetric Analgesia",
              ],
              "Obstetrical Analgesia" => [
                0 => "Obstetric Pain",
              ],
              "Labor Pain" => [
                0 => "Abdominal Deliveries",
                1 => "Abdominal Delivery",
                2 => "C Section (ob)",
                3 => "C-section (ob)",
                4 => "C-sections (ob)",
                5 => "Caesarean Section",
                6 => "Caesarean Sections",
                7 => "Cesarean Sections",
                8 => "Postcesarean Section",
                9 => "Cesárea",
                10 => "Parto Abdominal",
              ],
              "Cesarean Section" => [],
            ],
          ],
          "ImproveSearchQuery" => "",
          "mainLanguage" => "en",
        ]);
    }

    }
