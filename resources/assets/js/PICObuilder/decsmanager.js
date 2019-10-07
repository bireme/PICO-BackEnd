import {POSTrequest} from "./loadingrequest.js";
import {getPreviousImproveQuery,getPICOnumFromObjId,getLanguages,getPreviousResults,getobjDataInputVal,getImproveSearchWords,getOldDescriptors,setPreviousResults,setModalContent} from "./datadictionary.js";
import {hideBootstrapObj, showBootstrapObj} from "./hideshow";
////PUBLIC FUNCTIONS

export function OnExpandDeCS(ExpandButton) {
    let PICOnum = getPICOnumFromObjId(ExpandButton);
    console.log('DeCS EXpand PICO='+PICOnum);
    eventDeCSManager(PICOnum);
}

////PRIVATE FUNCTIONS
function eventDeCSManager(PICOnum) {
    clearDeCSMenu();
    let url = "PICO/DeCSExplore";
    let data = {
        query: getobjDataInputVal(PICOnum),
        OldSelectedDescriptors: getOldDescriptors(PICOnum),
        ImproveSearchWords: getImproveSearchWords(PICOnum),
        langs: getLanguages(),
        PICOnum: parseInt(PICOnum),
        SavedData: getPreviousResults(PICOnum),
        PreviousImproveQuery:getPreviousImproveQuery(PICOnum),
    };
    POSTrequest(url, data, function (Data) {
        createDeCSMenu(Data, PICOnum);
    });
}

function createDeCSMenu(data, PICOnum) {
    setPreviousResults(data.SavedData, PICOnum);
    setModalContent(1,data.DescriptorsHTML);
    setModalContent(2,data.DeCSHTML);
    setModalContent(3,data.PreviousImproveQuery);
    let modalone = $('#modal1');
    if($(modalone).find('.DontShowButton').length){
        hideBootstrapObj($(modalone).find('.btn-continue'))
    }else{
        showBootstrapObj($(modalone).find('.btn-continue'))
    }
    showDeCSMenu();
}

function showDeCSMenu() {
    $('#modal1').modal({
        show: true,
        keyboard: false,
        backdrop: 'static'
    });
}

function clearDeCSMenu() {
    let NoData = 'No Data';
    setModalContent(1,NoData);
    setModalContent(2,NoData);
    setModalContent(3,NoData);
}
