function getSelectedDescriptors() {
    var SelectedDescriptors = {};
    $('#modal2').find('input.DeCSCheckBoxElement:checked').each(function () {
        var DeCS = $(this).attr('name');
        var keyword = $(this).attr('data-keyword');
        var term = $(this).attr('data-term');

        if (!(keyword in SelectedDescriptors)) {
            SelectedDescriptors[keyword] = {};
        }
        if (!(term in SelectedDescriptors[keyword])) {
            SelectedDescriptors[keyword][term] = [];
        }
        SelectedDescriptors[keyword][term].push(DeCS);
    });
    return SelectedDescriptors;
}

function ProcessResults() {
    var PICOnum = $('#modal').find('#PICONumTag').val();
    var ImproveSearchQuery = $('#modal3').find('textarea').val();
    eventQueryBuild(PICOnum, ImproveSearchQuery);
}

function eventQueryBuild(PICOnum, ImproveSearchQuery) {
    var url = "PICOExplorerQueryBuild.php";
    var data = {
        PICOnum: PICOnum,
        QuerySplit: getTmpQuerySplit(PICOnum),
        results: getPreviousResults(PICOnum),
        SelectedDescriptors: getSelectedDescriptors(),
        ImproveSearchQuery: ImproveSearchQuery
    };
    POSTrequest(url, data, function (Data) {
        BuildImprovedQuery(PICOnum, Data);
    });
}

function BuildImprovedQuery(PICOnum, data) {
    var newQuery = data.newQuery;
    $('#datainput' + PICOnum).val(newQuery);
}