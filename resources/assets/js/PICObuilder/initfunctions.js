import {setLanguagesOfModal} from "./decslanguages.js";
import {ChangeSeeker} from "./changeseeker.js";
import {getPICOElements} from "./commons.js";
import {getLanguages} from "./commonsdecs.js";
import {translate} from "./translator.js";
import {showInfoMessage} from "./infomessage.js";
import {showBootstrapObj, hideBootstrapObj} from "./hideshow.js";


////PUBLIC FUNCTIONS

export function ExpandDeCSConfig() {
    let msg = '<div id="LanguageSection" class="container "><div class="row"><div class="col-md-7 sidebar LanguageContainer"><label class="labelMain">';
    msg = msg + translate("langimp") + '</label>\
        </div>\
        <div class="col-md-5 sidebar LanguageContainer text-left">\
           <div class="LanguageInfoContainer">\
                    <div class="CheckBoxRow" ><input type="checkbox" class="langCheck" name="Langs[]" value="en" checked /><label>English</label></div>\
                    <div class="CheckBoxRow" ><input type="checkbox" class="langCheck" name="Langs[]" value="pt" /><label>Spanish</label></div>\
                    <div class="CheckBoxRow" ><input type="checkbox" class="langCheck" name="Langs[]" value="es" /><label>Portuguese</label></div>\
            </div>\
        </div>\
    </div>\
    </div>';
    let langs = getLanguages();
    showInfoMessage('Config', msg, true, 'golanguage', setLanguagesOfModal, langs, true);
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
    if (PICOnum < 5) {
        msg = '<h2>' + title + '</h2>' + '</br></br><h3>' + msg + '</h3></br></br>' + '<h4>' + getPICOPlaceHolder(PICOnum) + '</h4>';
    }
    showInfoMessage('Info', msg, true);
}

export function ReBuildStudyType() {
    let CheckArr = [];
    $('#collapse5').find('.form-group').each(function () {
        if ($(this).find('input').first().is(':checked')) {
            let txt = $(this).find('label').first().text();
            CheckArr.push(txt);
        }
    });
    let msg = CheckArr.join(' OR ');
    if (msg.length > 0) {
        msg = '(' + msg + ')';
    }
    $('#datainput5').val(msg);
    ChangeSeeker(5);
}


export function setLanguagesFromModal(LangParent) {
    let langs = [];
    $(LangParent).find('.langCheck').each(function () {
        if ($(this).is(':checked')) {
            langs.push($(this).val());
        }
    });
    setLanguages(langs);
}

export function HideUnselectedDeCS() {
    $('#modal1').find('.DescriptorCheckbox').each(function () {
        let tmp = $(this).attr('id').slice(15);
        let tab = $('#decsform' + tmp + '-tab');
        let cont = $('#decsform' + tmp + '-cont');
        let status = $(this).prop("checked");
        if (status === true) {
            showBootstrapObj(tab);
            showBootstrapObj(cont);
            tab.find('.DescriptorCheckbox').each(function () {
                $(this).attr("checked", true);
            });
        } else {
            hideBootstrapObj(tab);
            hideBootstrapObj(cont);
            tab.find('.DescriptorCheckbox').each(function () {
                $(this).attr("checked", false);
            });
        }
    });
}

////PRIVATE FUNCTIONS

function setLanguages(langArr) {
    let count = 0;
    $(document).find('.languageCheckbox').each(function () {
        let index = langArr.indexOf($(this).val());
        if (index > -1) {
            $(this).attr('checked', true);
        } else {
            $(this).attr('checked', false);
        }
        count++;
    });
}

function getPICOinfo() {
    return [translate('pico_info1'), translate('pico_info2'), translate('pico_info3'), translate('pico_info4'), translate('pico_info5')];
}

function getPICOPlaceHolder(PICOnum) {
    let PHarr = [translate('pico_ex1'),
        translate('pico_ex2'),
        translate('pico_ex3'),
        translate('pico_ex4')
    ];
    if (PICOnum < 5) {
        return translate('keyas') + PHarr[PICOnum - 1];
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
