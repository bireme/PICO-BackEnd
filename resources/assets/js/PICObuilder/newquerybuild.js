import {POSTrequest} from "./loadingrequest.js";

////PUBLIC FUNCTIONS

export function ProcessResults() {
    let ImproveSearchQuery = $('#modal3').find('textarea').val();
    let PICOnum = $('#modalkw').find('.keywordform-piconum').first().val();
    eventQueryBuild(ImproveSearchQuery,PICOnum);
}

////PRIVATE FUNCTIONS

function BuildImprovedQuery(data) {
    let newQuery = data.newQuery;
    let PICOnum = $('#modalkw').find('.keywordform-piconum').first().val();
    setOldSelectedDescriptors(data.OldSelectedDescriptors,PICOnum);
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

function eventQueryBuild(ImproveSearchQuery,PICOnum) {
    let url = "PICO/QueryBuild";
    let data = {
        SelectedDescriptors: getSelectedDescriptors(),
        ImproveSearchQuery: ImproveSearchQuery,
        OldSelectedDescriptors:getOldSelectedDescriptors(PICOnum),
    };
    POSTrequest(url, data, function (Data) {
        BuildImprovedQuery(Data);
    });
}

function getQuerySplit() {
    return $('#modalkw').find('.keywordform-querysplit').first().val();
}

function setOldSelectedDescriptors(OldSelectedDescriptors,PICOnum) {
    $('#datainput'+PICOnum).attr('data-old-selected-descriptors',OldSelectedDescriptors);
}

function getOldSelectedDescriptors(PICOnum) {
    return $('#datainput'+PICOnum).attr('data-old-selected-descriptors');
}

