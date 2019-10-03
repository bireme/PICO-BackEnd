import {
    ShowExplodeButton,
    hideGlobalButton,
    hideLocalButton,
    getLocalOldVal,
    getLocalCompValue,
    getGlobalOldVal,
    getGlobalCompValue,
    RemoveReDoButton,
    hasReDoButton
} from "./changebasic.js";

////PUBLIC FUNCTIONS

export function ChangeSeekerHandler(PICOnum) {
    ShowExplodeButton(PICOnum);
    CompareNewOld(PICOnum)
}

export function ChangeSeekerStart() {
    SetAllToReDoButton();
}

export function UpdateAfterResults(PICOnum) {

}

function getObjects(PICOnum,isGlobal) {
    let data={};
    if(isGlobal){
        data.objResNum = $('#ResNumGlobal' + PICOnum);
    }else{
        data.objResNum = $('#ResNumLocal' + PICOnum);
    }
    data.objSpan=$(data.objResNum).find('span').first();
    return data;
}



function CompareNewOld(PICOnum, isGlobal) {
    getObjects(PICOnum,isGlobal);
    let localcomp = CompareLocalPICO(PICOnum);
    let globalcomp = CompareGlobalPICO(PICOnum);

    MustRecalculate(objResNum, objSpan)
    ReturnToOldState(objResNum, objSpan)


    if (!(ThishasReDo) && PICOnum !== 5) {
        return;
    }


    if (localcomp.res) {
        ShowLocal(PICOnum);
    } else {
        HideLocal(PICOnum);
    }
    for (let loop_i = 1; loop_i <= 6; loop_i++) {
        if (globalcomp[loop_i].res) {
            ShowGlobal(PICOnum);
        } else {
            HideGlobal(PICOnum);
        }
    }
}

function CompareLocalPICO(PICOnum) {
    let data = {};
    data.current = getLocalOldVal(PICOnum);
    data.comparison = getLocalCompValue(PICOnum)
    if (data.current === data.comparison) {
        data.res = true;
    } else {
        data.res = false;
    }
    return data;
}

function CompareGlobalPICO(PICOnum) {
    let data = {};
    for (let loop_i = PICOnum; loop_i < 5; loop_i--) {
        data[loop_i] = {};
        data[loop_i].current = getGlobalOldVal(PICOnum);
        data[loop_i].comparison = getGlobalCompValue(PICOnum);
        data[loop_i].res = false;
        if (data[loop_i].current === data.comparison) {
            data[loop_i].res = true;
        }
    }
    return data;
}



function ShowLocal(PICOnum) {

}

function HideLocal(PICOnum) {

}

function ShowGlobal(PICOnum) {

}

function HideGlobal(PICOnum) {

}

function SetAllToReDoButton() {
    for (let loop_i = 1; loop_i <= 6; loop_i++) {
        hideLocalButton(PICOnum)
        hideGlobalButton(PICOnum)
    }
}

