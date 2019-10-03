import {POSTrequest} from "./loadingrequest.js";

////PUBLIC FUNCTIONS

export function ProcessResults() {
    let ImproveSearchQuery = $('#modal3').find('textarea').val();
    let PICOnum = $('#modal1').find('.descriptorsform-piconum').first().val();
    setImproveSearch(ImproveSearchQuery, PICOnum);
    eventQueryBuild(ImproveSearchQuer);
}

function setImproveSearch(improve, PICOnum) {
    $('#datainput' + PICOnum).attr('data-improve', improve);
}

////PRIVATE FUNCTIONS

function BuildImprovedQuery(data) {
    let newQuery = data.newQuery;
    let PICOnum = $('#modal1').find('.descriptorsform-piconum').first().val();
    $('#datainput' + PICOnum).val(newQuery);
}

function getSelectedDescriptors() {
    let SelectedDescriptors = {};
    $('#modal1').find('.form-tab-cont').each(function () {
        let keyword = $(this).attr('data-name');
        if (!(keyword in SelectedDescriptors)) {
            SelectedDescriptors[keyword] = {};
        }
        $($(this)).find('.DescriptorCheckbox').each(function () {
            if ($(this).attr('checked')) {
                let term = $(this).attr('name');
                if (!(term in SelectedDescriptors[keyword])) {
                    SelectedDescriptors[keyword][term] = [];
                }
                let num = $(this).attr('id').slice(15);
                $('#decsform' + num + '-cont').find('.DescriptorCheckbox').each(function () {
                    if ($(this).attr('checked')) {
                        SelectedDescriptors[keyword][term].push($(this).attr('name'));
                    }
                });
            }
        });
    });
    return SelectedDescriptors;
}

function eventQueryBuild(ImproveSearchQuery) {
    let url = "PICO/QueryBuild";
    let data = {
        SelectedDescriptors: getSelectedDescriptors(),
        ImproveSearchQuery: ImproveSearchQuery,
        QuerySplit: getQuerySplit(),
    };
    POSTrequest(url, data, function (Data) {
        BuildImprovedQuery(Data);
    });
}

function getQuerySplit() {
    return $('#modal1').find('.descriptorsform-querysplit').first().val();
}
