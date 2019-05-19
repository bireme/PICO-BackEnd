var currentrequest;

function getBaseURL() {
    var foldername = '/PICO-BackEnd';
    var msg = location.protocol + '//' + location.hostname + foldername + '/';
    return msg;
}

function POSTrequest(url, data, callback) {
    showLoading();
    url = getBaseURL() + url;
    var sendData = {
        data: JSON.stringify(data)
    };
    currentrequest = $.post(url, sendData,
            function (obtainedData) {
                try {
                    var outerData = JSON.parse(obtainedData);
                    var Data = outerData.Data;
                    Data.length;
                    var Err = outerData.Error;
                    var War = outerData.Warning;
                    if (Err) {
                        hideLoading();
                        setTimeout(function () {
                            ConsoleErr(data, obtainedData);
                            showInfoMessage('Error',Err, false);
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
            }).fail(function (xhr, status, error) {
        hideLoading();
        if (xhr.statusText !== 'abort') {
            setTimeout(function () {
                showInfoMessage('Error', MessageCode(1), false);
            }, 300);
        }
    });
}

function CancelLoading() {
    currentrequest.abort();
}

function showLoading() {
    $('#modal4').modal('show');
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
