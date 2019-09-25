import {setCalcResAsSyncAlt} from "./changeseeker.js";
import {getGlobalSpanJSON,getFieldListOptionNum,RemoveReDoButton} from "./commonschange.js";
import {getPICOElements} from "./commons.js";
import {translate} from "./translator.js";
import {POSTrequest} from "./loadingrequest.js";
import {showInfoMessage} from "./infomessage.js";
import {showBootstrapObj,hideBootstrapObj} from "./hideshow.js";

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
    console.log('initialdata in resultsnumber');
    console.log(data);

    let spanObj;
    hideBootstrapObj($('#CalcRes' + PICOnum));
    setCalcResAsSyncAlt(PICOnum);
    let ResNumLocalObj = $('#ResNumLocal' + PICOnum);
    let ResNumGlobalObj = $('#ResNumGlobal' + PICOnum);

    let localresultsnumber = data.local.ResultsNumber;
    let localresultsurl = data.local.ResultsURL;
    let globalresultsnumber = data.global.ResultsNumber;
    let globalresultsurl = data.global.ResultsURL;

    console.log('localresultsnumber');
    console.log(localresultsnumber);
    console.log('localresultsurl');
    console.log(localresultsurl);
    console.log('globalresultsnumber');
    console.log(globalresultsnumber);
    console.log('globalresultsurl');
    console.log(globalresultsurl);
    if (PICOnum < 5) {
        spanObj = ResNumLocalObj.find('span').first();
        showBootstrapObj(spanObj.parent());
        RemoveReDoButton(spanObj);
        if (localresultsnumber === 0) {
            addIconZeroResults(spanObj);
        } else {
            $(spanObj).text(localresultsnumber);
        }
        $(spanObj).attr('data-oldval', localresultsnumber);
        ResNumLocalObj.attr("href", localresultsurl);
    }
    if (PICOnum > 1 && PICOnum !== 5) {
        spanObj = ResNumGlobalObj.find('span').first();
        showBootstrapObj(spanObj.parent());
        RemoveReDoButton(spanObj);
        if (globalresultsnumber === 0) {
            addIconZeroResults(spanObj);
        } else {
            $(spanObj).text(globalresultsnumber);
            if (PICOnum === 6) {
                showBootstrapObj(spanObj);
            }
        }
        $(spanObj).attr('data-oldval', globalresultsnumber);
        ResNumGlobalObj.attr("href", globalresultsurl);
    }
    if (PICOnum === 6) {
        let FinalSearchDetailsObj = $('#FinalSearchDetails');
        FinalSearchDetailsObj.val(data.global.query);
        FinalSearchDetailsObj.attr('data-oldval', data.global.query);
    }

    let oldvalJSON = getGlobalSpanJSON(PICOnum);
    $('#datainput' + PICOnum).attr('data-oldval', oldvalJSON);
    if (PICOnum === 6) {
        PICOnum = 5;
    }

    if (PICOnum < 5) {
        FieldListSetoldval(PICOnum);
    }
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

function addIconZeroResults(obj) {
    let iconHTML = '0 <a class="PICOiconzeroElement"><span>?</span></a>';
    $(obj).html(iconHTML);
}

function FieldListSetoldval(PICOnum) {
    let objFieldList = $('#FieldList' + PICOnum);
    objFieldList.attr('data-oldval', getFieldListOptionNum(PICOnum));
}
