import {getFieldListOptionNum,setnewQuery} from "./datadictionary";
import {translate} from "./translator.js";
import {POSTrequest} from "./loadingrequest.js";
import {showInfoMessage} from "./infomessage.js";
import {UpdateLocalAfterResults,UpdateGlobalAfterResults} from "./changeseeker";

////PUBLIC FUNCTIONS

export function getResultsNumber(id) {
    if (id < 5) {
        let cont = $('#datainput' + id).val();
        if (cont.length === 0) {
            showInfoMessage('Info', translate('allemptyq'), false);
            return;
        }
    } else {
        let newid = 0;
        for (let loop_i = 5; loop_i >= 1; loop_i--) {
            if ($('#datainput' + loop_i).val().length > 0) {
                newid = loop_i;
                break;
            }
        }
        if (newid === 0) {
            showInfoMessage('Info', translate('numresupd'), false);
            return;
        }
    }
    let queryobject = getAllInputFields();
    eventResultsNumber(id, queryobject);
}

////PRIVATE FUNCTIONS

function getAllInputFields() {
    let results = [];
    let loop_i;
    for (loop_i = 1; loop_i < 6; loop_i++) {
        let valx = $('#datainput' + loop_i).val();
        let fieldx = getFieldListOptionNum(loop_i);
        let obj= {
            query: valx,
            field: fieldx
        };
        results.push(obj);
    }
    return results;
}

function setResultsNumber(data, PICOnum) {
    setnewQuery(PICOnum,data.NewEquation);
    if(PICOnum>1){
        let globalresultsnumber = data.Results.global.ResultsNumber;
        let globalresultsurl = data.Results.global.ResultsURL;
        let globaltitle = data.GlobalTitle;
        UpdateGlobalAfterResults(PICOnum,globalresultsnumber,globalresultsurl,globaltitle);
    }
    if(PICOnum<6){
        let localresultsnumber = data.Results.local.ResultsNumber;
        let localresultsurl = data.Results.local.ResultsURL;
        UpdateLocalAfterResults(PICOnum,localresultsnumber,localresultsurl);
    }
}

function eventResultsNumber(PICOnum, queryobject) {
    let url = "PICO/ResultsNumber";
    let data = {
        PICOnum: PICOnum,
        queryobject: queryobject
    };
    POSTrequest(url, data, function (Data) {
        setResultsNumber(Data, PICOnum);
    });
}

