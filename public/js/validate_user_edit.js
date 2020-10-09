/* var f_name;
var l_name;
var email;
var file;
var validate_name = '';
var validate_lastname = '';
var valiate_email = '';
var password;
var conf_password;  
var validate_role = '';
var validate_password = '';
var validate_password_lenght = '';
var validate_passwordconf = '';
var roles;
var fileName;
var validate = [];

var locale = $('.locale').text();

if(locale == 'hr') {
    validate_name = "Obavezan unos imena";
    validate_lastname = "Obavezan unos prezimena";
    valiate_email = "Obavezan unos emaila";
    validate_password = "Obavezan unos lozinke";
    validate_passwordconf = "Obavezna potvrda lozinke";
    validate_password_lenght = "Obavezan unos minimalno 6 znaka";
    validate_role = "Obavezna dodjela uloge";
   
} else if( locale = 'en') {
    validate_name = "Required name entry";
    validate_lastname = "Required lastname entry ";
    valiate_email = "Required e-mail entry";
    validate_password = "Required password entry";
    validate_passwordconf = "Password confirmation required";
    validate_password_lenght = "Minimum of 6 characters is required";
    validate_role = "Required role assignment";   
}

var second_tab_height = $('.second_tab').height();
$('.first_tab').height(second_tab_height);

$('input[type="file"]').on('change',function(e){
    fileName = e.target.files[0].name;
    $('#file_name').text(fileName);
});

function validate_user_form () {
    validate = [];
    $('.roles').on('change',function(event){
        if( roles.is(':checked')) {
            validate.push(true);
        } else {
            validate.push("block");
        }
    });
    $( "textarea" ).each(function( index ) {
        if($(this).attr('required') == 'required' ) {
            if( $(this).val().length == 0 ) {
                if( !$( this ).parent().find('.modal_form_group_danger').length) {
                    $( this ).parent().append('<p class="modal_form_group_danger">' + validate_text + '</p>');
                }
                validate.push("block");
            } else {
                $( this ).parent().find('.modal_form_group_danger').remove();
                validate.push(true);
            }
        }
    });
    $( "input" ).each(function( index ) {
        if($(this).attr('required') == 'required' ) {
            if( $(this).val().length == 0 || $(this).val() == '') {
                if( ! $( this ).parent().find('.modal_form_group_danger').length) {
                    $( this ).parent().append('<p class="modal_form_group_danger">' + validate_text + '</p>');
                }
                validate.push("block");
            } else {
                $( this ).parent().find('.modal_form_group_danger').remove();
                validate.push(true);
            }
        }      
    });
    $( "select" ).each(function( index ) {
        if($(this).attr('required') == 'required' ) {
            if( $(this).val() == null || $(this).val() == '' || $(this).val() == '') {
                if( ! $( this ).parent().find('.modal_form_group_danger').length) {
                    $( this ).parent().append('<p class="modal_form_group_danger">' + validate_text + '</p>');
                }
                validate.push("block");
            } else {
                $( this ).parent().find('.modal_form_group_danger').remove();
                validate.push(true);
            }
        }
    });
    if($("#password").length >0) {
        password = $("#password");
        conf_password = $("#conf_password");    
        
        if(password.val().length > 0 ) {
            if( password.val().length < 6) {
                if( password.parent().find('.validate').length  == 0 ) {
                    password.parent().append(' <p class="validate">' + validate_password_lenght + '</p>');
                } else {
                    password.parent().find('.validate').text(validate_password_lenght);  
                }
                validate.push("block");
            } else {
                password.parent().find('.validate').text("");     
                if( ! conf_password.val() || (password.val() != conf_password.val()) ) {
                if( conf_password.parent().find('.validate').length  == 0 ) {                
                        conf_password.parent().append(' <p class="validate">' + validate_passwordconf + '</p>');
                    }
                    validate.push("block");
                } else {
                    conf_password.parent().find('.validate').text("");     
                    validate.push(true);
                }
            }
        }
    }
}

$('.form_edit_user').on('submit',function(event){   
    console.log("form_edit_user");
    event.preventDefault();
    var form = $(this).parents('form:first');
    let url = $(this).parents('form:first').attr('action');
    var form_data = form.serialize();
    
    validate_user_form ();

    if( validate.includes("block") ) {
        event.preventDefault();
        validate = [];
        if( roles.parent().parent().find('.validate').length  == 0 ) {                
            roles.parent().parent().append(' <p class="validate">' + validate_role + '</p>');
        }
    } else {
        roles.parent().find('.validate').text("");
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });     
        $.ajax({
            url: url,
            type: "POST",
            data: form_data,
            success: function( response ) {
            }, 
            error: function(jqXhr, json, errorThrown) {
                console.log(jqXhr.responseJSON);
                var data_to_send = { 'exception':  jqXhr.responseJSON.exception,
                                    'message':  jqXhr.responseJSON.message,
                                    'file':  jqXhr.responseJSON.file,
                                    'line':  jqXhr.responseJSON.line };
                if(url.includes("users") && errorThrown == 'Unprocessable Entity' ) {
                    alert(email_unique);
                }  else {
                    $.modal.close();
                    $.ajax({
                        url: 'errorMessage',
                        type: "get",
                        data: data_to_send,
                        success: function( response ) {
                            $('<div><div class="modal-header"><span class="img-error"></span></div><div class="modal-body"><div class="alert alert-danger alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button><strong>danger:</strong>' + response + '</div></div></div>').appendTo('body').modal();
                        }, 
                        error: function(jqXhr, json, errorThrown) {
                            console.log(jqXhr.responseJSON); 
                        }
                    });
                }
            }
        });
    }

    console.log(validate);
    console.log(password);
    console.log(conf_password);
    console.log(url);
    console.log(form_data);
});

$('.form_edit_user .btn-next').on('click',function(event){  
    f_name = $("#first_name");
    l_name = $("#last_name");
    email = $("#email");
    file = $("#file");        
    validate_user_form ();
  
    //console.log(validate);
    if( validate.includes("block") ) {
        validate = [];
    } else {
        $('.first_tab').toggle();
        $('.second_tab').toggle();
        if($('.first_tab').is(':visible')) {
            $('.mark1').css('background','#1594F0');
            $('.mark2').css('background','rgba(43, 43, 43, 0.2)');
    
        } 
        if($('.second_tab').is(':visible')) {
            $('.mark1').css('background','rgba(21, 148, 240, 0.4)');
            $('.mark2').css('background','#1594F0');
        }
    }
    console.log(validate);
});

$('.form_edit_user .btn-back').on('click',function(){
    $('.first_tab').toggle();
    $('.second_tab').toggle();
    if($('.first_tab').is(':visible')) {
        $('.mark1').css('background','#1594F0');
        $('.mark2').css('background','rgba(43, 43, 43, 0.2)');

    } 
    if($('.second_tab').is(':visible')) {
        $('.mark1').css('background','rgba(21, 148, 240, 0.4)');
        $('.mark2').css('background','#1594F0');
    }
}); */