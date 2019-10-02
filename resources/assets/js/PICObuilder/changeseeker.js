import {ShowExplodeButton,hideGlobalButton,hideLocalButton, getLocalOldVal,getLocalCompValue,getGlobalOldVal,getGlobalCompValue,RemoveReDoButton, hasReDoButton} from "./changebasic.js";

////PUBLIC FUNCTIONS

export function ChangeSeekerHandler(PICOnum) {
    ShowExplodeButton(PICOnum);
    CompareNewOld()
}

export function ChangeSeekerStart() {
    SetAllToReDoButton();
}


export function UpdateAfterResults(PICOnum){
    let localcomp = CompareLocalPICO(PICOnum);
    let globalcomp = CompareGlobalPICO(PICOnum);
}




export function SetAllToReDoButton() {
    for (let loop_i = 1; loop_i <= 6; loop_i++) {
        hideLocalButton(PICOnum)
        hideGlobalButton(PICOnum)
    }
}

function CompareLocalPICO(PICOnum){
    let data={};
    data.current =getLocalOldVal(PICOnum);
    data.comparison = getLocalCompValue(PICOnum)
    if(data.current===data.comparison){
        data.res=true;
    }else{
        data.res=false;
    }
    return data;
}

function CompareGlobalPICO(PICOnum){
    let data={};
    for (let loop_i = PICOnum; loop_i < 5; loop_i--) {
        data[loop_i]={};
        data[loop_i].current =getGlobalOldVal(PICOnum);
        data[loop_i].comparison = getGlobalCompValue(PICOnum)
        if(data[loop_i].current===data.comparison){
            data[loop_i].res=true;
        }else{
            data[loop_i].res=false;
        }
    }
    return data;
}

