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
    let results = data.results;
    let HTMLDescriptors = data.HTMLDescriptors;
    let HTMLDeCS = data.HTMLDeCS;
    let QuerySplit=data.QuerySplit;
    console.log('querysplit');
    console.log(QuerySplit);
    setTmpQuerySplit(QuerySplit,PICOnum);
    setPreviousResults(results);
    $('#modal').find('.modal-body').first().html(HTMLDescriptors);
    $('#modal2').find('.modal-body').first().html(HTMLDeCS);
}

function setTmpQuerySplit(QuerySplit,PICOnum) {
    $('#datainput'+PICOnum).attr('data-query-split',QuerySplit);
}

function setPreviousResults(results) {
    $('#TmpCookieElement').attr('data-previous-decs',results);
}

function showDeCSMenu() {
    $('#modal').modal({
        show: true,
        keyboard: false,
        backdrop: 'static'
    });
}

function eventDeCSSearch(query, langs, PICOnum) {
    let url = "PICO/DeCSExplore";
    console.log('PreviousData');
    console.log(getPreviousResults());
    let data = {
        PreviousData:getPreviousResults(),
        query: query,
        langs: langs,
        PICOnum: PICOnum
    };
    POSTrequest(url, data, function (Data) {
        createDeCSMenu(Data,PICOnum);
        showDeCSMenu();
    });
}

