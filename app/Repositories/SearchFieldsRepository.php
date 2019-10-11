<?php


namespace PICOExplorer\Repositories;


class SearchFieldsRepository
{

    static public function get($lang)
    {
        switch ($lang) {
            case 'en':
                return [
                    'fields1' => 'Title, Abstract, DeCS/MeSH terms',
                    'fields2' => 'Title',
                    'fields3' => 'DeCs/MeSH terms',
                ];
            case 'pt':
                return [
                    'fields1' => 'Título, resumo, termos DeCS / MeSH',
                    'fields2' => 'Título',
                    'fields3' => 'Termos DeCS/MeSH',
                ];
            case 'es':
                return [
                    'fields1' => 'Título, resumen, términos DeCS / MeSH',
                    'fields2' => 'Tìtulo',
                    'fields3' => 'Términos DeCS/MeSH',
                ];
            case 'fr':
                return [
                    'fields1' => 'Titre, Résumé, Termes DeCS / MeSH',
                    'fields2' => 'Titre',
                    'fields3' => 'Termes DeCS/MeSH',
                ];
            default:
                return [
                    'fields1' => ['tw:(',')'],
                    'fields2' => ['ti:(',')'],
                    'fields3' => ['mh:(',')']
                ];
        }
    }
}
