import {initEvents} from "./init.js";
import {debugfunctions} from "./debug.js";

$(function () {
    initialize();
});
function checkDivLoaded() {
    if ($('#footer').length == 0) {
        $jquery.error('not ready');
    }else{
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
    debugfunctions();
    initEvents();
}
