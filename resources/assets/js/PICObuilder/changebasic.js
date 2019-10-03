import {setResNumAltText, getFieldListOptionNum} from "./commons.js";
import {hideBootstrapObj, showBootstrapObj} from "./hideshow";
import {translate} from "./translator";

////PUBLIC FUNCTIONS

///STATE OF BUTTONS


export function ShowExplodeButton(PICOnum) {
    let explodebutton = $('#Exp' + PICOnum);
    if ($('#datainput' + PICOnum).val().length > 0) {
        showBootstrapObj(explodebutton);
        if (objResNumHasoldval(PICOnum)) {
            CalcResAsMustUpdate(PICOnum);
        }
    } else {
        CalcResAsReady(objCalcRes);
        hideBootstrapObj(explodebutton);
    }
}

/// SEEK COMPARISONS

export function getGlobalCompValue(PICOnum) {
    let txt = '';
    for (let loop_i = PICOnum; loop_i >= 1; loop_i--) {
        let localval =  getResNumOldVal(PICOnum,false);
        txt = txt + localval;
    }
    return txt;
}

export function getLocalCompValue(PICOnum) {
    let FieldData = getFieldListOptionNum(PICOnum);
    let queryVal = $('#datainput' + PICOnum).val();
    return queryVal + FieldData;
}

export function getResNumOldVal(PICOnum,isGlobal) {
    let objResNum = null;
    if(isGlobal){
        objResNum = $('#ResNumGlobal' + PICOnum);
    }else{
        objResNum = $('#ResNumLocal' + PICOnum);
    }
    return $(objResNum).attr('data-oldval');
}


export function MustRecalculate(objResNum, objSpan) {
    removeHREF(objResNum);
    hideDataButton(objResNum);
    AddReDoButton(objSpan);
    CalcResAsMustUpdate(objCalcRes)
}

export function ReturnToOldState(objResNum, objSpan) {
    recoverHREF(objResNum);
    RemoveReDoButton(objSpan);
    showDataButton(objResNum);
    CalcResAsReady(objCalcRes)
}

////PRIVATE FUNCTIONS

function hideDataButton(objResNum) {
    hideBootstrapObj(objResNum);
}

function showDataButton(objResNum) {
    showBootstrapObj(objResNum);
}

function objResNumHasoldval(objResNum) {
    return !!$(objResNum).attr('data-oldval')
}

function removeHREF(objResNum) {
    objResNum.attr('data-oldhref', (objResNum.attr('href')));
    objResNum.removeAttr('href');
}

function recoverHREF(objResNum) {
    objResNum.attr('data-href', (objResNum.attr('data-oldhref')));
}

/// RE-DO BUTTON

function AddReDoButton(objSpan) {
    if (!(hasReDoButton(objSpan))) {
        $(objSpan).addClass('fas fa-redo');
        $(objSpan).text(' ');
        setResNumAltText(objSpan, true);
    }
}

function hasReDoButton(objSpan) {
    return (objSpan).hasClass('fa-redo');
}

function RemoveReDoButton(objSpan) {
    if ((hasReDoButton(objSpan))) {
        $(objSpan).removeClass('fas fa-redo');
        setResNumAltText(objSpan, false);
    }
}

//////////////////////////////////
///CALC RES AKA EXPAND BUTTON
/////////////////////////////////

function getCalcResColorClass(choice) {
    if (cgoice === -1) {
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

