<script>
$(document).ready(function(){
    $(document).on('click', '#refresh_captcha', function(e) {
        e.preventDefault();

        $.ajax({
            type: "GET",
            url: cg.config.site_url + 'ajax/gamelang/captcha',
            beforeSend: function(xhr) {
                $('#refresh_captcha')
                    .find('.fa')
                    .addClass('fa-spin-pulse');
            },
            success: function(result, status, xhr) {
                $('#captcha').attr('src', result.results);
            },
            complete: function(xhr, status) {
                $('#captcha-input').focus();
                $('#refresh_captcha')
                    .find('.fa')
                    .removeClass('fa-spin-pulse');
            }
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
</script>