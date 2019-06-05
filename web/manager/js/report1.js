var loading = $('#loading');

function parseDataSource(itemSelected)
{
    loading.show();

    $.get("/tenstreet/get-report1-data", {item_selected: itemSelected}, function (response) {
        $('#tables-remote').html(response);
        loading.hide();
    });

}

$('select[name=data-source-select]').on('change', function () {
    var itemSelected = $(this).find('option:selected').val();
    if (itemSelected) {
        parseDataSource(itemSelected);
    }
});



