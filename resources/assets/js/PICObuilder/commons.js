import {translate} from "./translator.js";

////PUBLIC FUNCTIONS

export function getPICOElements() {
    return [translate('pico1'),translate('pico2'),translate('pico3'),translate('pico4'),translate('pico5'),translate('picototal')];
}

export function setResNumAltText(objSpan, hasReDo) {
    let msg = translate('clickres');
    if (hasReDo === true) {
        msg = translate('pressres');
    }
    $(objSpan).parent().attr('data-original-title', msg);
}

export function getFieldListOptionNum(PICOnum) {
    return $('#FieldList' + PICOnum).children("option:selected").index();
}


////PRIVATE FUNCTIONS
