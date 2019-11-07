import {setResNumAltText} from "./commons.js";
import {hideBootstrapObj, isHiddenBootstrapObj, showBootstrapObj} from "./hideshow";
import {translate} from "./translator";
import {getFieldListOptionNum} from "./datadictionary";

////PUBLIC FUNCTIONS

export function isHiddenResNum(PICOnum, isGlobal) {
    return isHiddenBootstrapObj(getobjResNum(PICOnum, isGlobal));
}

export function setGlobalTitle(PICOnum, globaltitle) {
    if (PICOnum === 5) {
        return;
    }
    let globalresnum = getobjResNum(PICOnum, true)
    $(globalresnum).find('label').first().text(globaltitle);
}

export function getobjResNum(PICOnum, isGlobal) {
    let objResNum = null;
    if (isGlobal) {
        objResNum = $('#ResNumGlobal' + PICOnum).first();
    } else {
        objResNum = $('#ResNumLocal' + PICOnum).first();
    }
    return objResNum;
}

export function MustRecalculate(PICOnum, isGlobal) {
    let obj = getObjects(PICOnum, isGlobal);
    if (isHiddenBootstrapObj(obj.Span)) {
        return;
    }
    if (hasReDoButton(obj.Span)) {
        return
    }
    hideDataButton(obj.ResNum);
    ChangeLogger(PICOnum, isGlobal, 0);
    removeHREF(obj.ResNum);
    AddReDoButton(obj.Span);
    CalcResAsMustUpdate(obj.CalcRes)
}

export function MustRecalculateFinal() {
    $('#FinalSearchDetails').text(translate('pleaseupd'));
    ChangeLogger(5, true, 0);
    removeHREF($('#FinalGlobal'));
    showBootstrapObj($('#finalmustupdate'));
    hideBootstrapObj($('#finalupdated'));
}

export function JustUpdatedFinal(resultsNumber, resultsURL) {
    hideBootstrapObj($('#finalmustupdate'));
    showBootstrapObj($('#finalupdated'));
    $('#FinalGlobal').attr('data-href',resultsURL);
    resNumSetNumber($('#finalupdated'), resultsNumber);
    ChangeLogger(5, true, -1);
    saveToComparisonGlobal(5);

}

export function JustUpdated(PICOnum, isGlobal, initial, resultsNumber, resultsURL) {
    let obj = getObjects(PICOnum, isGlobal);
    if (initial === true) {
        hideDataButton(obj.ResNum);
    } else {
        resNumSetHREF(obj.ResNum, resultsURL);
        resNumSetNumber(obj.Span, resultsNumber);
        showDataButton(obj.ResNum);
        if(isGlobal){
            saveToComparisonGlobal(PICOnum);
        }else {
            saveToComparisonLocal(PICOnum);
        }
    }
    ChangeLogger(PICOnum, isGlobal, -1);
    RemoveReDoButton(obj.Span);
    CalcResAsReady(obj.CalcRes)
}

function addIconZeroResults(spanObj) {
    let iconHTML = '0 <a class="PICOiconzeroElement"><span>?</span></a>';
    $(spanObj).html(iconHTML);
}

export function ReturnToOldStateFinal(PICOnum, isGlobal) {
    let obj = getObjects(PICOnum, isGlobal);
    ChangeLogger(5, true, 1);
    recoverHREF(obj.ResNum);
    hideBootstrapObj($('#finalmustupdate'));
    showBootstrapObj($('#finalupdated'));
}


export function ReturnToOldState(PICOnum, isGlobal) {
    let obj = getObjects(PICOnum, isGlobal);
    if (!(hasReDoButton(obj.Span))) {
        return
    }
    showDataButton(obj.ResNum);
    ChangeLogger(PICOnum, isGlobal, 1);
    recoverHREF(obj.ResNum);
    RemoveReDoButton(obj.Span);
    CalcResAsReady(obj.CalcRes)
}

function ChangeLogger(PICOnum, isGlobal, isReturnToOldState) {
    let txt = '';
    if (isReturnToOldState === 1) {
        txt = txt + 'ToOldState: ';
    } else {
        if (isReturnToOldState === 0) {
            txt = txt + 'ToBeUpdated: ';
        } else {
            txt = txt + 'JustUpdated: ';
        }
    }
    if (isGlobal) {
        txt = txt + 'Global-';
    } else {
        txt = txt + 'Local-';
    }
    txt = txt + PICOnum;
    console.log('Changeseeker: ' + txt);
}


