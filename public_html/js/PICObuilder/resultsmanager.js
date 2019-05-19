function addIconZeroResults(obj) {
    var iconHTML = '0 <a class="PICOiconzeroElement"><span>?</span></a>';
    $(obj).html(iconHTML);
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

function getResultsNumber(id) {
    if (id < 5) {
        var cont = $('#datainput' + id).val();
        if (cont.length == 0) {
            showInfoMessage('Info', MessageCode(24), false);
            return;
        }
    } else {
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
    var url = "ControllerEventResultsNumber.php";
    var data = {
        PICOnum: PICOnum,
        resultsData: resultsData
    };
    POSTrequest(url, data, function (Data) {
        setResultsNumber(Data, PICOnum);
        showInfoMessage('Success', MessageCode(31) + getPICOElements()[PICOnum - 1], false);
    });
}

function setResultsNumber(data, PICOnum) {
    var spanObj;
    hideBootstrapObj($('#CalcRes' + PICOnum));
    setCalcResAsSyncAlt(PICOnum);
    if (PICOnum < 5) {
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
    if (PICOnum > 1 && PICOnum !== 5) {
        spanObj = $('#ResNumGlobal' + PICOnum).find('span').first();
        showBootstrapObj(spanObj.parent());
        RemoveReDoButton(spanObj);
        if (data.global.ResultsNumber == 0) {
            addIconZeroResults(spanObj);
        } else {
            $(spanObj).text(data.global.ResultsNumber);
            if (PICOnum == 6) {
                showBootstrapObj(spanObj);
            }
        }
        $(spanObj).attr('data-oldval', data.global.ResultsNumber);
        $('#ResNumGlobal' + PICOnum).attr("href", data.global.ResultsURL);
    }
    if (PICOnum === 6) {
        $('#FinalSearchDetails').val(data.global.ResultsURL);
        $('#FinalSearchDetails').attr('data-oldval', data.global.ResultsURL);
    }
    var loop_i;
    var max = PICOnum;
    if (max > 4) {
        max = 4;
    }

    var oldvalJSON = getGlobalSpanJSON(PICOnum);
    $('#datainput' + PICOnum).attr('data-oldval', oldvalJSON);
    if (PICOnum === 6) {
        PICOnum = 5;
    }

    if (PICOnum < 5) {
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

function SetFinalSearchDetails(Equation) {
    $('#FinalSearchDetails').val(Equation);
}
