import {translate} from "./translator.js";
import {getGlobalSpanJSON, RemoveReDoButton, hasReDoButton} from "./commonschange.js";
import {setResNumAltText} from "./commons.js";
import {isHiddenBootstrapObj, showBootstrapObj, hideBootstrapObj} from "./hideshow.js";

////PUBLIC FUNCTIONS

export function ChangeSeeker(PICOnum) {
    let LocalObj = $('#ResNumLocal' + PICOnum);
    let LocalSpan = LocalObj.find('span').first();
    let LocalField = $('#FieldList' + PICOnum);
    ShowExplodeButton(PICOnum);
    let ThishasReDo = hasReDoButton(LocalSpan);
    if (CompareNewOld(PICOnum, false) === 1) {
        ReturnToOldState(PICOnum, LocalObj, LocalSpan, LocalField, ThishasReDo);
    } else {
        ChangeToMustUpdate(PICOnum, LocalObj, LocalSpan, LocalField, ThishasReDo);
    }
}

export function SetAllToReDoButton() {
    $(document).find('a[id^=ResNum]').each(function () {
        let objSpan = $(this).find('span').first();
        let num = ($(this).attr('id')).substr(-1);
        if (num === 6) {

        } else {
            SetToReDoButton(objSpan);
            hideBootstrapObj($(this));
        }
    });
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

/*
* @returns {boolean} true if equal
*/

function CompareNewOldLocal(oldval, currentval) {
    if (oldval.local === currentval.local) {
        return true;
    } else {
        return false;
    }
}

/*
* @returns {boolean} true if equal
*/

function CompareNewOldGlobal(oldval, currentval) {
    if (oldval.global === currentval.global) {
        return true;
    } else {
        return false;
    }
}

function CompareNewOld(PICOnum, onlyGlobal) {
    let obj = $('#datainput' + PICOnum).attr('data-oldval');
    try {
        let oldval = JSON.parse(obj);

        let currentval = JSON.parse(getGlobalSpanJSON(PICOnum));
        let global = CompareNewOldGlobal(oldval, currentval);
        if (onlyGlobal === true) {
            return global;
        }
        let local = CompareNewOldLocal(oldval, currentval);
        if (global === true && local === true) {
            return 1;
        } else {
            if (local !== true) {
                return -1;
            } else {
                return 0;
            }
        }
    } catch (e) {
        return -1
    }
}

function ChangeToMustUpdate(PICOnum, LocalObj, LocalSpan, LocalField, ThishasReDo) {
    objResNumSetMustUpdate(LocalObj);
    SetToReDoButton(LocalSpan);
    let loop_i;
    for (loop_i = PICOnum; loop_i <= 6; loop_i++) {
        let GlobalObj = $('#ResNumGlobal' + loop_i);
        let GlobalSpan = GlobalObj.find('span').first();
        objResNumSetMustUpdate(GlobalObj);
        SetToReDoButton(GlobalSpan);
        if (loop_i === 6) {
            if (!(isHiddenBootstrapObj(GlobalSpan))) {
                setCalcResAsResult(loop_i);
            }
        } else {
            setCalcResAsResult(loop_i);
        }

    }
    let FinalSearchDetailsObj = $('#FinalSearchDetails');
    FinalSearchDetailsObj.attr('data-oldval', FinalSearchDetailsObj.val());
    FinalSearchDetailsObj.val(translate('emptyq'));
}

function ReturnToOldState(PICOnum, LocalObj, LocalSpan, LocalField, ThishasReDo) {
    if (!(ThishasReDo) && PICOnum !== 5) {
        return;
    }
    objResNumSetOldResult(LocalObj);
    objSpanSetOldResult(LocalSpan);
    RemoveReDoButton(LocalSpan);
    for (let loop_i = 1; loop_i <= 6; loop_i++) {
        let GlobalObj = $('#ResNumGlobal' + loop_i);
        let GlobalSpan = GlobalObj.find('span').first();
        objResNumSetOldResult(GlobalObj);
        objSpanSetOldResult(GlobalSpan);
        RemoveReDoButton(GlobalSpan);
        let objdata = $('#datainput' + loop_i).val();
        if (objdata) {
            if (objdata.length > 0) {
                setCalcResAsSyncAlt(loop_i);
            }
        } else {
            if (loop_i === 6) {
                setCalcResAsSyncAlt(loop_i);
            }
        }
        let FinalSearchDetailsObj = $('#FinalSearchDetails');
        FinalSearchDetailsObj.val(FinalSearchDetailsObj.attr('data-oldval'));
    }
}

function objResNumSetMustUpdate(objResNum) {
    objResNum.attr('data-oldval', (objResNum.attr('href')));
    objResNum.removeAttr('href');
}

function ShowExplodeButton(PICOnum) {
    let explodebutton = $('#Exp' + PICOnum);
    if ($('#datainput' + PICOnum).val().length > 0) {
        showBootstrapObj(explodebutton);
        if (objResNumHasoldval(PICOnum)) {
            showBootstrapObj($('#ResNumLocal' + PICOnum));
            showBootstrapObj($('#ResNumGlobal' + PICOnum));
            setCalcResAsSyncAlt(PICOnum);
        }
    } else {
        setCalcResAsResult(PICOnum);
        hideBootstrapObj($('#ResNumLocal' + PICOnum));
        hideBootstrapObj($('#ResNumGlobal' + PICOnum));
        hideBootstrapObj(explodebutton);
    }
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

function SetToReDoButton(objSpan) {
    if (!(hasReDoButton(objSpan))) {
        $(objSpan).addClass('fas fa-redo');
        $(objSpan).text(' ');
        setResNumAltText(objSpan, true);
    }
}
