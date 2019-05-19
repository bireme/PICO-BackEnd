function SetAllToReDoButton() {
    $(document).find('a[id^=ResNum]').each(function () {
        objSpan = $(this).find('span').first();
        var num = ($(this).attr('id')).substr(-1);
        if (num == 6) {

        } else {
            SetToReDoButton(objSpan);
            hideBootstrapObj($(this));
        }
    });
}

function hasReDoButton(objSpan) {
    return (objSpan).hasClass('fa-redo');
}

function SetToReDoButton(objSpan) {
    if (!(hasReDoButton(objSpan))) {
        $(objSpan).addClass('fas fa-redo');
        $(objSpan).text(' ');
        setResNumAltText(objSpan, true);
    }
}

function RemoveReDoButton(objSpan) {
    if ((hasReDoButton(objSpan))) {
        $(objSpan).removeClass('fas fa-redo');
        setResNumAltText(objSpan, false);
    }
}

function setResNumAltText(objSpan, hasReDo) {
    var msg = MessageCode(139);
    if (hasReDo == true) {
        msg = MessageCode(161);
    }
    $(objSpan).parent().attr('data-original-title', msg);
}

function objSpanSetOldResult(objSpan) {
    objSpan.text(objSpan.attr('data-oldval'));
}

function objResNumSetOldResult(objResNum) {
    objResNum.attr('href', (objResNum.attr('data-oldval')));
}

function objResNumHasoldval(PICOnum) {
    if ($('#ResNumLocal' + PICOnum).attr('data-oldval')) {
        return true;
    }
    return false;
}

function ChangeSeeker(PICOnum) {
    var LocalObj = $('#ResNumLocal' + PICOnum);
    var LocalSpan = LocalObj.find('span').first();
    var LocalField = $('#FieldList' + PICOnum);
    ShowExplodeButton(PICOnum);
    var ThishasReDo = hasReDoButton(LocalSpan);
    if (CompareNewOld(PICOnum, false) == 1) {
        ReturnToOldState(PICOnum, LocalObj, LocalSpan, LocalField, ThishasReDo);
    } else {
        ChangeToMustUpdate(PICOnum, LocalObj, LocalSpan, LocalField, ThishasReDo);
    }
}

