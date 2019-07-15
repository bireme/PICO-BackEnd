import {translate} from "./translator.js";
import {getPICOElements} from "./commons.js";

////PUBLIC FUNCTIONS

export function UpdateLanguageInfo() {
    setLocalButtonLanguage();
    setResNumAltTextGlobal();
    ChangeSearchDetailsInfo();
    CalcResLanguage();
    setExpandDeCSLanguage();
}

////PRIVATE FUNCTIONS

function setLocalButtonLanguage() {
    let PICOs = getPICOElements();
    PICOs = PICOs.map(function (x) {
        return ((x.charAt(0)).toUpperCase() + (x.slice(1)).toLowerCase());
    });
    $(document).find('[id^=ResNumLocal]').each(function () {
        let PICOnum = ($(this).attr('id')).substr(-1);
        $(this).find('label').first().text(PICOs[PICOnum - 1]);
    });
}

function setResNumAltTextGlobal() {
    $(document).find('a[id^=ResNum]').each(function () {
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
        FinalSearchDetailsObj.val(translate('emptyq'));
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

function setExpandDeCSLanguage() {
    $(document).find('button[id^=Exp]').each(function () {
        let msg = '<span class="badge badge-light badgeM startlanguage"><i class="fas fa-cog"></i></span> ' + translate('butexp');
        $(this).html(msg);
    });
}

function getSearchDetailsInfo() {
    return translate('pleaseupd');
}
