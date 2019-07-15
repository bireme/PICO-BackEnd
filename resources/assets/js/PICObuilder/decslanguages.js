import {translate} from "./translator.js";

////PUBLIC FUNCTIONS

export function setLanguagesOfModal(langArr) {

    let count = 0;
    $(document).find('.langCheck').each(function () {
        let index = langArr.indexOf($(this).val());
        if (index > -1) {
            $(this).attr('checked', true);
        } else {
            $(this).attr('checked', false);
        }
        count++;
    });
    DeCSMenuLanguages();
}

////PRIVATE FUNCTIONS

function DeCSMenuLanguages() {
    let langs = getLanguagesInGlobalLang();
    let count = 0;
    $('#modalinfo').find('.LanguageInfoContainer').first().find('label').each(function () {
        $(this).text(langs[count]);
        count++;
    });
}

function getLanguagesInGlobalLang() {
    return [translate('langen'),translate('langpt'),translate('langes'),translate('langfr')];
}
