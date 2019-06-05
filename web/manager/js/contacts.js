$(document).ready(function () {
    $('.trigger-more-info').on('click', function () {
        if (!$(this).is('a')) {
            $(this).parent().find('.more-info-container').toggleClass('hide');
        }
    });
});