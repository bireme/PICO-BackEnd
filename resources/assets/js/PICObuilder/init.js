import {BlockButton,UnBlockButton,ShowPICOinfo,setLanguagesFromModal,ReBuildStudyType,CheckExistantHREF,InfoNoResults,HideUnselectedDeCS,ExpandDeCSConfig} from "./initfunctions.js";
import {UpdateLanguageInfo} from "./languagetoggler.js";
import {ProcessResults} from "./newquerybuild.js";
import {getResultsNumber} from "./resultsmanager.js";
import {OnExpandDeCS} from "./decsmanager.js";
import {SetAllToReDoButton, ChangeSeeker} from "./changeseeker.js";
import {IsLoading, CancelLoading} from "./loadingrequest.js";
import {isHiddenBootstrapObj} from "./hideshow.js";
import {AccordionAndTooltip} from "./AccordionTooltip.js";
import {ChangeLocale} from "./localepreservedata.js";

////PUBLIC FUNCTIONS

export function initEvents() {
    $('button.ExpandDeCS').click(function (e) {
        BlockButton($(this));
        if (IsLoading()) {
            UnBlockButton($(this));
            return;
        }
        if ($(this).find('.startlanguage').is(":hover")) {
            e.preventDefault();
            ExpandDeCSConfig();
            UnBlockButton($(this));
            return;
        }
        OnExpandDeCS($(this));
        UnBlockButton($(this));
    });
    $('#modal1').find('.btn-primary').click(function () {
        BlockButton($(this));
        HideUnselectedDeCS();
        $('#closemodal1').click();
        $('#modal2').modal('show');
        UnBlockButton($(this));
    });
    $('#modal2').find('.btn-primary').click(function () {
        BlockButton($(this));
        $('#closemodal2').click();
        $('#modal3').modal('show');
        UnBlockButton($(this));
    });
    $('#modal3').find('.btn-primary').click(function () {
        BlockButton($(this));
        ProcessResults();
        $('#closemodal3').click();
        UnBlockButton($(this));
    });
    $(document).find('button[id^=CalcRes]').click(function () {
        BlockButton($(this));
        if (IsLoading()) {
            UnBlockButton($(this));
            return;
        }
        let PICOnum = ($(this).attr('id')).substr(-1);
        getResultsNumber(PICOnum);
        UnBlockButton($(this));
    });
    $(document).find('button[id^= page-lang]').click(function () {
        BlockButton($(this));
        let locale = $(this).attr('name');
        ChangeLocale(locale);
        UnBlockButton($(this));
    });
    $(document).find('#modal4').find('.btn-info').click(function () {
        BlockButton($(this));
        CancelLoading();
        UnBlockButton($(this));
    });
    $(document).find('a[id^=ResNum]').click(function (e) {
        BlockButton($(this));
        let PICOnum = ($(this).attr('id')).substr(-1);
        if (PICOnum === 6) {
            let obj = $(this).find('span').first();
            if (isHiddenBootstrapObj(obj)) {
                getResultsNumber(6);
                UnBlockButton($(this));
                return;
            }
        }
        if ($(this).find('.PICOiconzeroElement').is(":hover")) {
            e.preventDefault();
            InfoNoResults();
            UnBlockButton($(this));
            return;
        }
        CheckExistantHREF($(this));
        UnBlockButton($(this));
    });
    $(document).find('input[id^=datainput]').on('input', function () {
        ChangeSeeker(($(this).attr('id')).substr(-1));
    });
    $('#collapse5').find('.form-group').find('input').change(function () {
        ReBuildStudyType();
    });
    $(document).find('select[id^=FieldList]').change(function () {
        ChangeSeeker(($(this).attr('id')).substr(-1));
    });
    $(document).find('a[id^=PICOinfo]').click(function (e) {
        BlockButton($(this));
        e.stopPropagation();
        ShowPICOinfo(($(this).attr('id')).substr(-1));
        UnBlockButton($(this));
    });
    $(document).on('click', ".golanguage", function () {
        BlockButton($(this));
        setLanguagesFromModal($(this).parent().parent());
        UnBlockButton($(this));
    });

    $.ajaxSetup({
        beforeSend: function(xhr, type) {
            if (!type.crossDomain) {
                xhr.setRequestHeader('X-CSRF-Token', $('meta[name="csrf-token"]').attr('content'));
            }
        },
    });

    UpdateLanguageInfo();
    SetAllToReDoButton();
    AccordionAndTooltip();
}
