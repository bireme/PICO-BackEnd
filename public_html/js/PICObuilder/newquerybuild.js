function ProcessResults() {
    var PICOnum = $('#modal').find('#PICONumTag').val();
    var query = $('#datainput' + PICOnum).val();
    var SelectedDescriptors = getSelectedDescriptors();
    var ImproveSearch = $('#modal3').find('textarea').val();
    eventQueryBuild(PICOnum, query, SelectedDescriptors, ImproveSearch);
}

function eventQueryBuild(PICOnum, query, SelectedDescriptors, ImproveSearch) {
    var url = "ControllerEventQueryBuild.php";
    var data = {
        PICOnum: PICOnum,
        query: query,
        SelectedDescriptors: SelectedDescriptors,
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