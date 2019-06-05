$(document).ready(function () {
    $('.logo').on('click', function (event) {
        event.preventDefault();
        $('.user-company-list').toggleClass('hide');
    });

    $('.user-company-list a').on('click', function (event) {
        event.preventDefault();
        var src = $(this).data('src');
        var id = $(this).data('id');
        if (src && id) {
            $.post('/user/change-company-logo', {id: id}, function () {
                sweetAlert({
                    title: "Success",
                    text: "Your company was changed successfully!",
                    type: "success"
                }, function (isConfirm) {
                    if (isConfirm) {
                        window.location.reload();
                    }
                });
                $('.logo-img').attr('src', src);
            }).fail(function () {
                sweetAlert("Oops...", "An error ocurred when trying to change the company", "error");
            });
        } else {
            sweetAlert("Oops...", "An error ocurred when trying to change the company", "error");
        }
    });
});