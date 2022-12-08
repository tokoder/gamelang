$(document).ready(function(){
    $(document).on('click', '#refresh_captcha', function(e) {
        e.preventDefault();
        $(this).find('.fa').addClass('fa-spin-pulse');
        $.get(config.siteURL + 'resource/captcha', function(json) {
            $('#captcha').attr('src', json.results);
            $('#captcha-input').focus();
            $('#refresh_captcha').find('.fa').removeClass('fa-spin-pulse');
        });
    });

    $(document).on('click', "#show_password", function(){
        if($(this).is(':checked')){
            $("input[name='password'], input[name='cpassword']").attr('type','text');
        }else{
            $("input[name='password'], input[name='cpassword']").attr('type','password');
        }
    });
});

function copy(email, password)
{
    $("#identity").val(email);
    $("#password").val(password);
}