import {initEvents} from "./init.js";

$(function () {
    initialize();
});
function checkDivLoaded() {
    if ($('#footer').length == 0) {
        console.log('not ready');
    }else{
        console.log('starting');
        startfunctions();
    }
}
function initialize() {
    try {
        checkDivLoaded();
        //...more code here
    } catch (err) {
        setTimeout(initialize, 200);
    }
}

function startfunctions() {
    initEvents();
}
