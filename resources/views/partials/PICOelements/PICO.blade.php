<div class="accordion" id="accordionPicos">

    <?php
    use \PICOExplorer\Http\Controllers\RepositoriesControllers\SearchFieldsRepositoryController;
    use \PICOExplorer\Http\Controllers\RepositoriesControllers\StudiesRepositoryController;

    $TOS = (new StudiesRepositoryController())->TypeOfStudies() ?? array();
    $FieldNames = (new SearchFieldsRepositoryController())->SearchFields() ?? array();
    $globaloldpicodata = $PreviousData['PICOData'] ?? array();
    $oldTOS = $PreviousData['TOS'] ?? array();
    $TmpCookieElement = $PreviousData['TmpCookieElement'] ?? '';
    ?>

    @for($PICOiterative=1;$PICOiterative<6;$PICOiterative++)

        @php
            $olddata = $globaloldpicodata[$PICOiterative] ?? null;
        @endphp
        @if($PICOiterative<5)
            @include('layout.PICOelementlayout',['PICOiterative' => $PICOiterative,'FieldsNames'=>$FieldNames,'olddata'=>$olddata])
        @else
            @include('layout.PICOelementlayout',['PICOiterative' => $PICOiterative,'TOS'=>$TOS,'olddata'=>$olddata,'oldTOS' => $oldTOS, 'TmpCookieElement' => $TmpCookieElement])
        @endif
    @endfor

</div>
