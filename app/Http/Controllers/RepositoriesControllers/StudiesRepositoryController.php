<?php

namespace PICOExplorer\Http\Controllers\RepositoriesControllers;

use PICOExplorer\Repositories\TypeOfStudyRepository;
use PICOExplorer\Http\Controllers\Controller;

class StudiesRepositoryController extends Controller
{
    //
    /**
     * @return array
     */
    public function TypeOfStudies(){
        return TypeOfStudyRepository::get();
    }
}
