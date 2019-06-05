$(function () {
    var filename = $('.filename').text(),
            loadingDataSources = $('.table-loading #loading');

    $('table').DataTable({
        dom: 'Bfrtip',
        buttons: [
            {
                extend: 'excelHtml5',
                title: filename,
                header: true,
                modifier: {
                    search: 'applied',
                    order: 'applied'
                },
                exportOptions: {
                    columns: ':visible'
                }
            },
            'colvis'
        ],
        "iDisplayLength": 100,
        "bServerSide": false,
        "bDeferRender": true,
        responsive: true,
        "fnDrawCallback": function (oSettings, json) {
            loadingDataSources.hide();
        }
    });

});
