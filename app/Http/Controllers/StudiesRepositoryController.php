<?php

namespace PICOExplorer\Http\Controllers;

use Illuminate\Http\Request;
use PICOExplorer\Repositories\TypeOfStudyRepository;

class StudiesRepositoryController extends Controller
{
    //
    public function TypeOfStudies(){
        return TypeOfStudyRepository::get();
    }
}
