import {
    ReturnToOldState,
    MustRecalculate,
    JustUpdated,
    getComparisonCurrentLocal,
    getComparisonLocalPreviouslySaved,
    getComparisonGlobalPreviouslySaved,
    isHiddenResNum,
    setGlobalTitle,
} from "./changebasic.js";


////PUBLIC FUNCTIONS

export function ChangeSeekerStart() {
    InitialButtonSet();
    CheckIfChanged();
}

function CheckIfChanged() {
    setTimeout(function () {
        ChangeSeekerHandler(false);
        CheckIfChanged();
    }, 1500);
}

export function ChangeSeekerHandler(isfirst) {
    let PICOnum = 1;
    let focused = $(':focus');
    if (isfirst === false) {
        if (!(focused.hasClass('PICOchangeitem'))) {
            return;
        } else {
            PICOnum = $(focused).attr('data-PICO');
        }
    }
    let tmptwo = '';
    let tmp;
    let ishiddenLocalResnum;
    let ishiddenGlobalResnum;

    for (let loop_i = PICOnum; loop_i < 7; loop_i++) {
        tmp = getComparisonCurrentLocal(loop_i);
        if (loop_i === PICOnum && loop_i < 5) {
            ishiddenLocalResnum = isHiddenResNum(loop_i, false);
            if (getComparisonLocalPreviouslySaved(PICOnum) === tmp) {
                ReturnToOldState(PICOnum, false);
            } else {
                if (!(ishiddenLocalResnum)) {
                    MustRecalculate(PICOnum, false);
                }
            }
        }
        tmptwo = tmptwo + tmp;
        if (loop_i > 1) {
            ishiddenGlobalResnum = isHiddenResNum(loop_i, true);
            if (tmptwo === getComparisonGlobalPreviouslySaved(loop_i)) {
                ReturnToOldState(loop_i, true);
            } else {
                if (!(ishiddenGlobalResnum)) {
                    MustRecalculate(loop_i, true);
                }
            }
        }
    }
}

export function UpdateLocalAfterResults(PICOnum, resultsNumber, resultsURL) {
    JustUpdated(PICOnum, false, false,resultsNumber, resultsURL);
}

export function UpdateGlobalAfterResults(PICOnum, resultsNumber, resultsURL,globaltitle) {
    setGlobalTitle(PICOnum,globaltitle);
    JustUpdated(PICOnum, true, false,resultsNumber, resultsURL);
}

function InitialButtonSet() {
    for (let loop_i = 1; loop_i <= 6; loop_i++) {
        JustUpdated(loop_i, true, true,null,null);
        JustUpdated(loop_i, false, true,null,null);
    }
}
