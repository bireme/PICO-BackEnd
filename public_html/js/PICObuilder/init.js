function initfunctions() {
    $('button.ExpandDeCS').click(function (e) {
        if ($(this).find('.startlanguage').is(":hover")) {
            e.preventDefault();
            ExpandDeCSConfig();
            return;
        }
        PICOnumGlobal = ($(this).attr('id')).substr(-1);
        OnExpandDeCS($(this));
    });
    $('#modal').find('.btn-primary').click(function () {
        HideUnselectedDeCS();
    });
    $('#modal3').find('.btn-primary').click(function () {
        ProcessResults();
    });
    $(document).find('button[id^=CalcRes]').click(function () {
        var PICOnum = ($(this).attr('id')).substr(-1);
        getResultsNumber(PICOnum);
    });
    $(document).find('#modal4').find('.btn-info').click(function () {
        hideLoading();
        CancelLoading();
    });
    $(document).find('a[id^=ResNum]').click(function (e) {
        var PICOnum = ($(this).attr('id')).substr(-1);
        if (PICOnum == 6) {
            var obj = $(this).find('span').first();
            if (isHiddenBootstrapObj(obj)) {
                getResultsNumber(6);
                return;
            }
        }
        if ($(this).find('.PICOiconzeroElement').is(":hover")) {
            e.preventDefault();
            InfoNoResults();
            return;
        }
        CheckExistantHREF($(this));
    });
    $(document).find('input[id^=datainput]').on('input', function () {
        ChangeSeeker(($(this).attr('id')).substr(-1));
    });
    $('#collapseFive').find('.form-group').find('input').change(function () {
        ReBuildStudyType();
    });
    $(document).find('select[id^=FieldList]').change(function () {
        ChangeSeeker(($(this).attr('id')).substr(-1));
    });
    $(document).find('a[id^=PICOinfo]').click(function (e) {
        e.stopPropagation();
        ShowPICOinfo(($(this).attr('id')).substr(-1));
    });
    $(document).find('button[id^=page-lang]').click(function () {
        ChangeLanguage(($(this).attr('id')).substr(-1));
    });
    $(document).on('click', ".golanguage", function () {
        setLanguagesFromModal($(this).parent().parent());
    });
    UpdateLanguageInfo();
    SetAllToReDoButton();
    TopPanelLanguages();
}
;

function InfoNoResults() {
    showInfoMessage('Info', MessageCode(146), false);
}

function CheckExistantHREF(Obj) {
    if (!($(Obj).attr('href'))) {
        if (!($(Obj).attr('data-oldval'))) {
            showInfoMessage('Info', MessageCode(21), false);
        } else {
            showInfoMessage('Info', MessageCode(22), false);
        }
    } else {
        var win = window.open((Obj).attr('href'), '_blank');
        if (win) {
            win.focus();
        } else {
            showInfoMessage('Error', MessageCode(3), false);
        }
    }
}

function hideModal2() { //BORRARESTO
    var Interv = setInterval(function () {
        $('#modal2').focus();
        $('#modal2').modal('toggle');
        $('#modal2').modal('hide');
        if (!($('#modal2').hasClass('show'))) {
            clearInterval(Interv);
        }
    }, 500);
}

function ShowPICOinfo(PICOnum) {
    var title= getPICOElements()[PICOnum-1];
    var msg = getPICOinfo()[PICOnum - 1];
    if (PICOnum < 5) {
        msg = title + ' \n \n' + msg + ' \n \n' + getPICOPlaceHolder(PICOnum);
    }
    showInfoMessage('Info', msg, false);
}