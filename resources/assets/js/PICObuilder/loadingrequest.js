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

export function POSTrequest(url, inidata, callback) {
    showLoading();
    inidata.mainLanguage = getMainLanguage();
    url = getBaseURL() + url;
    let sentData=JSON.stringify(inidata);
    console.log('Sending');
    console.log(sentData);
    currentrequest = $.ajax({
        url: url,
        type: 'post',
        data: sentData,
        tryCount : 0,
        retryLimit : 3,
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
        },
        dataType: 'json',
        success: function (content) {
            let result=null;
            try {
                let Err = content.Error;
                let War = content.Warning;
                if (Err) {
                    hideLoading();
                    setTimeout(function () {
                        showInfoMessage('Error', Err, false);
                    }, 300);
                    return;
                }
                if (War) {
                    hideLoading();
                    setTimeout(function () {
                        showInfoMessage('Warning', War, false);
                    }, 300);
                    return;
                }
                result = content.Data;
                if (!(result)) {
                    hideLoading();
                    setTimeout(function () {
                        ConsoleErr(sentData, content);
                        showInfoMessage('Error', translate('popallow'), false);
                    }, 300);
                    return;
                }
                hideLoading();
                setTimeout(function () {
                    callback(result);
                }, 300);
            } catch (Exception) {
                hideLoading();
                let errtxt = Exception.toString();
                setTimeout(function (content) {
                    ConsoleErr(sentData, (errtxt+': '+content));
                    showInfoMessage('Error', translate('popallow'), false);
                }, 300);
            }
        },
        error: function (jqXHR, textStatus ) {
            hideLoading();
            if (textStatus === 'timeout') {
                this.tryCount++;
                if (this.tryCount <= this.retryLimit) {
                    //try again
                    $.ajax(this);
                    return;
                }
                return;
            }
            if (textStatus !== 'abort') {
                setTimeout(function () {
                    showInfoMessage('Error', translate('errunknown'), false);
                }, 300);
            }
        },
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
