import {TableLogSave,RedirectToExploredd,RedirectToExploreinput} from "./initfunctions.js";

export function initEvents() {
    $(document).find('.expandinfo').on('click', function () {
        let obj = $(this).closest('tr');
        $('#' + obj.data('display')).toggle();
    });
    TableLogSave();
    $('#delete-log, #clean-log, #delete-all-log').click(function () {
        return confirm('Are you sure?');
    });
    $('.exploredd').on('click', function () {
        RedirectToExploredd($(this));
    });
    $('.exploreinput').on('click', function () {
        RedirectToExploreinput($(this));
    });
}
