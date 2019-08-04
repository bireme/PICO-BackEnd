import {getPreviousResults} from "./commonsdecs.js";
import {POSTrequest} from "./loadingrequest.js";

////PUBLIC FUNCTIONS

export function ProcessResults() {
    let PICOnum = $('#modal1').find('#PICONumTag').val();
    let ImproveSearchQuery = $('#modal3').find('textarea').val();
    eventQueryBuild(PICOnum, ImproveSearchQuery);
}

////PRIVATE FUNCTIONS

function BuildImprovedQuery(PICOnum, data) {
    let newQuery = data.newQuery;
    $('#datainput' + PICOnum).val(newQuery);
}

function getSelectedDescriptors() {
    let SelectedDescriptors = {};
    $('#modal2').find('input.DeCSCheckBoxElement:checked').each(function () {
        let DeCS = $(this).attr('name');
        let keyword = $(this).attr('data-keyword');
        let term = $(this).attr('data-term');

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

function eventQueryBuild(PICOnum, ImproveSearchQuery) {
    let url = "PICO/QueryBuild";
    let data = {
        PICOnum:  parseInt(PICOnum),
        QuerySplit: getTmpQuerySplit(PICOnum),
        DeCSResults: getPreviousResults(PICOnum),
        SelectedDescriptors: getSelectedDescriptors(),
        ImproveSearchQuery: ImproveSearchQuery
    };
    POSTrequest(url, data, function (Data) {
        BuildImprovedQuery(PICOnum, Data);
    });
}

function getTmpQuerySplit(PICOnum) {
    return $('#datainput'+PICOnum).attr('data-query-split');
}
