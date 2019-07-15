<?php

namespace PICOExplorer\Http\Controllers\RepositoriesControllers;

use PICOExplorer\Repositories\SearchFieldsRepository;
use PICOExplorer\Http\Controllers\Controller;

class SearchFieldsRepositoryController extends Controller
{

    /**
     * @return array
     */
    public function SearchFields(){
        return SearchFieldsRepository::get(app()->getLocale());
    }

    /**
     * @return array
     */
    public function FieldStructure(){
        return SearchFieldsRepository::get(-1);
    }

}
