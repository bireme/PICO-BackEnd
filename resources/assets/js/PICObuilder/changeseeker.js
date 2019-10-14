import {
    MustRecalculate,
    JustUpdated,
    getComparisonCurrentLocal,
    getComparisonLocalPreviouslySaved,
    getComparisonGlobalPreviouslySaved,
    isHiddenResNum,
    setGlobalTitle,
    MustRecalculateFinal,
    getComparisonCurrentGlobal,
    JustUpdatedFinal,
} from "./changebasic.js";


////PUBLIC FUNCTIONS

export function ChangeSeekerStart() {
    InitialButtonSet();
    CheckIfChanged();
}


function CheckIfChanged() {
    setTimeout(function () {
        ChangeSeekerTimer();
        CheckIfChanged();
    }, 1500);
}

export function ChangeSeekerTimer() {
    let focused = $(':focus');
    let PICOnum = -1;
    if (focused.hasClass('PICOchangeitem')) {
        PICOnum = $(focused).attr('data-pico');
    }
    if (PICOnum > 0 && PICOnum < 5) {
        console.log("timercall");
        ChangeSeekerHandler(PICOnum);
    }
}

export function ChangeSeekerHandler(PICOnum) {
    let ishiddenlocalResnum = isHiddenResNum(PICOnum, true);
    let currentlocal = getComparisonCurrentLocal(PICOnum);
    let savedlocal = getComparisonLocalPreviouslySaved(PICOnum);
    console.log("CHANGESEEKERPICONUM..." + PICOnum + '...savedlocal=' + savedlocal);
    if (PICOnum < 5 && (savedlocal === undefined || savedlocal.length < 2)) {
        return;
    }
    if (currentlocal !== savedlocal) {
        MustRecalculate(PICOnum, false);
    } else {
        return;
    }
    let ishiddenGlobalResnum;
    let currentglobal = null;
    let savedglobal = null;
    let loop_i = PICOnum;
    if (loop_i === 1) {
        loop_i = 2;
    }

    for (loop_i; loop_i < 6; loop_i++) {
        ishiddenGlobalResnum = isHiddenResNum(loop_i, true);
        currentglobal = getComparisonCurrentGlobal(loop_i);
        savedglobal = getComparisonGlobalPreviouslySaved(loop_i);
        let savedlocal = getComparisonLocalPreviouslySaved(loop_i);
        if (loop_i < 5 && (savedlocal === undefined || savedlocal.length < 2)) {
            console.log(loop_i+'...not has global')
            continue;
        }
        if (currentglobal !== savedglobal) {
            if (loop_i < 5) {
                MustRecalculate(loop_i, true);
            } else {
                MustRecalculateFinal();
            }
        }
    }
}

export function UpdateLocalAfterResults(PICOnum, resultsNumber, resultsURL) {
    JustUpdated(PICOnum, false, false, resultsNumber, resultsURL);
}

export function UpdateGlobalAfterResults(PICOnum, resultsNumber, resultsURL, globaltitle) {
    setGlobalTitle(PICOnum, globaltitle);
    if (PICOnum > 4) {
        JustUpdatedFinal(resultsNumber, resultsURL)
    } else {
        JustUpdated(PICOnum, true, false, resultsNumber, resultsURL);
    }
}

function InitialButtonSet() {
    JustUpdated(1, false, true, null, null);
    for (let loop_w = 2; loop_w <= 4; loop_w++) {
        JustUpdated(loop_w, true, true, null, null);
        JustUpdated(loop_w, false, true, null, null);
    }
}
