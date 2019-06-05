/* global Downloadify, items */

$(document).ready(function () {
    var loading = $('#loading'),
            md_referrer = $('select[name="md-referrer-select"]'),
            md_source = $('select[name="md-source-select"]'),
            md_call_source = $('select[name="md-call-source-select"]'),
            prev_referrer = $('select[name="prev-referrer-select"]'),
            prev_source = $('select[name="prev-source-select"]'),
            prev_call_source = $('select[name="prev-call-source-select"]'),
            items = [],
            downloadBtn = $("#downloader");

    function parseDataSource(items)
    {
        if (items.length == 0 || items.length != 6) {
            sweetAlert("Oops...", "Please select the required fields", "error");
            return false;
        }

        loading.show();

        $.post("/tenstreet/get-report4-data", {
            items: items
        }, function (response) {
            $('#tables-remote').html(response);
            loading.hide();
            exportData();
        });
    }

    $('.show-result').on('click', function (event) {
        event.preventDefault();
        items = [];

        var ref_1 = md_referrer.find('option:selected').val(),
                ref_2 = prev_referrer.find('option:selected').val(),
                source_1 = md_source.find('option:selected').val(),
                source_2 = prev_source.find('option:selected').val(),
                call_1 = md_call_source.find('option:selected').val(),
                call_2 = prev_call_source.find('option:selected').val();

        if (ref_1) {
            items.push({
                type: 'R',
                source: ref_1,
                mtd: 1
            });
        }
        if (ref_2) {
            items.push({
                type: 'R',
                source: ref_2,
                mtd: 0
            });
        }
        if (source_1) {
            items.push({
                type: 'S',
                source: source_1,
                mtd: 1
            });
        }
        if (source_2) {
            items.push({
                type: 'S',
                source: source_2,
                mtd: 0
            });
        }
        if (call_1) {
            items.push({
                type: 'C',
                source: call_1,
                mtd: 1
            });
        }
        if (call_2) {
            items.push({
                type: 'C',
                source: call_2,
                mtd: 0
            });
        }

        downloadBtn.addClass('hide');
        parseDataSource(items);
    });

    function exportData() {
        var tables = $('table'),
                titles = $('.publisher-title'),
                originalData = [],
                headers = [],
                i = 0,
                r = 0,
                f = 1,
                d = 0,
                e = 0;

        tables.each(function (key, table) {
            var theadTh = $(table).find('thead tr th'),
                    tbodyTr = $(table).find('tbody tr'),
                    tfootTr = $(table).find('tfoot tr');
            headers = [];

            originalData.push([$(titles[key]).text(), "", "", "", "", "", "", "", "", "", ""]);

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

            for (f = 0; f < tfootTr.length; f++) {
                var tds = $(tfootTr[f]).find('th');
                var row = [];
                for (e = 0; e < tds.length; e++) {
                    row.push($(tds[e]).text());
                }
                if (row.length) {
                    originalData.push(row);
                }
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
