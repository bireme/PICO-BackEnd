<?php


namespace PICOExplorer\Repositories;


class TypeOfStudyRepository
{

    static public function get(){
        return ['Case report',
            'Systematic reviews',
            'Cohort study',
            'Practice guideline',
            'Controlled clinical trial',
            'Health technology assessment',
            'Overview',
            'Health economic evaluation',
            'Evidence synthesis'
        ];
    }
}
