function getSelectedDescriptors() {
    var DeCSModalTitlePrefix = 'opcao';
    var DeCSModalTitlePostfix = '-tab';
    var num = 0;
    var SelectedDescriptors = [];
    $('#modal2').find('.DeCSCheckBoxElement').each(function () {
        if (!($(this).find('input').first().is(':checked'))) {
            return;
        }
        SelectedDescriptors.push($(this).find('input').first().attr('name'));
    });
    return SelectedDescriptors;
}


function ProcessResults() {
    var PICOnum = $('#modal').find('#PICONumTag').val();
    var ImproveSearch = $('#modal3').find('textarea').val();
    eventQueryBuild(PICOnum, ImproveSearch);
}

function eventQueryBuild(PICOnum, ImproveSearch) {
    var url = "ControllerEventQueryBuild.php";
    var data = {
        PICOnum: PICOnum,
        results: getPreviousData(),
        SelectedDescriptors: getSelectedDescriptors(),
        ImproveSearch: ImproveSearch
    };
    POSTrequest(url, data, function (Data) {
        BuildImprovedQuery(PICOnum, Data);
    });
}

function BuildImprovedQuery(PICOnum, data) {
    var newQuery = data.newQuery;
    $('#datainput' + PICOnum).val(newQuery);
}