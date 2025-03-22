$(document).ready(function() {
    $('#show').click(function() {
        $(this).toggleClass('showed');
        $(this).toggleClass('open');
        if ($(this).hasClass('open')) {
            $('#password').attr('type', 'text');
        } else {
            $('#password').attr('type', 'password');
        }
    })
});