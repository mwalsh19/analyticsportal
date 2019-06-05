/*global swal*/

$(document).ready(function () {
    var table = $('#table').DataTable({
        dom: 'Bfrtip',
        responsive: true,
        order: [[2, "desc"]],
        pageLength: 100,
        buttons: [
        ]
    }),
            loading = $('#loading');

    function importFile(params, _this) {
        $.ajax({
            method: 'POST',
            url: 'import-file',
            data: params,
            complete: function (jqXHR, textStatus) {

            },
            success: function (data, textStatus, jqXHR) {
                if (jqXHR.status == 200) {
                    swal('File Imported', 'The file was imported successfully! please go to Data Source section for view the lastest import file', 'success');
//                    table.row(_this)
//                            .remove()
//                            .draw();
                } else {
                    swal('Oops..', 'There was a problem, when trying to import the file.', 'error');
                }
            },
            error: function (jqXHR, textStatus, errorThrown) {
                swal('Oops..', 'There was a problem, when trying to import the file.', 'error');
            }
        }).fail(function () {
            swal('Oops..', 'There was a problem, when trying to import the file.', 'error');
        });
    }


    $(document).on('click', '.import-file', function (event) {
        event.preventDefault();

        var params = {
            file: $(this).data('file'),
            id_file: $(this).data('id-file')
        },
        _this = $(this).parents('tr'),
                filename = $(this).data('file-name');

        swal({
            title: "Import File to Data Sources",
            text: "Please type a label for <small style='font-size: 11px; color: red;'>" + filename + "</small>",
            type: "input",
            showCancelButton: true,
            closeOnConfirm: false,
            animation: "slide-from-top",
            inputPlaceholder: "Label",
            showLoaderOnConfirm: true,
            html: true
        }, function (inputValue) {
            if (inputValue === false)
                return false;
            if (inputValue === "") {
                swal.showInputError("Please type a label");
                return false;
            }
            params.label = inputValue;
            importFile(params, _this);
        });
    });

    $(window).load(function () {
        loading.hide();
    });

    function UpdateQueryString(key, value, url) {
        if (!url)
            url = window.location.href;
        var re = new RegExp("([?&])" + key + "=.*?(&|#|$)(.*)", "gi"),
                hash;

        if (re.test(url)) {
            if (typeof value !== 'undefined' && value !== null)
                return url.replace(re, '$1' + key + "=" + value + '$2$3');
            else {
                hash = url.split('#');
                url = hash[0].replace(re, '$1$3').replace(/(&|\?)$/, '');
                if (typeof hash[1] !== 'undefined' && hash[1] !== null)
                    url += '#' + hash[1];
                return url;
            }
        }
        else {
            if (typeof value !== 'undefined' && value !== null) {
                var separator = url.indexOf('?') !== -1 ? '&' : '?';
                hash = url.split('#');
                url = hash[0] + separator + key + '=' + value;
                if (typeof hash[1] !== 'undefined' && hash[1] !== null)
                    url += '#' + hash[1];
                return url;
            }
            else
                return url;
        }
    }
    $('.show-result').on('click', function (event) {
        event.preventDefault();
        var month = $('select[name="months-select"]').val(),
                year = $('select[name="years-select"]').find('option:selected').val();

        if (!month && !year) {
            swal("Opss..", "You need to select a Year and Month to continue", "error");
            return false;
        }
        window.location = window.location.protocol + '//' + window.location.host + '/tenstreet/tenstreet-import?year=' + year + '&month=' + month;
    });
});