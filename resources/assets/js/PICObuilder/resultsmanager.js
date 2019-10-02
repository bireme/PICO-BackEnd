import {getFieldListOptionNum} from "./commons.js";
import {translate} from "./translator.js";
import {POSTrequest} from "./loadingrequest.js";
import {showInfoMessage} from "./infomessage.js";

////PUBLIC FUNCTIONS

export function getResultsNumber(id) {
    if (id < 5) {
        let cont = $('#datainput' + id).val();
        if (cont.length === 0) {
            showInfoMessage('Info', translate('allemptyq'), false);
            return;
        }
    } else {
        let newid = 0;
        for (let loop_i = 5; loop_i >= 1; loop_i--) {
            if ($('#datainput' + loop_i).val().length > 0) {
                newid = loop_i;
                break;
            }
        }
        if (newid === 0) {
            showInfoMessage('Info', translate('numresupd'), false);
            return;
        }
    }
    let queryobject = getAllInputFields();
    eventResultsNumber(id, queryobject);
}

////PRIVATE FUNCTIONS

function getAllInputFields() {
    let results = {};
    let loop_i;
    for (loop_i = 1; loop_i < 6; loop_i++) {
        let valx = $('#datainput' + loop_i).val();
        let fieldx = getFieldListOptionNum(loop_i);
        results['PICO' + loop_i] = {
            query: valx,
            field: fieldx
        };
    }
    return results;
}

function setResultsNumber(data, PICOnum) {
    let localresultsnumber = data.local.ResultsNumber;
    let localresultsurl = data.local.ResultsURL;
    let globalresultsnumber = data.global.ResultsNumber;
    let globalresultsurl = data.global.ResultsURL;
    let ResNumLocalObj = $('#ResNumLocal' + PICOnum);
    let ResNumGlobalObj = $('#ResNumGlobal' + PICOnum);
    let spanObj;
    if (PICOnum < 5) {
        spanObj = ResNumLocalObj.find('span').first();
        if (localresultsnumber === 0) {
            addIconZeroResults(spanObj);
        } else {
            $(spanObj).text(localresultsnumber);
        }
        ResNumLocalObj.attr("href", localresultsurl);
    }
    if (PICOnum > 1 && PICOnum !== 5) {
        spanObj = ResNumGlobalObj.find('span').first();
        if (globalresultsnumber === 0) {
            addIconZeroResults(spanObj);
        } else {
            $(spanObj).text(globalresultsnumber);
        }
        ResNumGlobalObj.attr("href", globalresultsurl);
    }
    if (PICOnum === 6) {
        $('#FinalSearchDetails').val(data.global.query);
    }
}


function addIconZeroResults(obj) {
    let iconHTML = '0 <a class="PICOiconzeroElement"><span>?</span></a>';
    $(obj).html(iconHTML);
}

function eventResultsNumber(PICOnum, queryobject) {
    let url = "PICO/ResultsNumber";
    let data = {
        PICOnum: PICOnum,
        queryobject: queryobject
    };
    POSTrequest(url, data, function (Data) {
        setResultsNumber(Data, PICOnum);
    });
}