/// SEEK COMPARISONS

export function getComparisonCurrentLocal(PICOnum) {
    let FieldData = getFieldListOptionNum(PICOnum);
    let queryVal = getQueryVal(PICOnum);
    return queryVal + FieldData;
}

export function getComparisonLocalPreviouslySaved(PICOnum) {
    return $(getobjResNum(PICOnum, false)).attr('data-comparison');
}

export function getQueryVal(PICOnum) {
    return $('#datainput' + PICOnum).val();
}

export function getComparisonGlobalPreviouslySaved(PICOnum) {
    return $(getobjResNum(PICOnum, true)).attr('data-comparison');
}

export function resNumSetHREF(objResNum, value) {
    objResNum.attr('data-href', value);
}

export function resNumSetNumber(objSpan, value) {
    if (value === 0) {
        addIconZeroResults(objSpan);
    } else {
        objSpan.text(value);
    }
}

////PRIVATE FUNCTIONS

function saveToComparisonLocal(PICOnum) {
    let value = getComparisonCurrentLocal(PICOnum);
    $(getobjResNum(PICOnum, false)).attr('data-comparison', value);
}

export function getComparisonCurrentGlobal(PICOnum) {
    let txt = '';
    for (let loop_i = 1; loop_i <= 6; loop_i++) {
        txt = txt + getComparisonCurrentLocal(PICOnum);
    }
    return txt;
}


function saveToComparisonGlobal(PICOnum) {
    let txt = getComparisonCurrentGlobal(4);
    txt=txt+$('#datainput5').val();
    $(getobjResNum(PICOnum, true)).attr('data-comparison', txt);
}

function hideDataButton(objResNum) {
    hideBootstrapObj(objResNum);
}

function showDataButton(objResNum) {
    showBootstrapObj(objResNum);
}

function removeHREF(objResNum) {
    objResNum.attr('data-oldhref', (objResNum.attr('href')));
    objResNum.removeAttr('href');
}

function recoverHREF(objResNum) {
    objResNum.attr('href', (objResNum.attr('data-oldhref')));
}


/// RE-DO BUTTON

function AddReDoButton(objSpan) {
    $(objSpan).addClass('fas fa-redo');
    $(objSpan).text(' ');
    setResNumAltText(objSpan, true);
}

function hasReDoButton(objSpan) {
    return (objSpan).hasClass('fa-redo');
}

function RemoveReDoButton(objSpan) {
    $(objSpan).removeClass('fas fa-redo');
    setResNumAltText(objSpan, false);
}

//////////////////////////////////
///CALC RES AKA EXPAND BUTTON
/////////////////////////////////

function getCalcResColorClass(choice) {
    if (choice === -1) {
        return 'btn-info';
    }
    if (choice === 1) {
        return 'btn-outline-info';
    }
    return 'btn-outline-warning';
}

function CalcResAsReady(objCalcRes) {
    $(objCalcRes).html(translate('butres'));
    $(objCalcRes).addClass(getCalcResColorClass(-1));
    $(objCalcRes).removeClass(getCalcResColorClass(objCalcRes));
}

function CalcResAsMustUpdate(objCalcRes) {
    $(objCalcRes).html('<i class="fas fa-sync-alt"></i>');
    $(objCalcRes).addClass(getCalcResColorClass(objCalcRes));
    $(objCalcRes).removeClass(getCalcResColorClass(-1));
}

function getObjects(PICOnum, isGlobal) {
    let data = {};
    data.ResNum = getobjResNum(PICOnum, isGlobal);
    data.Span = getobjSpan(PICOnum, isGlobal);
    data.CalcRes = getobjCalcRes(PICOnum);
    return data;
}


function getobjCalcRes(PICOnum) {
    return $('#CalcRes' + PICOnum).first();
}


function getobjSpan(PICOnum, isGlobal) {
    let objResNum = getobjResNum(PICOnum, isGlobal).first();
    return objResNum.find('span').first();
}

