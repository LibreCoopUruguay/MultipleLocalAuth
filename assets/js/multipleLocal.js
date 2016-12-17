$(function() {
    
    $('#multiple-login-recover').click(function() {
        $('#multiple-login').hide();
        $('#multiple-recover').show();
    });
    
    $('#multiple-login-recover-cancel').click(function() {
        $('#multiple-login').show();
        $('#multiple-recover').hide();
    });

});
