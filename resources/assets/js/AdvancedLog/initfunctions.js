export function TableLogSave() {
    $('#table-log').DataTable({
        "order": [$('#table-log').data('orderingIndex'), 'desc'],
        "stateSave": true,
        "stateSaveCallback": function (settings, data) {
            window.localStorage.setItem("datatable", JSON.stringify(data));
        },
        "stateLoadCallback": function (settings) {
            var data = JSON.parse(window.localStorage.getItem("datatable"));
            if (data) data.start = 0;
            return data;
        }
    });
}

export function RedirectToExploredd(ExploreDDObj) {
    let name = $(ExploreDDObj).attr('name');
    let data = $(ExploreDDObj).attr('data-content')
    let datadd = {};
    datadd[name]=data;
    let sendData=JSON.stringify(datadd);
    console.log('Sending to server..' + sendData);
    $.ajax({
        type: "POST",
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
        },
        url: 'dd',
        data: sendData,
        error: function (xhr, status, error) {
            console.error(status + ': ' + error);
            alert('Error in operation');
        },
        success: function (response) {
            window.open('dd', "_blank");
        }
    });
}
