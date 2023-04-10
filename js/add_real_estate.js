    function show_add_real_estate_form () {
    // We'll pass this variable to the PHP function example_ajax_request
    // This does the ajax request
    $ = jQuery;
    $.post({
        url: ajax_obj.ajaxurl,
        data: {
            'action': 'show_acf_form',
            'email' : $('form #email').val (),
            'nonce' : ajax_obj.nonce
        },
        success:function(data) {
            // This outputs the result of the ajax request
            if (data == 'USER_EXISTS')          var msg =  ('Данный пользователь уже зарегистрирован, пожалуйста, <a href="/wp-login.php">войдите на сайт</a>');
            if (data == 'BAD_EMAIL')            var msg =  ('Пожалуйста, укажите корректный адресэ электронной почты');
            if (data == 'CANNOT_CREATE_USER')   var msg =  ('Произошла ошибка при создании пользователя. Пожалуйста, обратитесь к администратору');
            if (data == 'LOGIN_SUCCESS')  {
                $('#form_signup input').attr ('disabled', 'true');
                $('#add_real_estate, #form_error').hide ();
                $.ajax({
                    url: ajax_obj.ajaxurl,
                    data: {
                        'action': 'show_acf_form',
                        'show_form' : true,
                        'nonce' : ajax_obj.nonce
                    },
                    success:function(data) {
                        $('#form_result').html (data);
                    }
                })

            }
            if (typeof msg !== 'undefined') {
                $('#form_error').html (msg).removeClass ('d-none');
            }
            },
        error: function(errorThrown){
            console.log(errorThrown);
        }
    });
    }

function add_real_estate () {
    $ = jQuery;
    $('#add_real_estate_submit_btn').attr ('disabled', true).html('<i class="fa fa-spinner fa-spin" style="font-size:24px"></i> Загружаем данные...');

    var form = $("#add_real_estate_form");
    var formData = new FormData(form[0]);
    formData.append("action", "add_real_estate");
    formData.append("nonce", ajax_obj.nonce);

    $.post({
        url: ajax_obj.ajaxurl,
        processData: false,
        contentType: false,
        data: formData,
        success: function (data) {
            $('#add_real_estate_form').fadeOut (200, function () { $('#show_form_real_estate_result').html (data); });
        }
    });
}