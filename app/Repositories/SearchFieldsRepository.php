<?php


namespace PICOExplorer\Repositories;


class SearchFieldsRepository
{

    static public function get($lang)
    {
        switch ($lang) {
            case 'en':
                return [
                    'fields1' => 'All Fields',
                    'fields2' => 'Title, Abstract, DeCS/MeSH terms',
                    'fields3' => 'Title',
                    'fields4' => 'DeCs/MeSH terms',
                ];
            case 'pt':
                return [
                    'fields1' => 'Todos os campos',
                    'fields2' => 'Título, resumo, termos DeCS / MeSH',
                    'fields3' => 'Título',
                    'fields4' => 'Termos DeCS/MeSH',
                ];
            case 'es':
                return [
                    'fields1' => 'Todos los campos',
                    'fields2' => 'Título, resumen, términos DeCS / MeSH',
                    'fields3' => 'Tìtulo',
                    'fields4' => 'Términos DeCS/MeSH',
                ];
            case 'fr':
                return [
                    'fields1' => 'Tous les champs',
                    'fields2' => 'Titre, Résumé, Termes DeCS / MeSH',
                    'fields3' => 'Titre',
                    'fields4' => 'Termes DeCS/MeSH',
                ];
            default:
                return [
                    'fields1' => ['(',')'],
                    'fields2' => ['tw:(',')'],
                    'fields3' => ['ti:(',')'],
                    'fields4' => ['mh:(',')']
                ];
        }
    }
}
