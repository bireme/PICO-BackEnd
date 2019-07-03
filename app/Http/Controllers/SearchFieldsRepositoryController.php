<?php

namespace PICOExplorer\Http\Controllers;

use Illuminate\Http\Request;
use PICOExplorer\Repositories\SearchFieldsRepository;

class SearchFieldsRepositoryController extends Controller
{

    public function SearchFields(){
        return SearchFieldsRepository::get(app()->getLocale());
    }

    public function FieldStructure(){
        return SearchFieldsRepository::get(-1);
    }

}
