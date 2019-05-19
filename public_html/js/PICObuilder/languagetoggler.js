function ChangeLanguage(langid) {
    globalLanguage = langid;
    UpdateLanguageInfo(langid);
}

function UpdateLanguageInfo(lang) {
    setMainTitlesLanguage();
    setFieldListLanguage();
    setPlaceHolderLanguage();
    setExpandDeCSLanguage();
    SearchDetailsLanguage();
    FinalSearchLanguage();
    CalcResLanguage();
    AllRightsReservedLang();
    TopMenuToggleLang(lang);
    setLoadingLanguage();
    setDeCSImportLanguage();
    ChangeSearchDetailsInfo();
    setResNumAltTextGlobal();
    setLocalButtonLanguage();
}

function setMainTitlesLanguage() {
    $(document).find('div[id^=heading]').each(function () {
        var num = ($(this).attr('id')).substr(-1);
        var label = $(this).find('button.labelMain').first();
        var msg = getPICOElements()[num - 1];
        label.text(msg);
    });
}

function setFieldListLanguage() {
    var Opts = getFieldListOptions();
    $(document).find('select[id^=FieldList]').each(function () {
        var count = 0;
        $(this).find('option').each(function () {
            $(this).text(Opts[count]);
            count++;
        });
    });
}

function setPlaceHolderLanguage() {
    var Opts = getFieldListOptions();
    $(document).find('input[id^=datainput]').each(function () {
        var PICOnum = ($(this).attr('id')).substr(-1);
        $(this).attr('placeholder', getPICOPlaceHolder(PICOnum));
    });
}

function setExpandDeCSLanguage() {
    $(document).find('button[id^=Exp]').each(function () {
        var msg = '<span class="badge badge-light badgeM startlanguage"><i class="fas fa-cog"></i></span> ' + MessageCode(131);
        $(this).html(msg);
    });
}

function SearchDetailsLanguage() {
    $(document).find('b.sdlabel').text(MessageCode(133));
}

function FinalSearchLanguage() {
    $('#ResNumGlobal6').find('label').text(MessageCode(136));
}

function CalcResLanguage() {
    $(document).find('button[id^=CalcRes]').each(function () {
        if ($(this).find('i').length == 0) {
            $(this).text(MessageCode(132));
        }
        $(this).attr('data-original-title', MessageCode(138));
    });
}

function AllRightsReservedLang() {
    $('#footer').find('div').first().text(MessageCode(137));
}

function TopMenuToggleLang(lang) {
    var lang = getLanguage();
    $(document).find('button[id^=page-lang]').each(function () {
        var id = ($(this).attr('id')).substr(-1);
        if (id == lang) {
            hideBootstrapObj($(this));
            hideBootstrapObj($('#sep-lang' + id));
        } else {
            showBootstrapObj($(this));
            if (!((lang == 3) && (id == 2))) {
                showBootstrapObj($('#sep-lang' + id));
            }
        }
    });
}

function setLoadingLanguage() {
    $('#modal4').find('label').first().text(MessageCode(221));
    $('#modal4').find('button').first().text(MessageCode(222));
}

function setDeCSImportLanguage() {
    $('#modal').find('.modal-title').text(MessageCode(231));
    $('#modal2').find('.modal-title').text(MessageCode(233));
    $('#modal3').find('.modal-title').text(MessageCode(234));
    $('#modal3').find('.modal-body').first().find('label').text(MessageCode(235));
    $('#modal3').find('.modal-body').first().find('textarea').text(MessageCode(236));
    $('#modal').find('.modal-footer').first().find('button').text(MessageCode(232));
    $('#modal2').find('.modal-footer').first().find('button').text(MessageCode(232));
    $('#modal3').find('.modal-footer').first().find('button').text(MessageCode(232));
}

function ChangeSearchDetailsInfo() {
    var Arr = getSearchDetailsInfo();
    var prev = $('#FinalSearchDetails').val();
    var index = Arr.indexOf(prev);
    if (index > -1) {
        $('#FinalSearchDetails').val(MessageCode(23));
    }
}

function setResNumAltTextGlobal() {
    $(document).find('a[id^=ResNum]').each(function () {
        if ($(this).find('span').first().hasClass('fa-redo')) {
            $(this).attr('data-original-title', MessageCode(161));
        } else {
            $(this).attr('data-original-title', MessageCode(139));
        }
    });
}

function setLocalButtonLanguage() {
    var PICOs = getPICOElements();
    PICOs = PICOs.map(function (x) {
        return ((x.charAt(0)).toUpperCase() + (x.slice(1)).toLowerCase());
    });
    $(document).find('[id^=ResNumLocal]').each(function () {
        var PICOnum = ($(this).attr('id')).substr(-1);
        $(this).find('label').first().text(PICOs[PICOnum - 1]);
    });
}

function TopPanelLanguages() {
    var langs = getLanguagesInNative();
    var count = 0;
    $('#header').find('button').each(function () {
        $(this).text(langs[count]);
        count++;
    });
}