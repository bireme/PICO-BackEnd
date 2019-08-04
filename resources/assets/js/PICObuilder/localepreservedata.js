import {showInfoMessage} from "./infomessage";
import {getFieldListOptionNum} from "./commonschange.js";
import {translate} from "./translator.js";

////PUBLIC FUNCTIONS

export function ChangeLocale(locale) {
    let olddata = ObtainOldData();
    let datadd = {};
    datadd['olddata']=olddata;
    let sendData=JSON.stringify(datadd);
    console.log('Sending to server..' + olddata);
    $.ajax({
        type: "POST",
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
        },
        url: locale,
        data: sendData,
        error: function (xhr, status, error) {
            FailLanguage(locale, xhr, status, error);
        },
        success: function (response) {
            window.location.href = locale;
        }
    });
}

////PRIVATE FUNCTIONS

function FailLanguage(locale, xhr, status, error) {
    let content = translate('langerr');
    console.error(status + ': ' + error);
    showInfoMessage('Error', content, false)
}

function ObtainOldData() {
    let PICOData = {};
    $(document).find('input[id^=datainput]').each(function () {
        let oldval = $(this).attr('data-oldVal');
        let query = $(this).val();
        let previousdata = $(this).attr('data-previous-decs');
        let querysplit = $(this).attr('data-query-split');
        let PICOnum = ($(this).attr('id')).substr(-1);
        let fieldoldval = $('#FieldList'+PICOnum).attr('data-oldVal');
        let fieldselection = getFieldListOptionNum(PICOnum);
        PICOData[PICOnum] = {
            'oldval': oldval,
            'query': query,
            'previousdata':previousdata,
            'querysplit': querysplit,
            'fieldoldval': fieldoldval,
            'fieldselection': fieldselection
        }
    });

    let TmpCookieElement = $('#TmpCookieElement').val();
    let TOSdata = [];
    $('#collapse5').find('.form-group').each(function () {
        if ($(this).find('input').first().is(':checked')) {
            let txt = $(this).find('label').first().text();
            TOSdata.push(txt);
        }
    });
    let RequestData = {
        PICOData: PICOData,
        TmpCookieElement: TmpCookieElement,
        TOS: TOSdata
    };
    return RequestData;
}
