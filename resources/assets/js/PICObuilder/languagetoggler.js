import {translate} from "./translator.js";
import {getPICOElements} from "./commons.js";
import {getPICOnumFromObjId} from "./datadictionary.js";


////PUBLIC FUNCTIONS

export function UpdateLanguageInfo() {
    setLocalButtonLanguage();
    setResNumAltTextGlobal();
    ChangeSearchDetailsInfo();
    CalcResLanguage();
}

////PRIVATE FUNCTIONS

function setLocalButtonLanguage() {
    let PICOs = getPICOElements();
    PICOs = PICOs.map(function (x) {
        return ((x.charAt(0)).toUpperCase() + (x.slice(1)).toLowerCase());
    });
    $(document).find('[id^=ResNumLocal]').each(function () {
        let PICOnum = getPICOnumFromObjId($(this));
        $(this).find('.label').first().text(PICOs[PICOnum - 1]);
    });
}

function setResNumAltTextGlobal() {
    $(document).find('input[id^=ResNum]').each(function () {
        if ($(this).find('span').first().hasClass('fa-redo')) {
            $(this).attr('data-original-title', translate('pressres'));
        } else {
            $(this).attr('data-original-title', translate('clickres'));
        }
    });
}

function ChangeSearchDetailsInfo() {
    let Arr = getSearchDetailsInfo();
    let FinalSearchDetailsObj = $('#FinalSearchDetails');
    let prev = FinalSearchDetailsObj.val();
    let index = Arr.indexOf(prev);
    if (index > -1) {
        FinalSearchDetailsObj.text(translate('emptyq'));
    }
}

function CalcResLanguage() {
    $(document).find('button[id^=CalcRes]').each(function () {
        if ($(this).find('i').length === 0) {
            $(this).text(translate('butres'));
        }
        $(this).attr('data-original-title', translate('upres'));
    });
}

function getSearchDetailsInfo() {
    return translate('pleaseupd');
}
