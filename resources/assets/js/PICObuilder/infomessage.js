import {translate} from "./translator.js";

////PUBLIC FUNCTIONS

export function showInfoMessage(type, textcontent, asHTML, ActivateClassName, callback, cbparams,preventEscape) {
    if (ActivateClassName === undefined) {
        ActivateClassName = '';
    }
    let modalinfoObj = $('#modalinfo');
    if(preventEscape){
        modalinfoObj.modal({
            show: true,
            keyboard: false,
            backdrop: 'static'
        });
    }else{
        modalinfoObj.modal('show');
    }
    let MessageTitles = getMessageTitles();
    let icon = modalinfoObj.find('.iconElement').first();
    let info = modalinfoObj.find('.infoElement').first().find('span').first();
    switch (type) {
        case 'Error':
            $(icon).removeClass('info-success');
            $(icon).removeClass('info-warning');
            $(icon).removeClass('info-info');
            $(icon).removeClass('info-config');
            if (!($(icon).hasClass('info-error'))) {
                $(icon).addClass('info-error');
            }
            $(icon).find('span').first().text('x');
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
            $(icon).find('span').first().text('!');
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
            $(icon).find('span').first().text('✓');
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
            $(icon).find('span').first().html('<i class="fas fa-cog"></i>');
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
            $(icon).find('span').first().text('i');
            $(info).text(MessageTitles[3]);
            break;
    }
    if (asHTML === true) {
        modalinfoObj.find('.btn-primary').first().addClass(ActivateClassName);
        modalinfoObj.find('.InfoText').first().html(textcontent);
    } else {
        modalinfoObj.find('.InfoText').first().text(textcontent);
    }
    if (isFunction(callback)) {
        callback(cbparams);
    }
}

////PRIVATE FUNCTIONS

function isFunction(functionToCheck) {
    return functionToCheck && {}.toString.call(functionToCheck) === '[object Function]';
}

function getMessageTitles() {
    return ['ERROR', translate('warn'), translate('succ'), translate('info'), translate('conf'), translate('errcon')];
}
