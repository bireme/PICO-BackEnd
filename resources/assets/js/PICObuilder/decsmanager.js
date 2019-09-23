import {getPreviousResults, getLanguages} from "./commonsdecs.js";
import {POSTrequest} from "./loadingrequest.js";

////PUBLIC FUNCTIONS

export function OnExpandDeCS(ExpandButton) {
    let langs = getLanguages();
    let PICOval = '#datainput' + ($(ExpandButton).attr('id')).substr(-1);
    let query = $(PICOval).val();
    let PICOnum = ($(ExpandButton).attr('id')).substr(-1);
    eventKeywordManager(query, langs, PICOnum);
}

export function OnExploreDeCS(ExpandButton) {
    let KeywordList = getKeywordList();
    let PICOnum = $('#modalkw').find('.keywordform-piconum').first().val();
    let SavedData = getPreviousResults(PICOnum);
    eventDeCS(SavedData, KeywordList, PICOnum);
}

////PRIVATE FUNCTIONS

function setPreviousResults(results, PICOnum) {
    $('#datainput' + PICOnum).attr('data-previous-decs', results);
}

function getKeywordList() {
    let KeywordList = [];
    $('#modalkw').find('.DescriptorCheckbox').each(function () {
        if($(this).prop('checked')){
            KeywordList.push($(this).attr('name'));
        }

    });
    console.log('KeywordList...  '+JSON.stringify(KeywordList));
    return KeywordList;
}

function eventDeCS(SavedData, KeywordList, PICOnum) {
    let url = "PICO/DeCSExplore";
    let data = {
        SavedData: SavedData,
        KeywordList: KeywordList,
        PICOnum: parseInt(PICOnum),
    };
    POSTrequest(url, data, function (Data) {
        createDeCSMenu(Data, PICOnum);
        showDeCSMenu();
    });
}


function showDeCSMenu() {
    console.log('showing modal decs');
    $('#modal1').modal({
        show: true,
        keyboard: false,
        backdrop: 'static'
    });
}

function createDeCSMenu(data, PICOnum) {
    let SavedData = data.SavedData;
    let DescriptorsHTML = data.DescriptorsHTML;
    let DeCSHTML = data.DeCSHTML;
    setPreviousResults(SavedData, PICOnum);
    $('#modal1').find('.modal-body').first().html(DescriptorsHTML);
    $('#modal2').find('.modal-body').first().html(DeCSHTML);
}

function eventKeywordManager(query, langs, PICOnum) {
    let url = "PICO/KeywordManager";
    let SavedData = getPreviousResults(PICOnum);
    let data = {
        SavedData: SavedData,
        query: query,
        langs: langs,
        PICOnum: parseInt(PICOnum),
    };
    POSTrequest(url, data, function (Data, PICOnum) {
        createKeywordManagerMenu(Data, PICOnum);
        showKeywordManagerMenu();
    });
}

function showKeywordManagerMenu() {
    console.log('showing modal keywordmanager');
    $('#modalkw').modal({
        show: true,
        keyboard: false,
        backdrop: 'static'
    });
}

function createKeywordManagerMenu(data, PICOnum) {
    let HTML = data.HTML;
    $('#modalkw').find('.modal-body').first().html(HTML);
}

