$(function () {
    var filename = $('.filename').text(),
            overlay = $('#custom-overlay'),
            usersContainer = $('.users-container'),
            id_file = '',
            loading = $('#custom-overlay #loading'),
            loadingDataSources = $('.table-loading #loading');

    $('table').DataTable({
        "ajax": 'get-data-sources' + window.location.search,
        "fnDrawCallback": function (oSettings) {
            loadingDataSources.hide();
        },
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
        responsive: true
    });
    function prepareUsers(data) {
        if (data.length) {
            var usersCheckboxes = '';
            for (var i = 0; i < data.length; i++) {
                usersCheckboxes += '<div><label><input type="checkbox" name="user_' + data[i].id_user + '" value="' + data[i].id_user + '">' + data[i].name + '</label></div>';
            }
            usersContainer.find('.user-inputs').html(usersCheckboxes);
        } else {
            sweetAlert("Oops...", "No available users they found", "error");
        }
    }

    function checkUsersInputs(data) {
        if (data.length) {
            for (var i = 0; i < data.length; i++) {
                $(document).find('input[name="user_' + data[i].id_user + '"]').prop('checked', true);
            }
        }
    }

    function getUsers() {
        loading.show();
        $.ajax({
            method: 'GET',
            url: 'get-users',
            data: {
                id_file: id_file
            },
            dataType: "json"
        }).done(function (json) {
            prepareUsers(json.users);
            checkUsersInputs(json.sharedUsers);
            loading.hide();
        }).fail(function (xhr, status, errorThrown) {
            loading.hide();
            sweetAlert("Oops...", "Something went wrong when trying to load user list", "error");
        });
    }

    function sendUsers(params) {
        loading.show();
        $.ajax({
            method: 'POST',
            url: 'share',
            data: params
        }).done(function (response) {
            overlay.fadeOut();
            loading.hide();
            sweetAlert("Success", "The file was successfully shared", "success");
        }).fail(function (xhr, status, errorThrown) {
            loading.hide();
            sweetAlert("Oops...", "Something went wrong when trying to share file", "error");
        });
    }

    $(document).on('click', '.share-file-btn', function () {
        id_file = $(this).data('idfile');
        overlay.fadeIn();
        getUsers();
    });
    $('#share-now-btn').on('click', function () {
        var users = [];
        var selectedUsers = $(document).find('.users-container input');
        $.each(selectedUsers, function (index, elemt) {
            if ($(elemt).is(':checked')) {
                users.push(parseInt($(elemt).val()));
            }
        });
        if (id_file !== '') {
            var params = {
                users: users,
                id_file: id_file
            };
            sendUsers(params);
        } else {
            sweetAlert("Oops...", "You need to select at least one user", "error");
        }

    });
    $(document).on('click', '#cancel-btn', function () {
        overlay.fadeOut();
    });
});
