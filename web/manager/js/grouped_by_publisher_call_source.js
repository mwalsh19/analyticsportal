var loading = $('#loading'),
        downloadBtn = $("#downloader");

function exportData() {
    var tables = $('table'),
            titles = $('.publisher-title'),
            originalData = [],
            headers = [],
            i = 0,
            r = 0,
            f = 0,
            d = 0;

    tables.each(function (key, table) {
        var theadTh = $(table).find('thead tr th'),
                tbodyTr = $(table).find('tbody tr'),
                tfootTh = $(table).find('tfoot tr th');
        headers = [];
        footer = [];

        originalData.push([$(titles[key]).text(), "", "", "", "", "", "", "", "", ""]);

        for (i = 0; i < theadTh.length; i++) {
            headers.push($(theadTh[i]).text());
        }
        if (headers.length) {
            originalData.push(headers);
        }

        for (r = 0; r < tbodyTr.length; r++) {
            var tds = $(tbodyTr[r]).find('td');
            var row = [];
            for (d = 0; d < tds.length; d++) {
                row.push($(tds[d]).text());
            }
            if (row.length) {
                originalData.push(row);
            }

        }

        for (f = 0; f < tfootTh.length; f++) {
            footer.push($(tfootTh[f]).text());
        }
        if (footer.length) {
            originalData.push(footer);
        }

        originalData.push(["", "", "", "", "", "", "", "", "", ""]);
    });


    var workbook = ExcelBuilder.Builder.createWorkbook(),
            worksheet = workbook.createWorksheet({
                name: 'Swift_Portal_Report_Call_Source'
            }),
            report = new ExcelBuilder.Table();
//         stylesheet = workbook.getStyleSheet();

    report.styleInfo.themeStyle = "TableStyleLight1";
    report.setReferenceRange([1, 1], [10, originalData.length]);
//        report.setTableColumns(headers);
//        worksheet.sheetView.showGridLines = true;
    worksheet.setData(originalData);

    workbook.addWorksheet(worksheet);

    worksheet.addTable(report);
    workbook.addTable(report);


    ExcelBuilder.Builder.createFile(workbook).then(function (data) {
        if ('download' in document.createElement('a')) {
            downloadBtn.attr({
                href: "data:application/vnd.openxmlformats-officedocument.spreadsheetml.sheet;base64," + data
            });
        } else {
            Downloadify.create('downloader', {
                filename: function () {
                    return "Swift_Portal_Report_Call_Source.xlsx";
                },
                data: function () {
                    return data;
                },
                swf: 'downloadify/media/downloadify.swf',
                downloadImage: 'downloadify/images/download.png',
                width: 100,
                dataType: 'base64',
                height: 30,
                transparent: true,
                append: false
            });
        }

        if (originalData.length) {
            downloadBtn.removeClass('hide');
        }

    });
}

function parseDataSource(itemSelected)
{
    loading.show();
    downloadBtn.addClass('hide');

    $.get("/tenstreet/get-report3-data", {item_selected: itemSelected}, function (response) {
        $('#tables-remote').html(response);
        loading.hide();
        downloadBtn.removeClass('hide');
        exportData();
    });

}

$('select[name="call-source-select"]').on('change', function () {
    var itemSelected = $(this).find('option:selected').val();
    if (itemSelected) {
        parseDataSource(itemSelected);
    }
});



