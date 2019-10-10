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
            'InitialData.ImproveSearchWords' => 'required|string|min:0',
            'InitialData.langs' => 'required|array|min:1',
            'InitialData.langs.*' => 'required|string|in:es.pt.en.fr',
            'InitialData.PICOnum' => 'required|integer|size:1',
            'InitialData.SavedData' => 'nullable|array',
            'InitialData.mainLanguage' => 'required|string|in:es.pt.en.fr',
        ];
    }

    public static final function responseRules()
    {
        return [
            'results' => 'required|array|min:1',
            'results.SavedData' => 'required|string|min:1',
            //QuerySplit is included in a hidden field inside HTML
            'results.HTMLDescriptors' => 'required|string|min:1',
            'results.HTMLDeCS' => 'required|string|min:1',
        ];
    }

    public final function ControllerTestData()
    {
        return $this->FullDataSimpleQuery();
    }

    public static final function AttributeRules()
    {
        return [
            'PreviousImproveQueryFound' => ['required|string|min:1'],
            'decodedKeywordList' => ['required|array|min:0'],
            'PreviousData' => ['required|array|min:0'],
            'SavedData' => ['required|array|min:0'],
            'ImproveSearchWords' => ['required|array|min:0'],
            'OldSelectedDescriptors' => ['required|array|min:0'],
            'ProcessedDescriptors' => ['required|array|min:0'],
            'QuerySplit' => ['required|array'],
            'KeywordList' => ['array|min:0'],
            'AllKeywords' => ['array|min:0'],
            'ProcessedDeCS' => ['required|array|min:0'],
            'DescriptorsHTML' => ['required|string|min:1'],
            'DeCSHTML' => ['required|string|min:1'],
        ];
    }

    private function FullDataSimpleQuery()
    {
        return json_encode([
            "query" => 'DEngue',
            "OldSelectedDescriptors" => "",
            "ImproveSearchWords" => "[]",
            "langs" => [
                0 => "en",
                1 => "pt",
                2 => "es",
            ],
            "PICOnum" => 1,
            "SavedData" => '{"dengue":{"C02.081.270":{"en":{"term":"Dengue","decs":["Dengue","Break Bone Fever","Break-Bone Fever","Breakbone Fever","Classical Dengue","Classical Dengue Fever","Classical Dengue Fevers","Classical Dengues","Dengue Fever"]},"es":{"term":"Dengue","decs":["Dengue","Fiebre Dengue"]},"pt":{"term":"Dengue","decs":["Dengue","Febre Quebra-Ossos","Febre da Dengue","Infec\u00e7\u00e3o pelo V\u00edrus da Dengue","Infec\u00e7\u00e3o por V\u00edrus da Dengue","Infec\u00e7\u00e3o por V\u00edrus de Dengue"]}},"B04.820.250.350.270":{"en":{"term":"Dengue Virus","decs":["Dengue Virus","Breakbone Fever Virus","Breakbone Fever Viruses","Dengue Viruses"]},"es":{"term":"Virus del Dengue","decs":["Virus del Dengue","Virus de la Fiebre Rompehuesos"]},"pt":{"term":"V\u00edrus da Dengue","decs":["V\u00edrus da Dengue","V\u00edrus da Febre Quebra-Ossos"]}},"C02.081.270.200":{"en":{"term":"Severe Dengue","decs":{"0":"Severe Dengue","1":"Dengue Hemorrhagic Fever","2":"Dengue Shock Syndrome","4":"Severe Dengues","5":"Philippine Hemorrhagic Fever","6":"Singapore Hemorrhagic Fever","7":"Thai Hemorrhagic Fever","8":"Hemorrhagic Dengue","9":"Hemorrhagic Dengues"}},"es":{"term":"Dengue Grave","decs":["Dengue Grave","Dengue Hemorr\u00e1gico","Fiebre Dengue Hemorr\u00e1gica","Fiebre Hemorr\u00e1gica Dengue","Fiebre Hemorr\u00e1gica de Dengue","S\u00edndrome de Choque por Dengue","S\u00edndrome de Shock por Dengue"]},"pt":{"term":"Dengue Grave","decs":["Dengue Grave","Dengue Hemorr\u00e1gica","Febre Hemorr\u00e1gica Dengue","Febre Hemorr\u00e1gica da Dengue","Febre Hemorr\u00e1gica devida ao V\u00edrus do Dengue","Febre Hemorr\u00e1gica pelo V\u00edrus da Dengue","Febre Hemorr\u00e1gica pelo V\u00edrus do Dengue","S\u00edndrome de Choque da Dengue"]}},"D20.215.894.899.162":{"en":{"term":"Dengue Vaccines","decs":["Dengue Vaccines"]},"es":{"term":"Vacunas contra el Dengue","decs":["Vacunas contra el Dengue"]},"pt":{"term":"Vacinas contra Dengue","decs":["Vacinas contra Dengue","Vacinas contra a Dengue","Vacinas contra o V\u00edrus da Dengue"]}}},"zika":{"B04.820.250.350.995":{"en":{"term":"Zika Virus","decs":{"0":"Zika Virus","2":"ZikV"}},"es":{"term":"Virus Zika","decs":["Virus Zika","Virus de Zika","Virus de la Fiebre Zika","Virus del Zika","ZIKV","ZikV","Zika virus","Zikavirus"]},"pt":{"term":"Zika virus","decs":["Zika virus","V\u00edrus Zika","V\u00edrus da Febre Zika","V\u00edrus da Zika","V\u00edrus de Zika","ZIKV","ZikV","Zikavirus"]}},"C02.081.990":{"en":{"term":"Zika Virus Infection","decs":["Zika Virus Infection","Zika Virus Disease","Zika Fever","ZikV Infection"]},"es":{"term":"Infecci\u00f3n por el Virus Zika","decs":["Infecci\u00f3n por el Virus Zika","Enfermedad del Virus Zika","Enfermedad por Virus Zika","Enfermedad por ZIKV","Enfermedad por Zika","Enfermedad por Zika virus","Enfermedad por el Virus Zika","Enfermedad por el Virus de Zika","Enfermedad por el Virus del Zika","Enfermedad por el Zikavirus","Fiebre Zika","Fiebre por Virus Zika","Fiebre por el Virus Zika","Infecci\u00f3n del Virus Zika","Infecci\u00f3n por Virus Zika","Infecci\u00f3n por Vzika","Infecci\u00f3n por ZIKV","Infecci\u00f3n por Zika virus","Infecci\u00f3n por Zikavirus","Infecci\u00f3n por el Virus de Zika","Infecci\u00f3n por el Virus del Zika"]},"pt":{"term":"Infec\u00e7\u00e3o por Zika virus","decs":["Infec\u00e7\u00e3o por Zika virus","Doen\u00e7a pelo V\u00edrus Zika","Doen\u00e7a pelo Zika virus","Doen\u00e7a pelo Zikavirus","Doen\u00e7a por V\u00edrus Zika","Doen\u00e7a por Zika virus","Febre Zika","Febre pelo V\u00edrus Zika","Febre por V\u00edrus Zika","Febre por Zika","Febre por Zika virus","Infecc\u00e7\u00e3o por ZIKV","Infec\u00e7\u00e3o pelo Zika virus","Infec\u00e7\u00e3o pelo Zikavirus","Infec\u00e7\u00e3o por V\u00edrus Zika","Infec\u00e7\u00e3o por Zika","Infec\u00e7\u00e3o por Zikavirus"]}}}}',
            "PreviousImproveQuery" => "",
            "mainLanguage" => "en",
        ]);
    }

    private function FullData()
    {
        return json_encode([
            "query" => 'Dengue OR ("Break bone fever" OR "Break-bone fever" OR "Breakbone fever" OR "Classical dengue" OR "Classical dengue fever" OR "Classical dengue fevers" OR "Classical dengues" OR "Dengue fever" OR "Fiebre dengue" OR "Febre quebra-ossos" OR "Febre da dengue" OR "Infecção pelo vírus da dengue" OR "Infecção por vírus da dengue" OR "Infecção por vírus de dengue") OR ("Dengue virus" OR "Breakbone fever virus" OR "Breakbone fever viruses" OR "Dengue viruses" OR "Virus del dengue" OR "Virus del dengue" OR "Virus de la fiebre rompehuesos" OR "Vírus da dengue" OR "Vírus da dengue" OR "Vírus da febre quebra-ossos") OR ("Severe dengue" OR "Dengue hemorrhagic fever" OR "Dengue shock syndrome" OR "Severe dengues" OR "Philippine hemorrhagic fever" OR "Singapore hemorrhagic fever" OR "Thai hemorrhagic fever" OR "Hemorrhagic dengue" OR "Hemorrhagic dengues" OR "Dengue grave" OR "Dengue grave" OR "Dengue hemorrágico" OR "Fiebre dengue hemorrágica" OR "Fiebre hemorrágica dengue" OR "Fiebre hemorrágica de dengue" OR "Síndrome de choque por dengue" OR "Síndrome de shock por dengue" OR "Dengue grave" OR "Dengue grave" OR "Dengue hemorrágica" OR "Febre hemorrágica dengue" OR "Febre hemorrágica da dengue" OR "Febre hemorrágica devida ao vírus do dengue" OR "Febre hemorrágica pelo vírus da dengue" OR "Febre hemorrágica pelo vírus do dengue" OR "Síndrome de choque da dengue") OR ("Dengue vaccines" OR "Vacunas contra el dengue" OR "Vacunas contra el dengue" OR "Vacinas contra dengue" OR "Vacinas contra dengue" OR "Vacinas contra a dengue" OR "Vacinas contra o vírus da dengue") and Zika OR ("Zika virus" OR Zikv OR "Virus zika" OR "Virus zika" OR "Virus de zika" OR "Virus de la fiebre zika" OR "Virus del zika" OR Zikv OR Zikv OR Zikavirus OR "Vírus zika" OR "Vírus da febre zika" OR "Vírus da zika" OR "Vírus de zika" OR Zikv OR Zikv OR Zikavirus) OR ("Zika virus infection" OR "Zika virus disease" OR "Zika fever" OR "Zikv infection" OR "Infección por el virus zika" OR "Infección por el virus zika" OR "Enfermedad del virus zika" OR "Enfermedad por virus zika" OR "Enfermedad por zikv" OR "Enfermedad por zika" OR "Enfermedad por zika virus" OR "Enfermedad por el virus zika" OR "Enfermedad por el virus de zika" OR "Enfermedad por el virus del zika" OR "Enfermedad por el zikavirus" OR "Fiebre zika" OR "Fiebre por virus zika" OR "Fiebre por el virus zika" OR "Infección del virus zika" OR "Infección por virus zika" OR "Infección por vzika" OR "Infección por zikv" OR "Infección por zika virus" OR "Infección por zikavirus" OR "Infección por el virus de zika" OR "Infección por el virus del zika" OR "Infecção por zika virus" OR "Infecção por zika virus" OR "Doença pelo vírus zika" OR "Doença pelo zika virus" OR "Doença pelo zikavirus" OR "Doença por vírus zika" OR "Doença por zika virus" OR "Febre zika" OR "Febre pelo vírus zika" OR "Febre por vírus zika" OR "Febre por zika" OR "Febre por zika virus" OR "Infeccção por zikv" OR "Infecção pelo zika virus" OR "Infecção pelo zikavirus" OR "Infecção por vírus zika" OR "Infecção por zika" OR "Infecção por zikavirus") ',
            "OldSelectedDescriptors" => "",
            "ImproveSearchWords" => "[]",
            "langs" => [
                0 => "en",
                1 => "pt",
                2 => "es",
            ],
            "PICOnum" => 1,
            "SavedData" => '{"dengue":{"C02.081.270":{"en":{"term":"Dengue","decs":["Dengue","Break Bone Fever","Break-Bone Fever","Breakbone Fever","Classical Dengue","Classical Dengue Fever","Classical Dengue Fevers","Classical Dengues","Dengue Fever"]},"es":{"term":"Dengue","decs":["Dengue","Fiebre Dengue"]},"pt":{"term":"Dengue","decs":["Dengue","Febre Quebra-Ossos","Febre da Dengue","Infec\u00e7\u00e3o pelo V\u00edrus da Dengue","Infec\u00e7\u00e3o por V\u00edrus da Dengue","Infec\u00e7\u00e3o por V\u00edrus de Dengue"]}},"B04.820.250.350.270":{"en":{"term":"Dengue Virus","decs":["Dengue Virus","Breakbone Fever Virus","Breakbone Fever Viruses","Dengue Viruses"]},"es":{"term":"Virus del Dengue","decs":["Virus del Dengue","Virus de la Fiebre Rompehuesos"]},"pt":{"term":"V\u00edrus da Dengue","decs":["V\u00edrus da Dengue","V\u00edrus da Febre Quebra-Ossos"]}},"C02.081.270.200":{"en":{"term":"Severe Dengue","decs":{"0":"Severe Dengue","1":"Dengue Hemorrhagic Fever","2":"Dengue Shock Syndrome","4":"Severe Dengues","5":"Philippine Hemorrhagic Fever","6":"Singapore Hemorrhagic Fever","7":"Thai Hemorrhagic Fever","8":"Hemorrhagic Dengue","9":"Hemorrhagic Dengues"}},"es":{"term":"Dengue Grave","decs":["Dengue Grave","Dengue Hemorr\u00e1gico","Fiebre Dengue Hemorr\u00e1gica","Fiebre Hemorr\u00e1gica Dengue","Fiebre Hemorr\u00e1gica de Dengue","S\u00edndrome de Choque por Dengue","S\u00edndrome de Shock por Dengue"]},"pt":{"term":"Dengue Grave","decs":["Dengue Grave","Dengue Hemorr\u00e1gica","Febre Hemorr\u00e1gica Dengue","Febre Hemorr\u00e1gica da Dengue","Febre Hemorr\u00e1gica devida ao V\u00edrus do Dengue","Febre Hemorr\u00e1gica pelo V\u00edrus da Dengue","Febre Hemorr\u00e1gica pelo V\u00edrus do Dengue","S\u00edndrome de Choque da Dengue"]}},"D20.215.894.899.162":{"en":{"term":"Dengue Vaccines","decs":["Dengue Vaccines"]},"es":{"term":"Vacunas contra el Dengue","decs":["Vacunas contra el Dengue"]},"pt":{"term":"Vacinas contra Dengue","decs":["Vacinas contra Dengue","Vacinas contra a Dengue","Vacinas contra o V\u00edrus da Dengue"]}}},"zika":{"B04.820.250.350.995":{"en":{"term":"Zika Virus","decs":{"0":"Zika Virus","2":"ZikV"}},"es":{"term":"Virus Zika","decs":["Virus Zika","Virus de Zika","Virus de la Fiebre Zika","Virus del Zika","ZIKV","ZikV","Zika virus","Zikavirus"]},"pt":{"term":"Zika virus","decs":["Zika virus","V\u00edrus Zika","V\u00edrus da Febre Zika","V\u00edrus da Zika","V\u00edrus de Zika","ZIKV","ZikV","Zikavirus"]}},"C02.081.990":{"en":{"term":"Zika Virus Infection","decs":["Zika Virus Infection","Zika Virus Disease","Zika Fever","ZikV Infection"]},"es":{"term":"Infecci\u00f3n por el Virus Zika","decs":["Infecci\u00f3n por el Virus Zika","Enfermedad del Virus Zika","Enfermedad por Virus Zika","Enfermedad por ZIKV","Enfermedad por Zika","Enfermedad por Zika virus","Enfermedad por el Virus Zika","Enfermedad por el Virus de Zika","Enfermedad por el Virus del Zika","Enfermedad por el Zikavirus","Fiebre Zika","Fiebre por Virus Zika","Fiebre por el Virus Zika","Infecci\u00f3n del Virus Zika","Infecci\u00f3n por Virus Zika","Infecci\u00f3n por Vzika","Infecci\u00f3n por ZIKV","Infecci\u00f3n por Zika virus","Infecci\u00f3n por Zikavirus","Infecci\u00f3n por el Virus de Zika","Infecci\u00f3n por el Virus del Zika"]},"pt":{"term":"Infec\u00e7\u00e3o por Zika virus","decs":["Infec\u00e7\u00e3o por Zika virus","Doen\u00e7a pelo V\u00edrus Zika","Doen\u00e7a pelo Zika virus","Doen\u00e7a pelo Zikavirus","Doen\u00e7a por V\u00edrus Zika","Doen\u00e7a por Zika virus","Febre Zika","Febre pelo V\u00edrus Zika","Febre por V\u00edrus Zika","Febre por Zika","Febre por Zika virus","Infecc\u00e7\u00e3o por ZIKV","Infec\u00e7\u00e3o pelo Zika virus","Infec\u00e7\u00e3o pelo Zikavirus","Infec\u00e7\u00e3o por V\u00edrus Zika","Infec\u00e7\u00e3o por Zika","Infec\u00e7\u00e3o por Zikavirus"]}}}}',
            "PreviousImproveQuery" => "",
            "mainLanguage" => "en",
        ]);
    }

    private function SuperSimpleData()
    {
        return json_encode([
            "query" => "dengue",
            "OldSelectedDescriptors" => "",
            "ImproveSearchWords" => "",
            "langs" => [
                0 => "en",
                1 => "pt",
            ],
            "PICOnum" => 1,
            "SavedData" => "",
            "PreviousImproveQuery" => "",
            "mainLanguage" => "en",
        ]);
    }


    private function SimpleData()
    {
        return json_encode([
            "query" => "dengue AND zika",
            "OldSelectedDescriptors" => "",
            "ImproveSearchWords" => "",
            "langs" => [
                0 => "en",
                1 => "pt",
            ],
            "PICOnum" => 1,
            "SavedData" => "",
            "PreviousImproveQuery" => "",
            "mainLanguage" => "en",
        ]);
    }

    private function DataWithErrors()
    {
        return json_encode([
            'ImproveSearchWords' => "[]",
            'OldSelectedDescriptors' => "",
            'PICOnum' => 1,
            'PreviousImproveQuery' => "",
            'SavedData' => '{"dengue":{"C02.081.270":{"en":{"term":"Dengue","decs":["Dengue","Break Bone Fever","Break-Bone Fever","Breakbone Fever","Classical Dengue","Classical Dengue Fever","Classical Dengue Fevers","Classical Dengues","Dengue Fever"]},"es":{"term":"Dengue","decs":["Dengue","Fiebre Dengue"]},"pt":{"term":"Dengue","decs":["Dengue","Febre Quebra-Ossos","Febre da Dengue","Infec\u00e7\u00e3o pelo V\u00edrus da Dengue","Infec\u00e7\u00e3o por V\u00edrus da Dengue","Infec\u00e7\u00e3o por V\u00edrus de Dengue"]}},"B04.820.250.350.270":{"en":{"term":"Dengue Virus","decs":["Dengue Virus","Breakbone Fever Virus","Breakbone Fever Viruses","Dengue Viruses"]},"es":{"term":"Virus del Dengue","decs":["Virus del Dengue","Virus de la Fiebre Rompehuesos"]},"pt":{"term":"V\u00edrus da Dengue","decs":["V\u00edrus da Dengue","V\u00edrus da Febre Quebra-Ossos"]}},"C02.081.270.200":{"en":{"term":"Severe Dengue","decs":{"0":"Severe Dengue","1":"Dengue Hemorrhagic Fever","2":"Dengue Shock Syndrome","4":"Severe Dengues","5":"Philippine Hemorrhagic Fever","6":"Singapore Hemorrhagic Fever","7":"Thai Hemorrhagic Fever","8":"Hemorrhagic Dengue","9":"Hemorrhagic Dengues"}},"es":{"term":"Dengue Grave","decs":["Dengue Grave","Dengue Hemorr\u00e1gico","Fiebre Dengue Hemorr\u00e1gica","Fiebre Hemorr\u00e1gica Dengue","Fiebre Hemorr\u00e1gica de Dengue","S\u00edndrome de Choque por Dengue","S\u00edndrome de Shock por Dengue"]},"pt":{"term":"Dengue Grave","decs":["Dengue Grave","Dengue Hemorr\u00e1gica","Febre Hemorr\u00e1gica Dengue","Febre Hemorr\u00e1gica da Dengue","Febre Hemorr\u00e1gica devida ao V\u00edrus do Dengue","Febre Hemorr\u00e1gica pelo V\u00edrus da Dengue","Febre Hemorr\u00e1gica pelo V\u00edrus do Dengue","S\u00edndrome de Choque da Dengue"]}},"D20.215.894.899.162":{"en":{"term":"Dengue Vaccines","decs":["Dengue Vaccines"]},"es":{"term":"Vacunas contra el Dengue","decs":["Vacunas contra el Dengue"]},"pt":{"term":"Vacinas contra Dengue","decs":["Vacinas contra Dengue","Vacinas contra a Dengue","Vacinas contra o V\u00edrus da Dengue"]}}},"zika":{"B04.820.250.350.995":{"en":{"term":"Zika Virus","decs":{"0":"Zika Virus","2":"ZikV"}},"es":{"term":"Virus Zika","decs":["Virus Zika","Virus de Zika","Virus de la Fiebre Zika","Virus del Zika","ZIKV","ZikV","Zika virus","Zikavirus"]},"pt":{"term":"Zika virus","decs":["Zika virus","V\u00edrus Zika","V\u00edrus da Febre Zika","V\u00edrus da Zika","V\u00edrus de Zika","ZIKV","ZikV","Zikavirus"]}},"C02.081.990":{"en":{"term":"Zika Virus Infection","decs":["Zika Virus Infection","Zika Virus Disease","Zika Fever","ZikV Infection"]},"es":{"term":"Infecci\u00f3n por el Virus Zika","decs":["Infecci\u00f3n por el Virus Zika","Enfermedad del Virus Zika","Enfermedad por Virus Zika","Enfermedad por ZIKV","Enfermedad por Zika","Enfermedad por Zika virus","Enfermedad por el Virus Zika","Enfermedad por el Virus de Zika","Enfermedad por el Virus del Zika","Enfermedad por el Zikavirus","Fiebre Zika","Fiebre por Virus Zika","Fiebre por el Virus Zika","Infecci\u00f3n del Virus Zika","Infecci\u00f3n por Virus Zika","Infecci\u00f3n por Vzika","Infecci\u00f3n por ZIKV","Infecci\u00f3n por Zika virus","Infecci\u00f3n por Zikavirus","Infecci\u00f3n por el Virus de Zika","Infecci\u00f3n por el Virus del Zika"]},"pt":{"term":"Infec\u00e7\u00e3o por Zika virus","decs":["Infec\u00e7\u00e3o por Zika virus","Doen\u00e7a pelo V\u00edrus Zika","Doen\u00e7a pelo Zika virus","Doen\u00e7a pelo Zikavirus","Doen\u00e7a por V\u00edrus Zika","Doen\u00e7a por Zika virus","Febre Zika","Febre pelo V\u00edrus Zika","Febre por V\u00edrus Zika","Febre por Zika","Febre por Zika virus","Infecc\u00e7\u00e3o por ZIKV","Infec\u00e7\u00e3o pelo Zika virus","Infec\u00e7\u00e3o pelo Zikavirus","Infec\u00e7\u00e3o por V\u00edrus Zika","Infec\u00e7\u00e3o por Zika","Infec\u00e7\u00e3o por Zikavirus"]}}}}',
            'langs' => [
                0 => "en",
                1 => "pt",
                2 => "es",
            ],
            'mainLanguage' => "en",
            'query' => 'Dengue OR ("Break bone fever" OR "Break-bone fever" OR "Breakbone fever" OR "Classical dengue" OR "Classical dengue fever" OR "Classical dengue fevers" OR "Classical dengues" OR "Dengue fever" OR "Fiebre dengue" OR "Febre quebra-ossos" OR "Febre da dengue" OR "Infecção pelo vírus da dengue" OR "Infecção por vírus da dengue" OR "Infecção por vírus de dengue") OR ("Dengue virus" OR "Breakbone fever virus" OR "Breakbone fever viruses" OR "Dengue viruses" OR "Virus del dengue" OR "Virus del dengue" OR "Virus de la fiebre rompehuesos" OR "Vírus da dengue" OR "Vírus da dengue" OR "Vírus da febre quebra-ossos") OR ("Severe dengue" OR "Dengue hemorrhagic fever" OR "Dengue shock syndrome" OR "Severe dengues" OR "Philippine hemorrhagic fever" OR "Singapore hemorrhagic fever" OR "Thai hemorrhagic fever" OR "Hemorrhagic dengue" OR "Hemorrhagic dengues" OR "Dengue grave" OR "Dengue grave" OR "Dengue hemorrágico" OR "Fiebre dengue hemorrágica" OR "Fiebre hemorrágica dengue" OR "Fiebre hemorrágica de dengue" OR "Síndrome de choque por dengue" OR "Síndrome de shock por dengue" OR "Dengue grave" OR "Dengue grave" OR "Dengue hemorrágica" OR "Febre hemorrágica dengue" OR "Febre hemorrágica da dengue" OR "Febre hemorrágica devida ao vírus do dengue" OR "Febre hemorrágica pelo vírus da dengue" OR "Febre hemorrágica pelo vírus do dengue" OR "Síndrome de choque da dengue") OR ("Dengue vaccines" OR "Vacunas contra el dengue" OR "Vacunas contra el dengue" OR "Vacinas contra dengue" OR "Vacinas contra dengue" OR "Vacinas contra a dengue" OR "Vacinas contra o vírus da dengue") OR ( OR  OR  OR  OR  OR  OR  OR  OR  OR  OR  OR  OR  OR ) OR ( OR  OR  OR  OR  OR  OR  OR  OR  OR  OR  OR  OR  OR  OR  OR  OR  OR  OR  OR  OR  OR  OR  OR  OR  OR ) AND Zika OR ("Zika virus" OR Zikv OR "Virus zika" OR "Virus zika" OR "Virus de zika" OR "Virus de la fiebre zika" OR "Virus del zika" OR Zikv OR Zikv OR Zikavirus OR "Vírus zika" OR "Vírus da febre zika" OR "Vírus da zika" OR "Vírus de zika" OR Zikv OR Zikv OR Zikavirus) OR ("Zika virus infection" OR "Zika virus disease" OR "Zika fever" OR "Zikv infection" OR "Infección por el virus zika" OR "Infección por el virus zika" OR "Enfermedad del virus zika" OR "Enfermedad por virus zika" OR "Enfermedad por zikv" OR "Enfermedad por zika" OR "Enfermedad por zika virus" OR "Enfermedad por el virus zika" OR "Enfermedad por el virus de zika" OR "Enfermedad por el virus del zika" OR "Enfermedad por el zikavirus" OR "Fiebre zika" OR "Fiebre por virus zika" OR "Fiebre por el virus zika" OR "Infección del virus zika" OR "Infección por virus zika" OR "Infección por vzika" OR "Infección por zikv" OR "Infección por zika virus" OR "Infección por zikavirus" OR "Infección por el virus de zika" OR "Infección por el virus del zika" OR "Infecção por zika virus" OR "Infecção por zika virus" OR "Doença pelo vírus zika" OR "Doença pelo zika virus" OR "Doença pelo zikavirus" OR "Doença por vírus zika" OR "Doença por zika virus" OR "Febre zika" OR "Febre pelo vírus zika" OR "Febre por vírus zika" OR "Febre por zika" OR "Febre por zika virus" OR "Infeccção por zikv" OR "Infecção pelo zika virus" OR "Infecção pelo zikavirus" OR "Infecção por vírus zika" OR "Infecção por zika" OR "Infecção por zikavirus") OR ( OR  OR  OR  OR  OR  OR  OR  OR  OR  OR  OR  OR  OR  OR  OR  OR )',
        ]);
    }

    private function DataWithVeryAbnormalError()
    {
        return json_encode([
            'ImproveSearchWords' => "[]",
            'OldSelectedDescriptors' => "",
            'PICOnum' => 1,
            'PreviousImproveQuery' => "",
            'SavedData' => '{"dengue":{"C02.081.270":{"en":{"term":"Dengue","decs":["Dengue","Break Bone Fever","Break-Bone Fever","Breakbone Fever","Classical Dengue","Classical Dengue Fever","Classical Dengue Fevers","Classical Dengues","Dengue Fever"]},"es":{"term":"Dengue","decs":["Dengue","Fiebre Dengue"]},"pt":{"term":"Dengue","decs":["Dengue","Febre Quebra-Ossos","Febre da Dengue","Infec\u00e7\u00e3o pelo V\u00edrus da Dengue","Infec\u00e7\u00e3o por V\u00edrus da Dengue","Infec\u00e7\u00e3o por V\u00edrus de Dengue"]}},"B04.820.250.350.270":{"en":{"term":"Dengue Virus","decs":["Dengue Virus","Breakbone Fever Virus","Breakbone Fever Viruses","Dengue Viruses"]},"es":{"term":"Virus del Dengue","decs":["Virus del Dengue","Virus de la Fiebre Rompehuesos"]},"pt":{"term":"V\u00edrus da Dengue","decs":["V\u00edrus da Dengue","V\u00edrus da Febre Quebra-Ossos"]}},"C02.081.270.200":{"en":{"term":"Severe Dengue","decs":{"0":"Severe Dengue","1":"Dengue Hemorrhagic Fever","2":"Dengue Shock Syndrome","4":"Severe Dengues","5":"Philippine Hemorrhagic Fever","6":"Singapore Hemorrhagic Fever","7":"Thai Hemorrhagic Fever","8":"Hemorrhagic Dengue","9":"Hemorrhagic Dengues"}},"es":{"term":"Dengue Grave","decs":["Dengue Grave","Dengue Hemorr\u00e1gico","Fiebre Dengue Hemorr\u00e1gica","Fiebre Hemorr\u00e1gica Dengue","Fiebre Hemorr\u00e1gica de Dengue","S\u00edndrome de Choque por Dengue","S\u00edndrome de Shock por Dengue"]},"pt":{"term":"Dengue Grave","decs":["Dengue Grave","Dengue Hemorr\u00e1gica","Febre Hemorr\u00e1gica Dengue","Febre Hemorr\u00e1gica da Dengue","Febre Hemorr\u00e1gica devida ao V\u00edrus do Dengue","Febre Hemorr\u00e1gica pelo V\u00edrus da Dengue","Febre Hemorr\u00e1gica pelo V\u00edrus do Dengue","S\u00edndrome de Choque da Dengue"]}},"D20.215.894.899.162":{"en":{"term":"Dengue Vaccines","decs":["Dengue Vaccines"]},"es":{"term":"Vacunas contra el Dengue","decs":["Vacunas contra el Dengue"]},"pt":{"term":"Vacinas contra Dengue","decs":["Vacinas contra Dengue","Vacinas contra a Dengue","Vacinas contra o V\u00edrus da Dengue"]}}},"zika":{"B04.820.250.350.995":{"en":{"term":"Zika Virus","decs":{"0":"Zika Virus","2":"ZikV"}},"es":{"term":"Virus Zika","decs":["Virus Zika","Virus de Zika","Virus de la Fiebre Zika","Virus del Zika","ZIKV","ZikV","Zika virus","Zikavirus"]},"pt":{"term":"Zika virus","decs":["Zika virus","V\u00edrus Zika","V\u00edrus da Febre Zika","V\u00edrus da Zika","V\u00edrus de Zika","ZIKV","ZikV","Zikavirus"]}},"C02.081.990":{"en":{"term":"Zika Virus Infection","decs":["Zika Virus Infection","Zika Virus Disease","Zika Fever","ZikV Infection"]},"es":{"term":"Infecci\u00f3n por el Virus Zika","decs":["Infecci\u00f3n por el Virus Zika","Enfermedad del Virus Zika","Enfermedad por Virus Zika","Enfermedad por ZIKV","Enfermedad por Zika","Enfermedad por Zika virus","Enfermedad por el Virus Zika","Enfermedad por el Virus de Zika","Enfermedad por el Virus del Zika","Enfermedad por el Zikavirus","Fiebre Zika","Fiebre por Virus Zika","Fiebre por el Virus Zika","Infecci\u00f3n del Virus Zika","Infecci\u00f3n por Virus Zika","Infecci\u00f3n por Vzika","Infecci\u00f3n por ZIKV","Infecci\u00f3n por Zika virus","Infecci\u00f3n por Zikavirus","Infecci\u00f3n por el Virus de Zika","Infecci\u00f3n por el Virus del Zika"]},"pt":{"term":"Infec\u00e7\u00e3o por Zika virus","decs":["Infec\u00e7\u00e3o por Zika virus","Doen\u00e7a pelo V\u00edrus Zika","Doen\u00e7a pelo Zika virus","Doen\u00e7a pelo Zikavirus","Doen\u00e7a por V\u00edrus Zika","Doen\u00e7a por Zika virus","Febre Zika","Febre pelo V\u00edrus Zika","Febre por V\u00edrus Zika","Febre por Zika","Febre por Zika virus","Infecc\u00e7\u00e3o por ZIKV","Infec\u00e7\u00e3o pelo Zika virus","Infec\u00e7\u00e3o pelo Zikavirus","Infec\u00e7\u00e3o por V\u00edrus Zika","Infec\u00e7\u00e3o por Zika","Infec\u00e7\u00e3o por Zikavirus"]}}}}',
            'langs' => [
                0 => "en",
                1 => "pt",
                2 => "es",
            ],
            'mainLanguage' => "en",
            'query' => '((Dengue OR ("ak-bone fever" OR "Breakbone fever" OR "Classical dengue" OR "Classical dengue fever" OR "Classical dengue fevers" OR "Classical dengues" OR "Dengue fever" OR "Fiebre dengue" OR "Febre quebra-ossos" OR "Febre da dengue" OR "Infecção pelo vírus da dengue" OR "Infecção por vírus da dengue" OR "Infecção por vírus de dengue") OR ("Dengue virus" OR "Breakbone fever virus" OR "Breakbone fever viruses" OR "Dengue viruses" OR "Virus del dengue" OR "Virus del dengue" OR "Virus de la fiebre rompehuesos" OR "Vírus da dengue" OR "Vírus da dengue" OR "Vírus da febre quebra-ossos") OR ("Severe dengue" OR "Dengue hemorrhagic fever" OR "Dengue shock syndrome" OR "Severe dengues" OR "Philippine hemorrhagic fever" OR "Singapore hemorrhagic fever" OR "Thai hemorrhagic fever" OR "Hemorrhagic dengue" OR "Hemorrhagic dengues" OR "Dengue grave" OR "Dengue grave" OR "Dengue hemorrágico" OR "Fiebre dengue hemorrágica" OR "Fiebre hemorrágica dengue" OR "Fiebre hemorrágica de dengue" OR "Síndrome de choque por dengue" OR "Síndrome de shock por dengue" OR "Dengue grave" OR "Dengue grave" OR "Dengue hemorrágica" OR "Febre hemorrágica dengue" OR "Febre hemorrágica da dengue" OR "Febre hemorrágica devida ao vírus do dengue" OR "Febre hemorrágica pelo vírus da dengue" OR "Febre hemorrágica pelo vírus do dengue" OR "Síndrome de choque da dengue") OR ("Dengue vaccines" OR "Vacunas contra el dengue" OR "Vacunas contra el dengue" OR "Vacinas contra dengue" OR "Vacinas contra dengue" OR "Vacinas contra a dengue" OR "Vacinas contra o vírus da dengue")) AND (Zika OR ("Zika virus" OR Zikv OR "Virus zika" OR "Virus zika" OR "Virus de zika" OR "Virus de la fiebre zika" OR "Virus del zika" OR Zikv OR Zikv OR Zikavirus OR "Vírus zika" OR "Vírus da febre zika" OR "Vírus da zika" OR "Vírus de zika" OR Zikv OR Zikv OR Zikavirus) OR ("Zika virus infection" OR "Zika virus disease" OR "Zika fever" OR "Zikv infection" OR "Infección por el virus zika" OR "Infección por el virus zika" OR "Enfermedad del virus zika" OR "Enfermedad por virus zika" OR "Enfermedad por zikv" OR "Enfermedad por zika" OR "Enfermedad por zika virus" OR "Enfermedad por el virus zika" OR "Enfermedad por el virus de zika" OR "Enfermedad por el virus del zika" OR "Enfermedad por el zikavirus" OR "Fiebre zika" OR "Fiebre por virus zika" OR "Fiebre por el virus zika" OR "Infección del virus zika" OR "Infección por virus zika" OR "Infección por vzika" OR "Infección por zikv" OR "Infección por zika virus" OR "Infección por zikavirus" OR "Infección por el virus de zika" OR "Infección por el virus del zika" OR "Infecção por zika virus" OR "Infecção por zika virus" OR "Doença pelo vírus zika" OR "Doença pelo zika virus" OR "Doença pelo zikavirus" OR "Doença por vírus zika" OR "Doença por zika virus" OR "Febre zika" OR "Febre pelo vírus zika" OR "Febre por vírus zika" OR "Febre por zika" OR "Febre por zika virus" OR "Infeccção por zikv" OR "Infecção pelo zika virus" OR "Infecção pelo zikavirus" OR "Infecção por vírus zika" OR "Infecção por zika" OR "Infecção por zikavirus")))',
        ]);
    }

    private function DataWithExtremelyAbnormalError()
    {
        return json_encode([
            'ImproveSearchWords' => "[]",
            'OldSelectedDescriptors' => "",
            'PICOnum' => 1,
            'PreviousImproveQuery' => "",
            'SavedData' => '',
            'langs' => [
                0 => "en",
                1 => "pt",
                2 => "es",
            ],
            'mainLanguage' => "en",
            'query' => "if($(modalone).find('.DontShowButton'))",
        ]);
    }


    private function DataWithSelectedDescriptors()
    {
        return json_encode([
            'ImproveSearchWords' => "[]",
            'OldSelectedDescriptors' => "",
            'PICOnum' => 1,
            'PreviousImproveQuery' => "",
            'SavedData' => '',
            'langs' => [
                0 => "en",
                1 => "pt",
                2 => "es",
            ],
            'mainLanguage' => "en",
            'query' => "if($(modalone).find('.DontShowButton'))",
        ]);
    }

}
