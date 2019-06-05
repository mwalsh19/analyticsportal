$(document).ready(function () {
    var loading = $('#loading'),
            select1 = $('select[name="data-source-select-referrer"]'),
            select2 = $('select[name="data-source-select-source"]'),
            select3 = $('select[name="data-source-select-referrer-2"]'),
            select4 = $('select[name="data-source-select-source-2"]'),
            select5 = $('select[name="call-source-select"]'),
            publisherSelect = $('select[name="publishers-select"]'),
            items = [],
            downloadBtn = $("#downloader");

    function parseDataSource(items, publisher)
    {

        if (items.length < 2) {
            sweetAlert("Oops...", "You need to select a least two data sources", "error");
            return false;
        }
        if (!publisher) {
            sweetAlert("Oops...", "The media publisher is required to continue, please select an least one or check that the selected publisher has available Tenstreet (for referrer code) ", "error");
            return false;
        }
        loading.show();
        $.post("/tenstreet/get-report2-data", {
            items: items,
            publisher: publisher
        }, function (response) {
            $('#tables-remote').html(response);
            loading.hide();
            exportData();
        });
    }

    $('.show-result').on('click', function (event) {
        event.preventDefault();
        items = [];

        var item1 = select1.find('option:selected').val(),
                item2 = select2.find('option:selected').val(),
                item3 = select3.find('option:selected').val(),
                item4 = select4.find('option:selected').val(),
                item5 = select5.find('option:selected').val(),
                publisher = publisherSelect.find('option:selected').val();
        if (item1) {
            items.push({
                type: 'datasource',
                source: item1
            });
        }
        if (item2) {
            items.push({
                type: 'datasource',
                source: item2
            });
        }
        if (item3) {
            items.push({
                type: 'datasource',
                source: item3
            });
        }
        if (item4) {
            items.push({
                type: 'datasource',
                source: item4
            });
        }
        if (item5) {
            items.push({
                type: 'callsource',
                source: item5
            });
        }

        downloadBtn.addClass('hide');
        parseDataSource(items, publisher);
    });

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

            if ($(titles[key]).data('type') == 'call') {
                originalData.push([$(titles[key]).text(), "", ""]);
            } else {
                originalData.push([$(titles[key]).text(), "", "", "", "", "", "", "", "", ""]);
            }

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
            if ($(titles[key]).data('type') == 'call') {
                originalData.push(["", "", ""]);
            } else {
                originalData.push(["", "", "", "", "", "", "", "", "", ""]);
            }
        });


        var workbook = ExcelBuilder.Builder.createWorkbook(),
                worksheet = workbook.createWorksheet({
                    name: 'Swift_Portal_Report'
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
                        return "Swift_Portal_Report.xlsx";
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

            if (items.length) {
                downloadBtn.removeClass('hide');
            }

        });
    }
});

