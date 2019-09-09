<?php

namespace PICOExplorer\Models\DataModels;

use PICOExplorer\Models\MainModelsModel;

class QueryBuildModel extends MainModelsModel
{

    public static final function requestRules()
    {
        return [
            'InitialData' => 'required|array|min:1',
            'InitialData.PICOnum' => 'required|string|min:1',
            'InitialData.QuerySplit' => 'required|array',
            'InitialData.DeCSResults' => 'required|array|min:1',
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
        return json_encode(
            ["PICOnum" => "1",
                "QuerySplit" => "[[\"type\" => \"key\",\"value\" => \"dengue\"]]", "DeCSResults" => "[\"dengue\" => [\"C02.081.270\" => [\"en\" => [\"decs\" => [\"0\" => \"Break Bone Fever\",\"1\" => \"Break-Bone Fever\",\"2\" => \"Breakbone Fever\",\"3\" => \"Classical Dengue\",\"4\" => \"Classical Dengue Fever\",\"5\" => \"Classical Dengue Fevers\",\"6\" => \"Classical Dengues\",\"7\" => \"Dengue Fever\",\"8\" => \"Severe Dengue\",\"9\" => \"Dengue Hemorrhagic Fever\",\"10\" => \"Dengue Shock Syndrome\",\"12\" => \"Severe Dengues\",\"13\" => \"Philippine Hemorrhagic Fever\",\"14\" => \"Singapore Hemorrhagic Fever\",\"15\" => \"Thai Hemorrhagic Fever\",\"16\" => \"Hemorrhagic Dengue\",\"17\" => \"Hemorrhagic Dengues\"],\"term\" => \"Dengue\"],\"pt\" => [\"decs\" => [\"Febre Quebra-Ossos\",\"Febre da Dengue\",\"Infec\\u00e7\\u00e3o pelo V\\u00edrus da Dengue\",\"Infec\\u00e7\\u00e3o por V\\u00edrus da Dengue\",\"Infec\\u00e7\\u00e3o por V\\u00edrus de Dengue\",\"Dengue Grave\",\"Dengue Hemorr\\u00e1gica\",\"Febre Hemorr\\u00e1gica Dengue\",\"Febre Hemorr\\u00e1gica da Dengue\",\"Febre Hemorr\\u00e1gica devida ao V\\u00edrus do Dengue\",\"Febre Hemorr\\u00e1gica pelo V\\u00edrus da Dengue\",\"Febre Hemorr\\u00e1gica pelo V\\u00edrus do Dengue\",\"S\\u00edndrome de Choque da Dengue\"],\"term\" => \"Dengue\"],\"es\" => [\"decs\" => [\"Fiebre Dengue\",\"Dengue Grave\",\"Dengue Hemorr\\u00e1gico\",\"Fiebre Dengue Hemorr\\u00e1gica\",\"Fiebre Hemorr\\u00e1gica Dengue\",\"Fiebre Hemorr\\u00e1gica de Dengue\",\"S\\u00edndrome de Choque por Dengue\",\"S\\u00edndrome de Shock por Dengue\"],\"term\" => \"Dengue\"]],\"B04.820.250.350.270\" => [\"en\" => [\"decs\" => [\"0\" => \"Breakbone Fever Virus\",\"1\" => \"Breakbone Fever Viruses\",\"2\" => \"Dengue Viruses\",\"6\" => \"Dengue Virus\"],\"term\" => \"Dengue Virus\"],\"pt\" => [\"decs\" => [\"V\\u00edrus da Febre Quebra-Ossos\"],\"term\" => \"V\\u00edrus da Dengue\"],\"es\" => [\"decs\" => [\"Virus de la Fiebre Rompehuesos\"],\"term\" => \"Virus del Dengue\"]],\"C02.081.270.200\" => [\"en\" => [\"decs\" => [\"Dengue Hemorrhagic Fever\",\"Dengue Shock Syndrome\",\"Severe Dengue\",\"Severe Dengues\",\"Philippine Hemorrhagic Fever\",\"Singapore Hemorrhagic Fever\",\"Thai Hemorrhagic Fever\",\"Hemorrhagic Dengue\",\"Hemorrhagic Dengues\"],\"term\" => \"Severe Dengue\"],\"pt\" => [\"decs\" => [\"Dengue Hemorr\\u00e1gica\",\"Febre Hemorr\\u00e1gica Dengue\",\"Febre Hemorr\\u00e1gica da Dengue\",\"Febre Hemorr\\u00e1gica devida ao V\\u00edrus do Dengue\",\"Febre Hemorr\\u00e1gica pelo V\\u00edrus da Dengue\",\"Febre Hemorr\\u00e1gica pelo V\\u00edrus do Dengue\",\"S\\u00edndrome de Choque da Dengue\"],\"term\" => \"Dengue Grave\"],\"es\" => [\"decs\" => [\"Dengue Hemorr\\u00e1gico\",\"Fiebre Dengue Hemorr\\u00e1gica\",\"Fiebre Hemorr\\u00e1gica Dengue\",\"Fiebre Hemorr\\u00e1gica de Dengue\",\"S\\u00edndrome de Choque por Dengue\",\"S\\u00edndrome de Shock por Dengue\"],\"term\" => \"Dengue Grave\"]],\"D20.215.894.899.162\" => [\"en\" => [\"decs\" => [\"Dengue Vaccines\"],\"term\" => \"Dengue Vaccines\"],\"pt\" => [\"decs\" => [\"Vacinas contra a Dengue\",\"Vacinas contra o V\\u00edrus da Dengue\"],\"term\" => \"Vacinas contra Dengue\"],\"es\" => [\"decs\" => [],\"term\" => \"Vacunas contra el Dengue\"]]]]", "SelectedDescriptors" => ["dengue" => ["Dengue" => ["Dengue", "Break Bone Fever", "Break-Bone Fever", "Breakbone Fever", "Classical Dengue", "Classical Dengue Fever", "Classical Dengue Fevers", "Classical Dengues", "Dengue Fever", "Severe Dengue", "Dengue Hemorrhagic Fever", "Dengue Shock Syndrome", "Severe Dengues", "Philippine Hemorrhagic Fever", "Singapore Hemorrhagic Fever", "Thai Hemorrhagic Fever", "Hemorrhagic Dengue", "Hemorrhagic Dengues"], "Dengue Virus" => ["Dengue Virus", "Breakbone Fever Virus", "Breakbone Fever Viruses", "Dengue Viruses", "Dengue Virus"], "Severe Dengue" => ["Severe Dengue", "Dengue Hemorrhagic Fever", "Dengue Shock Syndrome", "Severe Dengue", "Severe Dengues", "Philippine Hemorrhagic Fever", "Singapore Hemorrhagic Fever", "Thai Hemorrhagic Fever", "Hemorrhagic Dengue", "Hemorrhagic Dengues"], "Dengue Vaccines" => ["Dengue Vaccines", "Dengue Vaccines"]]],
                "ImproveSearchQuery" => "",
                "mainLanguage" => "en",
            ]);
    }


    public static final function AttributeRules()
    {
        return [
            'ProcessedQueries'=>[],
            'QuerySplit'=>[],
            'results',
            'SelectedDescriptors'=>[],
            'ImproveSearchQuery'=>[],
            'newQuery'=>[],
        ];
    }

}