function ShowExplodeButton(PICOnum) {
    var explodebutton = $('#Exp' + PICOnum);
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

function FieldListSetoldval(PICOnum) {
    var objFieldList = $('#FieldList' + PICOnum);
    objFieldList.attr('data-oldval', getFieldListOptionNum(PICOnum));
}


function objResNumSetMustUpdate(objResNum) {
    objResNum.attr('data-oldval', (objResNum.attr('href')));
    objResNum.removeAttr('href');
}

function ReturnToOldState(PICOnum, LocalObj, LocalSpan, LocalField, ThishasReDo) {
    if (!(ThishasReDo) && PICOnum != 5) {
        return;
    }
    objResNumSetOldResult(LocalObj);
    objSpanSetOldResult(LocalSpan);
    RemoveReDoButton(LocalSpan);
    for (var loop_i = 1; loop_i <= 6; loop_i++) {
        var GlobalObj = $('#ResNumGlobal' + loop_i);
        var GlobalSpan = GlobalObj.find('span').first();
        objResNumSetOldResult(GlobalObj);
        objSpanSetOldResult(GlobalSpan);
        RemoveReDoButton(GlobalSpan);
        var objdata = $('#datainput' + loop_i).val();
        if (objdata) {
            if (objdata.length > 0) {
                hideBootstrapObj($('#CalcRes' + loop_i));
            }
        } else {
            if (loop_i == 6) {
                hideBootstrapObj($('#CalcRes' + loop_i));
            }
        }
        $('#FinalSearchDetails').val($('#FinalSearchDetails').attr('data-oldval'));
    }
}

function ChangeToMustUpdate(PICOnum, LocalObj, LocalSpan, LocalField, ThishasReDo) {
    objResNumSetMustUpdate(LocalObj);
    SetToReDoButton(LocalSpan);
    var loop_i;
    for (loop_i = PICOnum; loop_i <= 6; loop_i++) {
        var GlobalObj = $('#ResNumGlobal' + loop_i);
        var GlobalSpan = GlobalObj.find('span').first();
        objResNumSetMustUpdate(GlobalObj);
        SetToReDoButton(GlobalSpan);
        if (loop_i == 6) {
            if (!(isHiddenBootstrapObj(GlobalSpan))) {
                showBootstrapObj($('#CalcRes' + loop_i));
            }
        } else {
            showBootstrapObj($('#CalcRes' + loop_i));
        }

    }
    $('#FinalSearchDetails').attr('data-oldval', $('#FinalSearchDetails').val());
    $('#FinalSearchDetails').val(MessageCode(23));
}

function CompareNewOld(PICOnum, onlyGlobal) {
    var obj = $('#datainput' + PICOnum).attr('data-oldval');
    try {
        var oldval = JSON.parse(obj);
    } catch (e) {
        return -1
    }
    var currentval = JSON.parse(getGlobalSpanJSON(PICOnum));
    var global = CompareNewOldGlobal(oldval, currentval);
    if (onlyGlobal == true) {
        return global;
    }
    var local = CompareNewOldLocal(oldval, currentval);
    if (global == true && local == true) {
        return 1;
    } else {
        if (local == true) {
            return 0;
        } else {
            return -1;
        }
    }
}

function CompareNewOldLocal(oldval, currentval) {
    var oldlocal = oldval.local;
    var newlocal = currentval.local;
    if (oldlocal == newlocal) {
        return true;
    } else {
        return false;
    }
}

function CompareNewOldGlobal(oldval, currentval) {
    var oldglobal = oldval.global;
    var newglobal = currentval.global;
    if (oldglobal == newglobal) {
        return true;
    } else {
        return false;
    }
}





function isHiddenBootstrapObj(Obj) {
    return $(Obj).hasClass('d-none');
}

function showBootstrapObj(Obj) {
    if (isHiddenBootstrapObj(Obj)) {
        $(Obj).removeClass('d-none');
    }
}

function hideBootstrapObj(Obj) {
    if (!(isHiddenBootstrapObj(Obj))) {
        $(Obj).addClass('d-none');
    }
}

function setCalcResAsSyncAlt(PICOnum) {
    var CalcResObj = $('#CalcRes' + PICOnum);
    $(CalcResObj).html('<i class="fas fa-sync-alt"></i>');
    $(CalcResObj).addClass(getCalcResColorClass(PICOnum));
    $(CalcResObj).removeClass(getCalcResColorClass(-1));
}

function setCalcResAsResult(PICOnum) {
    var CalcResObj = $('#CalcRes' + PICOnum);
    $(CalcResObj).html(MessageCode(132));
    $(CalcResObj).addClass(getCalcResColorClass(-1));
    $(CalcResObj).removeClass(getCalcResColorClass(PICOnum));
}

function getCalcResColorClass(PICOnum) {
    if (PICOnum == -1) {
        return 'btn-info';
    }
    if (PICOnum == 1) {
        return 'btn-outline-info';
    }
    return 'btn-outline-warning';
}

function getGlobalSpanJSON(PICOnum) {
    if (PICOnum == 6) {
        PICOnum = 5;
    }
    var global = [];
    var local = [];
    for (var loop_i = 1; loop_i <= PICOnum; loop_i++) {
        global.push($('#datainput' + loop_i).val());
        if (loop_i < 5) {
            global.push(getFieldListOptionNum(loop_i));
        }
        if (loop_i == PICOnum) {
            local.push($('#datainput' + loop_i).val());
            if (loop_i < 5) {
                local.push(getFieldListOptionNum(loop_i));
            }
        }
    }
    var Result = {
        local: JSON.stringify(local),
        global: JSON.stringify(global)
    };
    return JSON.stringify(Result);
}