import {getPICOElements} from "./commons.js";
import {translate} from "./translator.js";
import {showInfoMessage} from "./infomessage.js";
import {showBootstrapObj, hideBootstrapObj} from "./hideshow.js";
import {UpdateLanguageInfo} from "./languagetoggler";
import {ChangeSeekerStart} from "./changeseeker";
import {AccordionAndTooltip} from "./AccordionTooltip";
import {getResultsNumber} from "./resultsmanager";


////PUBLIC FUNCTIONS

export function ManageResultsNumber(PICOnum) {
    getResultsNumber(PICOnum);
}

export function StartFunctions() {
    UpdateLanguageInfo();
    ChangeSeekerStart();
    AccordionAndTooltip();
    ReBuildConfButtonText();
}

function ReBuildConfButtonText(){
    let tmp=[];
    $('#LanguageSection').find('.languageCheckbox:checked').each(function(){
        tmp.push($(this).attr('data-lang'));
    });
    let txt = '['+tmp.join(', ')+']';
    let oldval = $('#Exp1').find('.startlanguage').first().text();
    console.log('oldconftextval='+oldval);
    if(oldval!==txt){
        $(document).find('.startlanguage').each(function(){
            $(this).text(txt);
        });
    }
}

export function SkipStepTwo(){
    let selectnum = $('#modal1').find('.DescriptorCheckbox:checked').length;
    if (selectnum === 0) {
        return true;
    }
    return false;
}

export function ChangeDeCSLanguages() {
    let selectnum = $('#LanguageSection').find('.languageCheckbox:checked').length;
    console.log('langs selected = '+selectnum);
    if (selectnum === 0) {
        $('#languagenumberalert').removeClass('d-none');
        setTimeout(function () {
            $('#languagenumberalert').addClass('d-none');
        }, 3000);
    } else {
        ReBuildConfButtonText();
        $('#closemodallanguage').click();
    }
}

export function InfoNoResults() {
    showInfoMessage('Info', translate('zerores'), false);
}

export function CheckExistantHREF(Obj) {
    if (!($(Obj).attr('href'))) {
        if (!($(Obj).attr('data-oldval'))) {
            showInfoMessage('Info', translate('mustrecalc'), false);
        } else {
            showInfoMessage('Info', translate('pleaseupd'), false);
        }
    } else {
        let win = window.open((Obj).attr('href'), '_blank');
        if (win) {
            win.focus();
        } else {
            showInfoMessage('Error', translate('mustcalc'), false);
        }
    }
}

export function ShowPICOinfo(PICOnum) {
    let title = getPICOElements()[PICOnum - 1];
    let msg = getPICOinfo()[PICOnum - 1];
    let secondary = null;
    if(PICOnum<5) {
        let example = getPICOPlaceHolder(PICOnum);
        let placeholder = getPICOHelpInfo(PICOnum);
        secondary = '</br></br>' + example + '</br>' + placeholder;
    }
    showInfoMessage('Info', msg, true, null, null, null, false, title, secondary);
}

export function ReBuildStudyType() {
    let CheckArr = [];
    $('#collapse5').find('.studytypecheckbox:checked').each(function () {
            let txt = $(this).attr('name');
            CheckArr.push('"'+txt+'"');
    });
    let msg = CheckArr.join(' OR ');
    $('#datainput5').val(msg);
}

export function HideUnselectedDeCS() {
    let countvisible=0;
    $('#modal1').find('.DescriptorCheckbox').each(function () {
        let tmp = $(this).attr('id').slice(15);
        let tab = $('#decsform' + tmp + '-tab');
        let cont = $('#decsform' + tmp + '-cont');
        let isactive=false;
        if ($(this).is(':checked')) {
            if(countvisible===0){
                isactive=true;
            }
            showBootstrapObj(tab);
            showBootstrapObj(cont);
            countvisible++;
        } else {
            hideBootstrapObj(tab);
            hideBootstrapObj(cont);
        }
        if(isactive){
            if(!(tab.hasClass('active show'))){
                tab.addClass('active show');
                cont.addClass('active show');
            }
        }else{
            if(tab.hasClass('active show')){
                tab.removeClass('active show');
                cont.removeClass('active show');
            }
        }

    });
}

////PRIVATE FUNCTIONS

function getPICOinfo() {
    return [translate('pico_info1'), translate('pico_info2'), translate('pico_info3'), translate('pico_info4'), translate('pico_info5')];
}


function getPICOHelpInfo(PICOnum) {
    let PHarr = [translate('pico_exinfo1'),
        translate('pico_exinfo2'),
        translate('pico_exinfo3'),
        translate('pico_exinfo4'),
        translate('pico_exinfo5')
    ];
    if (PICOnum < 5) {
        return translate('keyas') + ': ' + PHarr[PICOnum - 1];
    }
}

function getPICOPlaceHolder(PICOnum) {
    let PHarr = [translate('pico_ex1'),
        translate('pico_ex2'),
        translate('pico_ex3'),
        translate('pico_ex4'),
        translate('pico_ex5')
    ];
    if (PICOnum < 5) {
        return 'Ex: ' + PHarr[PICOnum - 1];
    }
}

export function BlockButton(obj) {
    obj.attr('disabled', true);
}

export function UnBlockButton(obj) {
    setTimeout(function () {
        obj.attr('disabled', false);
    }, 300);
}
