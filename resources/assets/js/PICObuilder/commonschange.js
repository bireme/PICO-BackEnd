import {setResNumAltText} from "./commons.js";

////PUBLIC FUNCTIONS

export function getGlobalSpanJSON(PICOnum) {
    if (PICOnum === 6) {
        PICOnum = 5;
    }
    let global = [];
    let local = [];
    for (let loop_i = 1; loop_i <= PICOnum; loop_i++) {
        let datainputVal =$('#datainput' + loop_i).val();
        global.push();
        if (loop_i < 5) {
            global.push(getFieldListOptionNum(loop_i));
        }
        if (loop_i === PICOnum) {
            local.push(datainputVal);
            if (loop_i < 5) {
                local.push(getFieldListOptionNum(loop_i));
            }
        }
    }
    let Result = {
        local: JSON.stringify(local),
        global: JSON.stringify(global)
    };
    return JSON.stringify(Result);
}

export function getFieldListOptionNum(PICOnum) {
    return $('#FieldList' + PICOnum).children("option:selected").index();
}

export function RemoveReDoButton(objSpan) {
    if ((hasReDoButton(objSpan))) {
        $(objSpan).removeClass('fas fa-redo');
        setResNumAltText(objSpan, false);
    }
}

export function hasReDoButton(objSpan) {
    return (objSpan).hasClass('fa-redo');
}

////PRIVATE FUNCTIONS
