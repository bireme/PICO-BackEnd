var currentrequest;
$(document).ready(() => {
    $('a.ExpandDeCS').click(function (e) {
        if ($(this).find('.startlanguage').is(":hover")) {
            e.preventDefault();
            ExpandDeCSConfig();
            return;
        }
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
    $(document).find('#modal4').find('.close').click(function () {
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
    $(document).find('a[id^=page-lang]').click(function () {
        ChangeLanguage(($(this).attr('id')).substr(-1));
    });
    $(document).on('click', ".golanguage", function () {
        setLanguagesFromModal($(this).parent().parent());
    });
    UpdateLanguageInfo();
    SetAllToReDoButton();
});







function ChangeLanguage(langid) {
    globalLanguage = langid;
    UpdateLanguageInfo();
}

function addIconZeroResults(obj) {
    var iconHTML = '0 <a class="PICOiconzeroElement"><span>?</span></a>';
    $(obj).html(iconHTML);
}

function ExpandDeCSConfig() {
    var msg = `
<div id="LanguageSection" class="container ">
    <div class="row">
        <div class="col-md-7 sidebar LanguageContainer">
            <label class="labelMain">
                ` + MessageCode(135) + `
            </label>
        </div>
        <div class="col-md-5 sidebar LanguageContainer text-left">
           <div class="LanguageInfoContainer">
                    <div class="CheckBoxRow" ><input type="checkbox" class="langCheck" name="Langs[]" value="en" checked /><label>English</label></div>
                    <div class="CheckBoxRow" ><input type="checkbox" class="langCheck" name="Langs[]" value="es" /><label>Spanish</label></div>
                    <div class="CheckBoxRow" ><input type="checkbox" class="langCheck" name="Langs[]" value="pt" /><label>Portuguese</label></div>
            </div>
        </div>
    </div>
</div`
    var langs = getLanguages();
    showInfoMessage('Config', msg, true, 'golanguage', setLanguagesOfModal, langs);
}

function UpdateLanguageInfo() {
    setMainTitlesLanguage();
    setFieldListLanguage();
    setPlaceHolderLanguage();
    setExpandDeCSLanguage();
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
    $(document).find('a[id^=Exp]').each(function () {
        var msg = '<span class="badge badge-light badgeM startlanguage"><i class="fas fa-cog"></i></span> ' + MessageCode(131);
        $(this).html(msg);
    });
}

function setCalcResAsSyncAlt(PICOnum) {
    var CalcResObj = $('#CalcRes' + PICOnum);
    $(CalcResObj).html('<i class="fas fa-sync-alt"></i>');
    $(CalcResObj).addClass(getCalcResColorClass(PICOnum));
    $(CalcResObj).removeClass(getCalcResColorClass(-1));
}

function setCalcResAsResult(PICOnum) {
    var CalcResObj = $('#CalcRes' + PICOnum);
    $(CalcResObj).html('Results');
    $(CalcResObj).addClass(getCalcResColorClass(-1));
    $(CalcResObj).removeClass(getCalcResColorClass(PICOnum));

}

function getCalcResColorClass(PICOnum) {
    if (PICOnum == -1) {
        return 'btn-info';
    }
    if (PICOnum == 1) {
        return 'btn-outline-info';
    }
    return 'btn-outline-warning';
}

function ShowPICOinfo(PICOnum) {
    var msg = getPICOinfo()[PICOnum - 1];
    if (PICOnum < 5) {
        msg = msg + ' \n \n' + getPICOPlaceHolder(PICOnum);
    }
    showInfoMessage('Info', msg, false);
}

function InfoNoResults() {
    showInfoMessage('Info', MessageCode(146), false);
}

function CalcResLanguage() {
    $(document).find('button[id^=CalcRes]').each(function () {
        $(this).find('label').text(MessageCode(132));
    });
}

function SearchDetailsLanguage() {
    $(document).find('b.sdlabel').text(MessageCode(133));
}

function ReBuildStudyType() {
    var CheckArr = [];
    $('#collapseFive').find('.form-group').each(function () {
        if ($(this).find('input').first().is(':checked')) {
            var txt = $(this).find('label').first().text();
            CheckArr.push(txt);
        }
    });
    var msg = CheckArr.join(' OR ');
    if (msg.length > 0) {
        msg = '(' + msg + ')';
    }
    $('#datainput5').val(msg);
    ChangeSeeker(5);
}






function getBaseURL() {
    return "http://localhost/PHP-Bireme/";
}

function OnExpandDeCS(ExpandButton) {
    var langs = getLanguages();
    var PICOval = '#datainput' + ($(ExpandButton).attr('id')).substr(-1);
    var query = $(PICOval).val();
    var PICOnum = ($(ExpandButton).attr('id')).substr(-1);
    eventDeCSSearch(query, langs, PICOnum);
}

function setLanguagesFromModal(LangParent) {
    var langs = [];
    $(LangParent).find('.langCheck').each(function () {
        if ($(this).is(':checked')) {
            langs.push($(this).val());
        }
    });
    setLanguages(langs);
}

function setLanguagesOfModal(langArr) {
    var count = 0;
    $(document).find('.langCheck').each(function () {
        var index = langArr.indexOf($(this).val());
        if (index > -1) {
            $(this).attr('checked', true);
        } else {
            $(this).attr('checked', false);
        }
        count++;
    });

}


function setLanguages(langArr) {
    var count = 0;
    $(document).find('.languageCheckbox').each(function () {
        var index = langArr.indexOf($(this).val());
        if (index > -1) {
            $(this).attr('checked', true);
        } else {
            $(this).attr('checked', false);
        }
        count++;
    });

}

function getLanguages() {
    var langs = [];
    var langObj = $(document).find('.languageCheckbox').each(function () {
        if ($(this).is(':checked')) {
            langs.push($(this).val());
        }
    });
    return langs;
}

function POSTrequest(url, data, callback) {
    showLoading();
    var sendData = {
        data: JSON.stringify(data)
    };
    currentrequest = $.post(url, sendData,
            function (obtainedData) {
                try {
                    var Data = JSON.parse(obtainedData);
                } catch (Exception) {
                    hideLoading();
                    setTimeout(function () {
                        ConsoleErr(data, obtainedData);
                        showInfoMessage('Error', MessageCode(2), false);
                    }, 300);
                    return;
                }
                hideLoading();
                setTimeout(function () {
                    callback(Data);
                }, 300);
            }).fail(function () {
        hideLoading();
        setTimeout(function () {
            showInfoMessage('Error', MessageCode(1), false);
        }, 300);
    });
}

function eventDeCSSearch(query, langs, PICOnum) {
    var url = getBaseURL() + "ControllerEventDeCSSearch.php";
    var data = {
        query: query,
        langs: langs,
        PICOnum: PICOnum
    };
    POSTrequest(url, data, function (Data) {
        createDeCSMenu(Data);
        showDeCSMenu();
    });
}

function createDeCSMenu(data) {
    var results = data.results;
    var HTMLDescriptors = data.HTMLDescriptors;
    var HTMLDeCS = data.HTMLDeCS;
    $('#modal').find('.modal-body').first().html(HTMLDescriptors);
    $('#modal2').find('.modal-body').first().html(HTMLDeCS);
}

function HideUnselectedDeCS() {
    var DeCSModalTitlePrefix = 'opcao';
    var DeCSModalTitlePostfix = '-tab';
    var num = 0;
    $('#modal').find('input.DescriptorCheckbox').each(function () {
        var identifier = ($(this).attr('id')).substring(10);
        var titleid = '#' + DeCSModalTitlePrefix + identifier + DeCSModalTitlePostfix;
        var contentid = '#' + DeCSModalTitlePrefix + identifier;
        if ($(this).is(':checked')) {
            $('#modal2').find(contentid).prop('checked', true);
            if (num == 0) {
                if ($('#modal2').find(titleid).hasClass('active') == false) {
                    $('#modal2').find(titleid).toggle('nav-link active');
                }
            }
            num++;
        } else {
            $('#modal2').find(titleid).hide();
            $('#modal2').find(contentid).hide();
        }
    });
}

function ProcessResults() {
    var PICOnum = $('#modal').find('#PICONumTag').val();
    var query = $('#datainput' + PICOnum).val();
    var SelectedDescriptors = getSelectedDescriptors();
    var ImproveSearch = $('#modal3').find('textarea').val();
    eventQueryBuild(PICOnum, query, SelectedDescriptors, ImproveSearch);
}

function getSelectedDescriptors() {
    var DeCSModalTitlePrefix = 'opcao';
    var DeCSModalTitlePostfix = '-tab';
    var num = 0;
    var SelectedDescriptors = [];
    $('#modal2').find('.DeCSCheckBoxElement').each(function () {
        if (!($(this).find('input').first().is(':checked'))) {
            return;
        }
        var identifier = ($(this).attr('id')).substring(DeCSModalTitlePrefix.length);
        var tmp = identifier.split('-');
        var DescriptorNum = tmp[0];
        var DeCSLabelText = $(this).find('label').first().text();
        var DeCSArr = DeCSLabelText.split(', ');
        if (!(SelectedDescriptors[DescriptorNum])) {
            SelectedDescriptors[DescriptorNum] = [];
        }
        SelectedDescriptors[DescriptorNum].push(DeCSArr);
    });
    var SelectedDescriptors = SelectedDescriptors.filter(function (item) {
        return item !== null;
    });
    return SelectedDescriptors;
}



function eventQueryBuild(PICOnum, query, SelectedDescriptors, ImproveSearch) {
    var url = getBaseURL() + "ControllerEventQueryBuild.php";
    var data = {
        PICOnum: PICOnum,
        query: query,
        SelectedDescriptors: SelectedDescriptors,
        ImproveSearch: ImproveSearch
    };
    POSTrequest(url, data, function (Data) {
        BuildImprovedQuery(PICOnum, Data);
    });
}

function BuildImprovedQuery(PICOnum, data) {
    var newQuery = data.newQuery;
    $('#datainput' + PICOnum).val(newQuery);
}

function getResultsNumber(id) {
    if (id < 5) {
        var cont = $('#datainput' + id).val();
        if (cont.length == 0) {
            showInfoMessage('Info', MessageCode(24), false);
            return;
        }
    }else{
        var newid = 0;
        for (var loop_i = 5; loop_i >= 1; loop_i--) {
            if ($('#datainput' + loop_i).val().length > 0) {
                newid = loop_i;
                break;
            }
        }
        if (newid == 0) {
            showInfoMessage('Info', MessageCode(25), false);
            return;
        }
    }
    var resultsData = getAllInputFields();
    eventResultsNumber(id, resultsData);
}


function eventResultsNumber(PICOnum, resultsData) {
    var url = getBaseURL() + "ControllerEventResultsNumber.php";
    var data = {
        PICOnum: PICOnum,
        resultsData: resultsData
    };
    POSTrequest(url, data, function (Data) {
        setResultsNumber(Data, PICOnum);
        showInfoMessage('Success', MessageCode(31) + getPICOElements()[PICOnum - 1], false);
    });
}

function setResultsNumber(data,PICOnum) {
    var spanObj;
    hideBootstrapObj($('#CalcRes' + PICOnum));
    setCalcResAsSyncAlt(PICOnum);


    if (PICOnum< 5) {
        spanObj = $('#ResNumLocal' + PICOnum).find('span').first();

        showBootstrapObj(spanObj.parent());
        RemoveReDoButton(spanObj);
        if (data.local.ResultsNumber == 0) {
            addIconZeroResults(spanObj);
        } else {
            $(spanObj).text(data.local.ResultsNumber);
        }
        $(spanObj).attr('data-oldval', data.local.ResultsNumber);
        $('#ResNumLocal' + PICOnum).attr("href", data.local.ResultsURL);
    }
    if (PICOnum> 1 && PICOnum!== 5) {
        spanObj = $('#ResNumGlobal' + PICOnum).find('span').first();
        showBootstrapObj(spanObj.parent());
        RemoveReDoButton(spanObj);
        if (data.global.ResultsNumber == 0) {
            addIconZeroResults(spanObj);
        } else {
            $(spanObj).text(data.global.ResultsNumber);
            if(PICOnum==6){
                showBootstrapObj(spanObj);
            }
        }
        $(spanObj).attr('data-oldval', data.global.ResultsNumber);
        $('#ResNumGlobal' + PICOnum).attr("href", data.global.ResultsURL);
    }
    if (PICOnum=== 6) {
        $('#FinalSearchDetails').val(data.global.ResultsURL);
        $('#FinalSearchDetails').attr('data-oldval', data.global.ResultsURL);
    }
    var loop_i;
    var max = PICOnum;
    if (max > 4) {
        max = 4;
    }
    if (PICOnum=== 6) {
        PICOnum= 5;
    }
    var oldval = $('#datainput' +PICOnum).val();
    $('#datainput' + PICOnum).attr('data-oldval', oldval);
    if (PICOnum< 4) {
        FieldListSetoldval(PICOnum);
    }
}

function getFieldListOptionNum(PICOnum) {
    var res = $('#FieldList' + PICOnum).children("option:selected").index();
    return res;
}

function getAllInputFields() {
    var results = {};
    var loop_i;
    for (loop_i = 1; loop_i < 6; loop_i++) {
        var valx = $('#datainput' + loop_i).val();
        var fieldx = getFieldListOptionNum(loop_i);
        var obj = {
            query: valx,
            field: fieldx
        };
        results['PICO' + loop_i] = obj;
    }
    return results;
}

function showDeCSMenu() {
    $('#modal').modal('show');
}

function showLoading() {
    $('#modal4').modal('show');
}

function wasLoadingCancelled() {
    return !($('#modal4').hasClass('show'));
}

function hideLoading() {
    var Interv = setInterval(function () {
        $('#modal4').focus();
        $('#modal4').modal('toggle');
        $('#modal4').modal('hide');
        if (!($('#modal4').hasClass('show'))) {
            clearInterval(Interv);
        }
    }, 500);
}


function CancelLoading() {
    currentrequest.abort();
}


function showInfoMessage(type, textcontent, asHTML, ActivateClassName = '', callback, cbparams) {
    $('#modalinfo').modal('show');
    var MessageTitles = getMessageTitles();
    var icon = $('#modalinfo').find('.iconElement').first();
    var info = $('#modalinfo').find('.infoElement').first().find('span').first();
    switch (type) {
        case 'Error':
            $(icon).removeClass('info-success');
            $(icon).removeClass('info-warning');
            $(icon).removeClass('info-info');
            $(icon).removeClass('info-config');
            if (!($(icon).hasClass('info-error'))) {
                $(icon).addClass('info-error');
            }
            $(icon).find('span').first().text('x')
            $(info).text(MessageTitles[0]);
            break;
        case 'Warning':
            $(icon).removeClass('info-success');
            $(icon).removeClass('info-error');
            $(icon).removeClass('info-info');
            $(icon).removeClass('info-config');
            if (!($(icon).hasClass('info-warning'))) {
                $(icon).addClass('info-warning');
            }
            $(icon).find('span').first().text('!')
            $(info).text(MessageTitles[1]);
            break;
        case 'Success':
            $(icon).removeClass('info-warning');
            $(icon).removeClass('info-error');
            $(icon).removeClass('info-config');
            $(icon).removeClass('info-info');
            if (!($(icon).hasClass('info-success'))) {
                $(icon).addClass('info-success');
            }
            $(icon).find('span').first().text('✓')
            $(info).text(MessageTitles[2]);
            break;
        case 'Config':
            $(icon).removeClass('info-warning');
            $(icon).removeClass('info-error');
            $(icon).removeClass('info-info');
            $(icon).removeClass('info-success');
            if (!($(icon).hasClass('info-config'))) {
                $(icon).addClass('info-config');
            }
            $(icon).find('span').first().html('<i class="fas fa-cog"></i>')
            $(info).text(MessageTitles[4]);
            break;
        default:
            $(icon).removeClass('info-success');
            $(icon).removeClass('info-error');
            $(icon).removeClass('info-warning');
            $(icon).removeClass('info-config');
            if (!($(icon).hasClass('info-info'))) {
                $(icon).addClass('info-info');
            }
            $(icon).find('span').first().text('i')
            $(info).text(MessageTitles[3]);
            break;
    }
    if (asHTML == true) {
        $('#modalinfo').find('.btn-primary').first().addClass(ActivateClassName)
        $('#modalinfo').find('.InfoText').first().html(textcontent);
    } else {
        $('#modalinfo').find('.InfoText').first().text(textcontent);
    }
    if (isFunction(callback)) {
        callback(cbparams);
}
}

function isFunction(functionToCheck) {
    return functionToCheck && {}.toString.call(functionToCheck) === '[object Function]';
} //author: Alex Grande @ StackOverFlow

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

function SetFinalSearchDetails(Equation) {
    $('#FinalSearchDetails').val(Equation);
}

function ConsoleErr(RequestData, ObtainedData) {
    var debug = true;
    var msg = 'Sent JSON request: </br>' + JSON.stringify(RequestData) + ' </br></br></br> Received Data: </br> ' + ObtainedData;
    if (debug === false) {
        msg = msg.replace("</br>", "\n");
        console.log(msg);
    } else {
        var tab = window.open('about:blank', '_blank');
        tab.document.write(msg); // where 'html' is a variable containing your HTML
        tab.document.close();
    }
}


function OpenInNewTab(url) {
    window.open(url, "_blank");
}

function SetAllToReDoButton() {
    $(document).find('a[id^=ResNum]').each(function () {
        objSpan = $(this).find('span').first();
        var num = ($(this).attr('id')).substr(-1);
        if (num == 6) {

        } else {
            SetToReDoButton(objSpan);
            hideBootstrapObj($(this));
        }
    });
}

function hasReDoButton(objSpan) {
    return (objSpan).hasClass('fa-redo');
}

function SetToReDoButton(objSpan) {
    if (!(hasReDoButton(objSpan))) {
        $(objSpan).addClass('fas fa-redo');
        $(objSpan).text(' ');
    }
}

function RemoveReDoButton(objSpan) {
    $(objSpan).removeClass('fas fa-redo');
}


function objSpanSetOldResult(objSpan) {
    objSpan.text(objSpan.attr('data-oldval'));
}

function objResNumSetOldResult(objResNum) {
    objResNum.attr('href', (objResNum.attr('data-oldval')));
}

function objResNumHasoldval(PICOnum) {
    if ($('#ResNumLocal' + PICOnum).attr('data-oldval')) {
        return true;
    }
    return false;
}


function ChangeSeeker(PICOnum) {
    var LocalObj = $('#ResNumLocal' + PICOnum);
    var LocalSpan = LocalObj.find('span').first();
    var LocalField = $('#FieldList' + PICOnum);

    ShowExplodeButton(PICOnum);
    var ThishasReDo = hasReDoButton(LocalSpan);
    if (CompareNewOld(PICOnum)) {
        ReturnToOldState(PICOnum, LocalObj, LocalSpan, LocalField, ThishasReDo);
    } else {
        ChangeToMustUpdate(PICOnum, LocalObj, LocalSpan, LocalField, ThishasReDo);
    }
}

function ShowExplodeButton(PICOnum) {
    var explodebutton = $('#Exp' + PICOnum);
    if ($('#datainput' + PICOnum).val().length > 0) {
        showBootstrapObj(explodebutton);
        if (objResNumHasoldval(PICOnum)) {
            showBootstrapObj($('#ResNumLocal' + PICOnum));
            showBootstrapObj($('#ResNumGloal' + PICOnum));
            setCalcResAsSyncAlt(PICOnum);
        }
    } else {
        setCalcResAsResult(PICOnum);
        hideBootstrapObj($('#ResNumLocal' + PICOnum));
        hideBootstrapObj($('#ResNumGloal' + PICOnum));
        hideBootstrapObj(explodebutton);
    }
}

function FieldListSetoldval(PICOnum) {
    var objFieldList = $('#FieldList' + PICOnum);
    objFieldList.attr('data-oldval', getFieldListOptionNum(PICOnum));
}


function objResNumSetMustUpdate(objResNum) {
    objResNum.attr('data-oldval', (objResNum.attr('href')));
    objResNum.removeAttr('href');
}

function ReturnToOldState(PICOnum, LocalObj, LocalSpan, LocalField, ThishasReDo) {
    if (!(ThishasReDo) && PICOnum != 5) {
        return;
    }
    objResNumSetOldResult(LocalObj);
    objSpanSetOldResult(LocalSpan);
    RemoveReDoButton(LocalSpan);
    var loop_i;
    for (loop_i = 1; loop_i <= 6; loop_i++) {
        if (loop_i < 6) {
            if (!(CompareNewOld(loop_i))) {
                break;
            }
        }
        var GlobalObj = $('#ResNumGlobal' + loop_i);
        var GlobalSpan = GlobalObj.find('span').first();
        objResNumSetOldResult(GlobalObj);
        objSpanSetOldResult(GlobalSpan);
        RemoveReDoButton(GlobalSpan);
        if (loop_i < 6) {
            if ($('#datainput' + loop_i).val().length > 0) {
                hideBootstrapObj($('#CalcRes' + loop_i));
            }
        }

    }
    $('#FinalSearchDetails').val($('#FinalSearchDetails').attr('data-oldval'));
}

function ChangeToMustUpdate(PICOnum, LocalObj, LocalSpan, LocalField, ThishasReDo) {
    if (ThishasReDo) {
        return;
    }
    objResNumSetMustUpdate(LocalObj);
    SetToReDoButton(LocalSpan);
    var loop_i;
    for (loop_i = PICOnum; loop_i <= 6; loop_i++) {
        var GlobalObj = $('#ResNumGlobal' + loop_i);
        var GlobalSpan = GlobalObj.find('span').first();
        objResNumSetMustUpdate(GlobalObj);
        SetToReDoButton(GlobalSpan);
        if (loop_i == 6) {
            if (!(isHiddenBootstrapObj(GlobalSpan))) {
                showBootstrapObj($('#CalcRes' + loop_i));
            }
        } else {
            showBootstrapObj($('#CalcRes' + loop_i));
        }

    }
    $('#FinalSearchDetails').attr('data-oldval', $('#FinalSearchDetails').val());
    $('#FinalSearchDetails').val(MessageCode(23));
}


function CompareNewOld(PICOnum) {
    var obj = $('#datainput' + PICOnum);
    var newval = $(obj).val();
    var oldval = $(obj).attr('data-oldval');
    var isEqual = true;
    if (!(newval == oldval)) {
        isEqual = false;
    }
    if (PICOnum < 5) {
        var field = $('#FieldList' + PICOnum);
        var newfield = getFieldListOptionNum(PICOnum);
        var oldfield = $(field).attr('data-oldval');
        if (!(newfield == oldfield)) {
            isEqual = false;
        }
    }
    return isEqual;
}

function isHiddenBootstrapObj(Obj) {
    return $(Obj).hasClass('d-none');
}

function showBootstrapObj(Obj) {
    if (isHiddenBootstrapObj(Obj)) {
        $(Obj).removeClass('d-none');
    }
}

function hideBootstrapObj(Obj) {
    if (!(isHiddenBootstrapObj(Obj))) {
        $(Obj).addClass('d-none');
    }
}