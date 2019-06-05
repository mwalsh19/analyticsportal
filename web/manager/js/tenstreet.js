var oTable, loading = $('#loading');


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
        oTable.fnDestroy();
    }

    oTable = $("#table").DataTable({
        "iDisplayLength": -1,
        "aaSorting": [],
        "bLengthChange": false,
        "sAjaxSource": "get-data-source?item_selected=" + itemSelected,
        "fnPreDrawCallback": function (oSettings) {
            colsTotals = [];
        },
        "fnDrawCallback": function (oSettings) {
            var total = 0;
            $('#totals-row th').each(function (index, ele) {
                if (index > 0 && index < 9) {
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
                if (index > 0 && index < 9) {
                    var n = intVal(aData[index]);
                    total += n;
                    if (colsTotals[index] === undefined) {
                        colsTotals[index] = n;
                    } else {
                        colsTotals[index] += n;
                    }
                }
            });
        }
    });


    $.getJSON("/tenstreet/get-grand-total", {item_selected: itemSelected}, function (response) {
        $('#grand-total').text(response.total);
        loading.hide();
    });

}

$('select[name=data-source-select]').on('change', function () {
    var itemSelected = $(this).find('option:selected').val();
    if (itemSelected) {
        parseDataSource(itemSelected);
    }
});



