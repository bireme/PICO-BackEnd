import {showInfoMessage} from "./infomessage.js";
import {getBaseURL} from "./baseurl.js";
import {translate} from "./translator.js";

////PUBLIC FUNCTIONS

export function IsLoading() {
    let loadingObj =$('#loading');
    let WasLoading =loadingObj.attr('isLoading');
    loadingObj.attr('isLoading',true);
    return WasLoading;
}

export function CancelLoading() {
    hideLoading();
    setAsNotLoading();
    abortrequest();
}

export function POSTrequest(url, data, callback) {
    showLoading();
    data.mainLanguage= getMainLanguage();
    url = getBaseURL() + url;
    let sendData = {
        data: JSON.stringify(data)
    };
    currentrequest = $.post(url, sendData,
            function (obtainedData) {
                try {
                    let outerData = JSON.parse(obtainedData);
                    let Err = outerData.Error;
                    let War = outerData.Warning;
                    if (Err) {
                        hideLoading();
                        setTimeout(function () {
                            ConsoleErr(data, obtainedData);
                            showInfoMessage('Error', Err, false);
                        }, 300);
                        return;
                    }
                    if (War) {
                        hideLoading();
                        setTimeout(function () {
                            ConsoleErr(data, obtainedData);
                            showInfoMessage('Warning', War, false);
                        }, 300);
                        return;
                    }
                    let Data = outerData.Data;
                    if (!(Data)) {
                        hideLoading();
                        setTimeout(function () {
                            ConsoleErr(data, obtainedData);
                            showInfoMessage('Error', translate('popallow'), false);
                        }, 300);
                        return;
                    }
                } catch (Exception) {
                    hideLoading();
                    setTimeout(function () {
                        ConsoleErr(data, obtainedData);
                        showInfoMessage('Error', translate('popallow'), false);
                    }, 300);
                    return;
                }
                hideLoading();
                setTimeout(function () {
                    callback(Data);
                }, 300);
            }).fail(function (xhr, status, error) {
        hideLoading();
        if (xhr.statusText !== 'abort') {
            setTimeout(function () {
                showInfoMessage('Error', translate('errunknown'), false);
            }, 300);
        }
    });
}

////PRIVATE FUNCTIONS

let currentrequest;

function showLoading() {
    $('#modal4').modal({
        show: true,
        keyboard: false,
        backdrop: 'static'
});
}

function setAsNotLoading() {
    $('#loading').attr('isLoading',false);
}

function hideLoading() {
    let Interv = setInterval(function () {
        let loadingModal = $('#modal4');
        loadingModal.focus();
        loadingModal.modal('toggle');
        loadingModal.modal('hide');
        setAsNotLoading();
        if (!(loadingModal.hasClass('show'))) {
            clearInterval(Interv);
        }
    }, 500);
}

function ConsoleErr(RequestData, ObtainedData) {
    let debug = true;
    let msg = 'Sent JSON request: </br>' + JSON.stringify(RequestData) + ' </br></br></br> Received Data: </br> ' + JSON.stringify(ObtainedData);
    if (debug === false) {
        msg = msg.replace("</br>", "\n");
        console.log(msg);
    } else {
        let tab = window.open('about:blank', '_blank');
        tab.document.write(msg); // where 'html' is a variable containing your HTML
        tab.document.close();
    }
}
function abortrequest() {
currentrequest.abort();    
}

function getMainLanguage() {
    return $('html').attr('lang');
}
