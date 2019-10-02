import {getPreviousResults, getLanguages} from "./commonsdecs.js";
import {POSTrequest} from "./loadingrequest.js";

////PUBLIC FUNCTIONS

export function OnExpandDeCS(ExpandButton) {
    let langs = getLanguages();
    let PICOnum = ($(ExpandButton).attr('id')).substr(-1);
    clearDeCSMenu();
    let PICOval = '#datainput' + PICOnum;
    let query = $(PICOval).val();
    let ImprovedSearch = getImproveSearch(PICOnum);
    eventdDeCSManager(query, langs, PICOnum,ImprovedSearch);
}

////PRIVATE FUNCTIONS

function setPreviousResults(results, PICOnum) {
    $('#datainput' + PICOnum).attr('data-previous-decs', results);
}

function showDeCSMenu() {
    console.log('showing modal decs');
    $('#modal1').modal({
        show: true,
        keyboard: false,
        backdrop: 'static'
    });
}

function getImproveSearch(PICOnum) {
    return $('#datainput' + PICOnum).attr('data-improve');
}


function clearDeCSMenu() {
    let NoData = 'No Data';
    $('#modal3').find('textarea').val(NoData);
    $('#modal1').find('.modal-body').first().html(NoData);
    $('#modal2').find('.modal-body').first().html(NoData);
}

function createDeCSMenu(data, PICOnum,Improved) {
    let SavedData = data.SavedData;
    let DescriptorsHTML = data.DescriptorsHTML;
    let DeCSHTML = data.DeCSHTML;
    $('#modal3').find('textarea').val(Improved);
    setPreviousResults(SavedData, PICOnum);
    $('#modal1').find('.modal-body').first().html(DescriptorsHTML);
    $('#modal2').find('.modal-body').first().html(DeCSHTML);
}

function eventdDeCSManager(query, langs, PICOnum,ImprovedSearch) {
    let url = "PICO/DeCSExplore";
    let SavedData = getPreviousResults(PICOnum);
    let data = {
        SavedData: SavedData,
        query: query,
        ImprovedSearch: ImprovedSearch,
        langs: langs,
        PICOnum: parseInt(PICOnum),
    };
    POSTrequest(url, data, function (Data) {
        console.log('Obtained SavedData... '+JSON.stringify(Data.SavedData));
        createDeCSMenu(Data, PICOnum,ImprovedSearch);
        showDeCSMenu();
    });
}
