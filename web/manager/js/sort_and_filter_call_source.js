var oTable = $("table").DataTable({
    responsive: true,
    dom: 'Bfrtip',
    buttons: [
    ]
}),
        loading = $('#loading'),
        filename = $('.filename').text();

function parseDataSource(itemSelected)
{
    loading.show();
    var intVal = function (i) {
        return typeof i === 'string' ?
                i.replace(/[\$,]/g, '') * 1 :
                typeof i === 'number' ?
                i : 0;
    }, colsTotals = [];

    if (oTable) {
        colsTotals = [];
        oTable.destroy();
    }

    oTable = $("table").DataTable({
        "sAjaxSource": "get-call-source?item_selected=" + itemSelected,
        "fnPreDrawCallback": function (oSettings) {
            colsTotals = [];
        },
        "fnDrawCallback": function (oSettings) {
            var total = 0;
            $('tfoot .totals-row th').each(function (index, ele) {
                if (index > 1 && index < 3) {
                    var n = colsTotals[index];
                    $(ele).text(n);
                    total += n;
                }
            });
            $('thead .totals-row th').each(function (index, ele) {
                if (index > 1 && index < 3) {
                    var n = colsTotals[index];
                    $(ele).text(n);
                    total += n;
                }
            });
        },
        "fnRowCallback": function (nRow, aData, iDisplayIndex, iDisplayIndexFull) {
            //
            var total = 0;
            $(nRow).find('td').each(function (index, ele) {
                if (index > 1 && index < 3) {
                    var n = intVal(aData[index]);
                    total += n;
                    if (colsTotals[index] === undefined) {
                        colsTotals[index] = n;
                    } else {
                        colsTotals[index] += n;
                    }
                }
            });
        },
        "iDisplayLength": -1,
        "bLengthChange": false,
        "paging": false,
        "info": false,
        "bFooter": true,
        responsive: true,
        dom: 'Bfrtip',
        buttons: [
            {
                extend: 'excelHtml5',
                title: filename,
                header: true,
                footer: true,
                modifier: {
                    search: 'applied',
                    order: 'applied'
                },
                exportOptions: {
                    columns: ':visible'
                }
            },
            'colvis'
        ]

    });
    loading.hide();
//    $.getJSON("/tenstreet/get-grand-total", {item_selected: itemSelected}, function (response) {
//        $('.grand-total').text(response.total);
//        loading.hide();
//    });
}

$('select[name="call-source-select"]').on('change', function () {
    var itemSelected = $(this).find('option:selected').val();
    if (itemSelected) {
        parseDataSource(itemSelected);
    }
});



