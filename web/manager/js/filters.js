function addParameter(url, param, value) {
// Using a positive lookahead (?=\=) to find the
// given parameter, preceded by a ? or &, and followed
// by a = with a value after than (using a non-greedy selector)
// and then followed by a & or the end of the string
    var val = new RegExp('(\\?|\\&)' + param + '=.*?(?=(&|$))'),
            parts = url.toString().split('#'),
            url = parts[0],
            hash = parts[1]
    qstring = /\?.+$/,
            newURL = url;
    // Check if the parameter exists
    if (val.test(url))
    {
// if it does, replace it, using the captured group
// to determine & or ? at the beginning
        newURL = url.replace(val, '$1' + param + '=' + value);
    } else if (qstring.test(url))
    {
// otherwise, if there is a query string at all
// add the param to the end of it
        newURL = url + '&' + param + '=' + value;
    } else
    {
// if there's no query string, add one
        newURL = url + '?' + param + '=' + value;
    }

    if (hash)
    {
        newURL += '#' + hash;
    }

    return newURL;
}

$(function () {

    $('#status-filter').on('change', function () {
        var option = $(this).find('option:selected').val();
        if (option) {
            window.location.href = addParameter(window.location.href, 'status', option);
        }
    });
    $('#publisher-filter').on('change', function () {
        var option = $(this).find('option:selected').val();
        if (option) {
            window.location.href = addParameter(window.location.href, 'publisher', option);
        }
    });
    $('#segment-filter').on('change', function () {
        var option = $(this).find('option:selected').val();
        if (option) {
            window.location.href = addParameter(window.location.href, 'segment', option);
        }
    });

    var table = $('#table').dataTable({
        "iDisplayLength": 100,
        "aoColumnDefs": [
            {'bSortable': false, 'aTargets': [1]},
//            {"asSorting": ["asc"], "aTargets": [1]}
        ]
    });

    function sendRowsToDelete(arrayItems) {
        if (!arrayItems.length) {
            swal({
                title: "Opss",
                text: "You need to selected a least one record for this action",
                timer: 2000,
                showConfirmButton: false
            });
            return false;
        }
        $.post('delete-multiple-records', {
            items: JSON.stringify(arrayItems)
        }, function () {
            var aTrs = table.fnGetNodes();
            for (var i = 0; i < aTrs.length; i++) {
                if ($(aTrs[i]).hasClass('selected')) {
                    table.fnDeleteRow(aTrs[i]);
                }
            }
            swal("Success", "The selected records was delete successfully", "success");
        }).fail(function () {
            swal("Opss", "An error ocurred when try to delete de selected records", "error");
        });
    }



    $('.delete-checked-items').on('click', function () {
        swal({
            title: "Delete Records",
            text: "Are your sure you want to delete the selected records? this action can't be reverted",
            type: "info",
            showCancelButton: true,
            closeOnConfirm: false,
            showLoaderOnConfirm: true
        }, function (isTrue) {
            if (isTrue) {
                var items = $(document).find('input[name="delete-check"]');
                var arrayItems = [];
                $.each(items, function (index, elem) {
                    if ($(elem).is(':checked')) {
                        arrayItems.push($(elem).data('id'));
                    }
                });
                sendRowsToDelete(arrayItems);
            }
        });

    });

    $(document).on('click', '.delete-check', function () {
        if ($(this).is(':checked')) {
            $(this).parents('tr').addClass('selected');
        } else {
            $(this).parents('tr').removeClass('selected');
        }
    });
});