import {POSTrequest} from "./loadingrequest.js";
import {
    setPreviousImproveQuery,
    setImproveSearchWords,
    setOldDescriptors,
    getQuerySplit,
    setnewQuery,
    getModalPICOnum,
    getImproveSearchTextArea,
} from "./datadictionary.js";
import {ChangeSeekerHandler} from "./changeseeker.js";

////PUBLIC FUNCTIONS

export function ProcessResults() {
    let PICOnum = getModalPICOnum();
    console.log('new query PICO=' + PICOnum);
    eventQueryBuild(PICOnum);
}

////PRIVATE FUNCTIONS

function getSelectedDescriptors() {
    let SelectedDescriptors = {};
    $('#modal1').find('.form-tab-cont').each(function () {
        let keyword = $(this).attr('data-name');
        if (!(keyword in SelectedDescriptors)) {
            SelectedDescriptors[keyword] = {};
        }
        $($(this)).find('.DescriptorCheckbox').each(function () {
            let term = $(this).attr('name');
            if (!(term in SelectedDescriptors[keyword])) {
                SelectedDescriptors[keyword][term] = [];
            }
            if ($(this).is(':checked')) {
                let preid = $(this).attr('id').replace('descriptorsform', 'decsform');
                $('#' + preid + '-cont').find('.DescriptorCheckbox:checked').each(function () {
                    SelectedDescriptors[keyword][term].push($(this).attr('name'));
                });
            }

        });
    });
    return SelectedDescriptors;
}

function eventQueryBuild(PICOnum) {
    let url = "PICO/QueryBuild";
    let data = {
        QuerySplit: getQuerySplit(),
        SelectedDescriptors: getSelectedDescriptors(),
        ImproveSearchQuery: getImproveSearchTextArea(),
    };
    POSTrequest(url, data, function (Data) {
        BuildImprovedQuery(Data, PICOnum);
    });
}

function BuildImprovedQuery(data, PICOnum) {
    setnewQuery(PICOnum, data.newQuery);
    setImproveSearchWords(PICOnum, data.ImproveSearchWords);
    setOldDescriptors(PICOnum, data.OldSelectedDescriptors);
    setPreviousImproveQuery(PICOnum, data.ImproveSearchQuery);
    ChangeSeekerHandler(PICOnum)
}
