function debugfunctions() {
    $(document).find('#debugEventInfo').click(function () {
        OpenInNewTab(getBaseURL() + 'var/log/log/');
    });
    $(document).find('#debugErrorInfo').click(function () {
        OpenInNewTab(getBaseURL() + 'var/log/error/');
    });
};

function OpenInNewTab(url) {
    window.open(url, "_blank");
}
