import {SkipStepTwo,ChangeDeCSLanguages,ManageResultsNumber,StartFunctions,BlockButton,UnBlockButton,ShowPICOinfo,ReBuildStudyType,CheckExistantHREF,InfoNoResults,HideUnselectedDeCS} from "./initfunctions.js";
import {ProcessResults} from "./newquerybuild.js";
import {OnExpandDeCS} from "./decsmanager.js";
import {IsLoading, CancelLoading} from "./loadingrequest.js";
import {isHiddenBootstrapObj} from "./hideshow.js";
import {ChangeLocale} from "./localepreservedata.js";
import {OpenInNewTab} from "./debug.js";
import {ChangeSeekerHandler} from "./changeseeker.js";

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
            $('#modallanguage').modal('show');
            UnBlockButton($(this));
            return;
        }
        OnExpandDeCS($(this));
        UnBlockButton($(this));
    });
    let modal2 =$('#modal2');
    let modal3 =$('#modal3');

    modal2.find('.btn-back').click(function () {
        BlockButton($(this));
        $('#closemodal2').click();
        $('#modal1').modal('show');
        UnBlockButton($(this));
    });
    $(modal3).find('.btn-back').click(function () {
        BlockButton($(this));
        if (IsLoading()) {
            UnBlockButton($(this));
            return;
        }
        $('#closemodal3').click();
        if(SkipStepTwo()===true){
            $('#modal1').modal('show');
        }else{
            $('#modal2').modal('show');
        }
        UnBlockButton($(this));
    });
    $('#modal1').find('.btn-continue').click(function () {
        BlockButton($(this));
        $('#closemodal1').click();
        if(SkipStepTwo()===true){
            $('#modal3').modal('show');
        }else{
            $('#modal2').modal('show');
        }
        HideUnselectedDeCS();
        UnBlockButton($(this));
    });
    $('#modallanguage').find('.btn-continue').click(function () {
        BlockButton($(this));
        ChangeDeCSLanguages();
        UnBlockButton($(this));
    });
    $(modal2).find('.btn-continue').click(function () {
        BlockButton($(this));
        $('#closemodal2').click();
        $('#modal3').modal('show');
        UnBlockButton($(this));
    });
    $(modal3).find('.btn-continue').click(function () {
        BlockButton($(this));
        ProcessResults();
        $('#closemodal3').click();
        UnBlockButton($(this));
    });
    $(document).find('.calcresbut').click(function () {
        BlockButton($(this));
        if (IsLoading()) {
            UnBlockButton($(this));
            return;
        }
        let PICOnum = $(this).attr('data-piconum');
        ManageResultsNumber(PICOnum);
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
    $(document).find('.PICOchangeitem').blur(function() {
        ChangeSeekerHandler($(this).attr('data-pico'));
    });
    $(document).find('.studytypecheckbox').change(function() {
        ChangeSeekerHandler(5);
    });

    $(document).find('#FinalGlobal').click(function () {
        if(isHiddenBootstrapObj($('#finalupdated'))){
            console.log('calculating data');
            BlockButton($(this));
            if (IsLoading()) {
                UnBlockButton($(this));
                return;
            }
            ManageResultsNumber(5);
            UnBlockButton($(this));
        }else{
            //console.log('opening href');
            //BlockButton($(this));
            //if (IsLoading()) {
                //UnBlockButton($(this));
                //return;
                //}
            //OpenInNewTab($(this).attr('data-href'));
            //UnBlockButton($(this));
        }
    });


    $('#collapse5').find('.studytypecheckbox').click(function () {
        ReBuildStudyType();
    });
    $(document).find('a[id^=PICOinfo]').click(function (e) {
        BlockButton($(this));
        e.stopPropagation();
        ShowPICOinfo(($(this).attr('id')).substr(-1));
        UnBlockButton($(this));
    });
    $(document).on('click', ".golanguage", function () {
        BlockButton($(this));
//
        UnBlockButton($(this));
    });

    $.ajaxSetup({
        beforeSend: function(xhr, type) {
            if (!type.crossDomain) {
                xhr.setRequestHeader('X-CSRF-Token', $('meta[name="csrf-token"]').attr('content'));
            }
        },
    });

    StartFunctions();

}
