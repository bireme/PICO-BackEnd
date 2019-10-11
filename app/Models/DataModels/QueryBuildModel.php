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
        return $this->IssuePost57();
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
}
