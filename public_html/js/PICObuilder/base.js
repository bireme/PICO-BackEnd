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

function isFunction(functionToCheck) {
    return functionToCheck && {}.toString.call(functionToCheck) === '[object Function]';
} //author: Alex Grande @ StackOverFlow

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