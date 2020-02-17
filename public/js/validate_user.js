var first_tab_height = $('.first_tab').height();

$('.second_tab').height(first_tab_height);

var fileName;
var locale = $('.locale').text();
var validate2 = false;
var roles;
roles = $('.roles');

$('input[type="file"]').change(function(e){
    fileName = e.target.files[0].name;
    $('#file_name').text(fileName);
});

$('.roles').change(function(event){
    if( roles.is(':checked')) {
        validate2 = true;
    } else {
        validate2 = false;
    }
});

$('.btn-submit').click(function(event){       
    var validate_role = '';
    
    if(locale == 'hr') {
        validate_role = "Obavezna dodjela uloge";
    } else if( locale = 'en') {
        validate_role = "Required role assignment";            
    }       

    if( validate2 == false ) {
        event.preventDefault();

        if( roles.parent().parent().find('.validate').length  == 0 ) {                
            roles.parent().parent().append(' <p class="validate">' + validate_role + '</p>');
        }
    } else {
            roles.parent().find('.validate').text("");
    }
});

$('.btn-next').click(function(event){
    var f_name;
    var l_name;
    var email;
    var password;
    var conf_password;
    var file;
    var validate_name = '';
    var validate_lastname = '';
    var valiate_email = '';
    var validate_password = '';
    var validate_password_lenght = '';
    var validate_passwordconf = '';
    var validate = false;

    f_name = $("#first_name");
    l_name = $("#last_name");
    email = $("#email");
    password = $("#password");
    conf_password = $("#conf_password");
    file = $("#file");        
    
    if(locale == 'hr') {
        validate_name = "Obavezan unos imena";
        validate_lastname = "Obavezan unos prezimena";
        valiate_email = "Obavezan unos emaila";
        validate_password = "Obavezan unos lozinke";
        validate_passwordconf = "Obavezna potvrda lozinke";
        validate_password_lenght = "Obavezan unos minimalno 6 znaka";
    } else if( locale = 'en') {
        validate_name = "Required name entry";
        validate_lastname = "Required lastname entry ";
        valiate_email = "Required e-mail entry";
        validate_password = "Required password entry";
        validate_passwordconf = "Password confirmation required";
        validate_password_lenght = "Minimum of 6 characters is required";
    }

    if(! f_name.val()) {
        if( f_name.parent().find('.validate').length  == 0) {
            f_name.parent().append(' <p class="validate">' + validate_name + '</p>');               
        }
        validate = false;
    } else {
        f_name.parent().find('.validate').text("");  
        validate = true;
    }
    if(! l_name.val()) {
        if( l_name.parent().find('.validate').length  == 0) {
            l_name.parent().append(' <p class="validate">' + validate_lastname + '</p>');
        }            
        validate = false;
    } else {
        l_name.parent().find('.validate').text("");
        validate = true;
    }
    if(! email.val()) {
        if( email.parent().find('.validate').length  == 0) {
            email.parent().append(' <p class="validate">' + valiate_email + '</p>');
        }
        validate = false;
    } else {
        email.parent().find('.validate').text("");  
        validate = true;     
    }
    if(! password.val()) {
        if( password.parent().find('.validate').length  == 0) {
            password.parent().append(' <p class="validate">' + validate_password + '</p>');
        }
        validate = false;
    } else {
        if(password.val().length < 6) {
            if( password.parent().find('.validate').length  == 0 ) {
                password.parent().append(' <p class="validate">' + validate_password_lenght + '</p>');
            } else {
                password.parent().find('.validate').text(validate_password_lenght);  
            }
            validate = false;
        } else {
            password.parent().find('.validate').text("");     
            if( ! conf_password.val() || (password.val() != conf_password.val()) ) {
            if( conf_password.parent().find('.validate').length  == 0 ) {                
                    conf_password.parent().append(' <p class="validate">' + validate_passwordconf + '</p>');
                }
                validate = false;
            } else {
                conf_password.parent().find('.validate').text("");     
                validate = true;  
            }
        }            
    }
    
    if(validate == true ) {
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

});
$('.btn-back').click(function(){
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
});