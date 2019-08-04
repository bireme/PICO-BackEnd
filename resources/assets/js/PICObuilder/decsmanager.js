import {getPreviousResults,getLanguages} from "./commonsdecs.js";
import {POSTrequest} from "./loadingrequest.js";

////PUBLIC FUNCTIONS

export function OnExpandDeCS(ExpandButton) {
    let langs = getLanguages();
    let PICOval = '#datainput' + ($(ExpandButton).attr('id')).substr(-1);
    let query = $(PICOval).val();
    let PICOnum = ($(ExpandButton).attr('id')).substr(-1);
    eventDeCSSearch(query, langs, PICOnum);
}

////PRIVATE FUNCTIONS

function createDeCSMenu(data,PICOnum) {
    let SavedData = data.SavedData;
    let DescriptorsHTML = data.DescriptorsHTML;
    let DeCSHTML = data.DeCSHTML;
    let QuerySplit=data.QuerySplit;
    setTmpQuerySplit(QuerySplit,PICOnum);
    setPreviousResults(SavedData,PICOnum);
    $('#modal1').find('.modal-body').first().html(DescriptorsHTML);
    $('#modal2').find('.modal-body').first().html(DeCSHTML);
}

function setTmpQuerySplit(QuerySplit,PICOnum) {
    $('#datainput'+PICOnum).attr('data-query-split',QuerySplit);
}

function setPreviousResults(results,PICOnum) {
    $('#datainput'+PICOnum).attr('data-previous-decs',results);
}

function showDeCSMenu() {
    console.log('showing modal');
    $('#modal1').modal({
        show: true,
        keyboard: false,
        backdrop: 'static'
    });
}

function eventDeCSSearch(query, langs, PICOnum) {
    let url = "PICO/DeCSExplore";
    let SavedData =getPreviousResults(PICOnum);
    let data = {
        SavedData:SavedData,
        query: query,
        langs: langs,
        PICOnum: parseInt(PICOnum),
    };
    POSTrequest(url, data, function (Data) {
        createDeCSMenu(Data,PICOnum);
        showDeCSMenu();
    });
}
