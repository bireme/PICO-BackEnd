import {getBaseURL} from "./baseurl.js";
////PUBLIC FUNCTIONS

export function debugfunctions() {
    $(document).find('#debugEventInfo').click(function () {
        OpenInNewTab(getBaseURL() + 'public_html/var/log/log/');
    });
    $(document).find('#debugErrorInfo').click(function () {
        OpenInNewTab(getBaseURL() + 'public_html/var/log/error/');
    });
}

////PRIVATE FUNCTIONS

function OpenInNewTab(url) {
    window.open(url, "_blank");
}
