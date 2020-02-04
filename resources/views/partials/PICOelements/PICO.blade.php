<?php

use \PICOExplorer\Http\Controllers\RepositoriesControllers\SearchFieldsRepositoryController;
use \PICOExplorer\Http\Controllers\RepositoriesControllers\StudiesRepositoryController;
$TOS = (new StudiesRepositoryController())->TypeOfStudies() ?? array();
$FieldNames = (new SearchFieldsRepositoryController())->SearchFields() ?? array();

?>
<div class="row">
    <div class="col text-right">
        <a href="#" class="ConfigDeCS">
            {{ mb_strtolower(trans('lang.conf')) }}
        </a>
    </div>
</div>

<div class="accordion" id="accordionPicos">
    @for($PICOiterative=1;$PICOiterative<6;$PICOiterative++)
        @if($PICOiterative<5)
            @include('layout.PICOelementlayout',['FieldNames'=>$FieldNames,'PICOiterative'=>$PICOiterative])
        @else
            @include('layout.PICOelementlayout',['TOS'=>$TOS,'PICOiterative'=>$PICOiterative])
        @endif
    @endfor
</div>
