
////PUBLIC FUNCTIONS

export function getPreviousResults() {
    return $('#TmpCookieElement').attr('data-previous-decs');
}

export function getLanguages() {
    let langs = [];
    $(document).find('.languageCheckbox').each(function () {
        if ($(this).is(':checked')) {
            langs.push($(this).val());
        }
    });
    return langs;
}

////PRIVATE FUNCTIONS
