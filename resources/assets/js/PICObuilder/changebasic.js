import {setResNumAltText,getFieldListOptionNum} from "./commons.js";
import {hideBootstrapObj, showBootstrapObj} from "./hideshow";
import {translate} from "./translator";

////PUBLIC FUNCTIONS

export function ShowExplodeButton(PICOnum) {
    let explodebutton = $('#Exp' + PICOnum);
    if ($('#datainput' + PICOnum).val().length > 0) {
        showBootstrapObj(explodebutton);
        if (objResNumHasoldval(PICOnum)) {
            setCalcResAsSyncAlt(PICOnum);
        }
    } else {
        setCalcResAsResult(PICOnum);
        hideBootstrapObj(explodebutton);
    }
}

export function getGlobalCompValue(PICOnum) {
    let txt='';
    for (let loop_i = PICOnum; loop_i >= 1; loop_i--) {
        let localval = $('#ResNumLocal' + PICOnum).attr('data-oldval');
        txt = txt+ localval;
    }
    return txt;
}

export function getLocalCompValue(PICOnum) {
    let FieldData = getFieldListOptionNum(PICOnum);
    let queryVal = $('#datainput' + PICOnum).val();
    return queryVal+'Field'+FieldData;
}

export function getLocalOldVal(PICOnum) {
    $('#ResNumLocal' + PICOnum).attr('data-oldval');
}

export function getGlobalOldVal(PICOnum) {
    $('#ResNumGlobal' + PICOnum).attr('data-oldval');
}

export function MustRecalculate(objResNum) {
    objResNumSetMustUpdate(objResNum);
    RemoveReDoButton(objSpan);
    SetToReDoButton(objSpan);
    hideLocalButton
    hideGlobalButton
}

export function ReturnToOldState(PICOnum, LocalObj, LocalSpan, LocalField, ThishasReDo) {
    if (!(ThishasReDo) && PICOnum !== 5) {
        return;
    }
    console.log('Returning to oldstate pico' + PICOnum);
    objResNumSetOldResult(LocalObj);
    objSpanSetOldResult(LocalSpan);
    showLocalButton(PICOnum);
    showGlobalButton
}

export function setCalcResAsSyncAlt(PICOnum) {
    let CalcResObj = $('#CalcRes' + PICOnum);
    $(CalcResObj).html('<i class="fas fa-sync-alt"></i>');
    $(CalcResObj).addClass(getCalcResColorClass(PICOnum));
    $(CalcResObj).removeClass(getCalcResColorClass(-1));
}

////PRIVATE FUNCTIONS

function getCalcResColorClass(PICOnum) {
    if (PICOnum === -1) {
        return 'btn-info';
    }
    if (PICOnum === 1) {
        return 'btn-outline-info';
    }
    return 'btn-outline-warning';
}

function setCalcResAsResult(PICOnum) {
    let CalcResObj = $('#CalcRes' + PICOnum);
    $(CalcResObj).html(translate('butres'));
    $(CalcResObj).addClass(getCalcResColorClass(-1));
    $(CalcResObj).removeClass(getCalcResColorClass(PICOnum));
}


function hideLocalButton(PICOnum) {
    let ObjResNum = $('#ResNumLocal' + PICOnum);
    let objSpan = $(ObjResNum).find('span').first();
    if (PICOnum !== 6) {
        SetToReDoButton(objSpan);
    }
    hideBootstrapObj(ObjResNum);
}

function hideGlobalButton(PICOnum) {
    let ObjResNum = $('#ResNumGlobal' + PICOnum);
    let objSpan = $(ObjResNum).find('span').first();
    if (PICOnum !== 6) {
        SetToReDoButton(objSpan);
    }
    hideBootstrapObj($('#ResNumGlobal' + PICOnum));
}

function showLocalButton(PICOnum) {
    showBootstrapObj($('#ResNumLocal' + PICOnum));
}

function showGlobalButton(PICOnum) {
    showBootstrapObj($('#ResNumGlobal' + PICOnum));
}


function objSpanSetOldResult(objSpan) {
    objSpan.text(objSpan.attr('data-oldval'));
}

function objResNumSetOldResult(objResNum) {
    objResNum.attr('href', (objResNum.attr('data-oldval')));
}


function objResNumHasoldval(PICOnum) {
    return  !!$('#ResNumLocal' + PICOnum).attr('data-oldval')
}

function hasReDoButton(objSpan) {
    return (objSpan).hasClass('fa-redo');
}

export function SetToReDoButton(objSpan) {
    if (!(hasReDoButton(objSpan))) {
        $(objSpan).addClass('fas fa-redo');
        $(objSpan).text(' ');
        setResNumAltText(objSpan, true);
    }
}

function objResNumSetMustUpdate(objResNum) {
    objResNum.attr('data-oldval', (objResNum.attr('href')));
    objResNum.removeAttr('href');
}

function RemoveReDoButton(objSpan) {
    if ((hasReDoButton(objSpan))) {
        $(objSpan).removeClass('fas fa-redo');
        setResNumAltText(objSpan, false);
    }
}
