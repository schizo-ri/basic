var locale = $('.locale').text();

if(locale == 'hr') {
    validate_text = "Obavezno polje";
} else if( locale = 'en') {
    validate_text = "Required field";            
} else {
    validate_text = "Obavezno polje";
}   
$('.remove').click(function(){
    $(this).parent().remove();
    console.log("remove");
});
var page = $('.admin_pages li').find('a.active_admin');
var modul_name = $('.admin_pages li').find('a.active_admin').attr('id');

$('.btn-submit').click(function(event){
    event.preventDefault();
    var form = $(this).parents('form:first');
    let url = $(this).parents('form:first').attr('action');
    var form_data = form.serialize();
    console.log(form_data);
    
    var validate = [];
    
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
             //   console.log("input" + $(this).val() ); 
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
    
    if (tinyMCE.activeEditor) {
        if(tinyMCE.activeEditor.getContent().length == 0) {
            if( ! $('#mytextarea').parent().find('.modal_form_group_danger').length) {
                $('#mytextarea').parent().append('<p class="modal_form_group_danger">' + validate_text + '</p>');
            }
            validate.push("block");
            $('#mytextarea').parent().find('.modal_form_group_danger').remove();
            validate.push(true);
        }
    }
    
    if(validate.includes("block") ) {
       event.preventDefault();
     
       validate = [];
       
    } else {     
        $('.roles_form .checkbox').show();
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
                $.modal.close();
                var url_load = window.location.href;
                var pathname = window.location.pathname;
                if(pathname == '/events' ) {
                   $('.all_events').load(url_load + ' .all_events .hour_in_day');
                }
                if(url.includes("/vehical_services/")) {                  
                    url = window.location.origin + '/vehical_services';
                    $('.modal-body').load(url + " .modal-body table" );
                    $.getScript( '/../restfulizer.js');
                }
                if(url.includes("/fuels/")) {                    
                    url = window.location.origin + '/fuels';
                    $('.modal-body').load(url + " .modal-body table" );
                    $.getScript( '/../restfulizer.js');
                }
            }, 
            error: function(xhr,textStatus,thrownError) {
                console.log("validate eror " + xhr + "\n" + textStatus + "\n" + thrownError); 
                if(url.includes("users") && thrownError == 'Unprocessable Entity' ) {
                    alert("E-mail mora biti jedinstven / Email must be unique");
                }                
            }
          });
          
        if($(page).length > 0) {
            $(page).click();
        } else {
           $('.btn-submit').unbind();
        }
    }
});

$('.btn-next').click(function(event){  
    f_name = $("#first_name");
    l_name = $("#last_name");
    email = $("#email");
    file = $("#file");        
   var validate2 = [];
    //console.log(l_name.val());
    if(! f_name.val()) {
        if( f_name.parent().find('.validate').length  == 0) {
            f_name.parent().append(' <p class="validate">' + validate_name + '</p>');               
        }
        validate2.push("block");
    } else {
        f_name.parent().find('.validate').text("");  
        validate2.push(true);
        if(! l_name.val()) {
            if( l_name.parent().find('.validate').length  == 0) {
                l_name.parent().append(' <p class="validate">' + validate_lastname + '</p>');
            }            
            validate2.push("block");
        } else {
            l_name.parent().find('.validate').text("");
            validate2.push(true);
            if(! email.val()) {
                if( email.parent().find('.validate').length  == 0) {
                    email.parent().append(' <p class="validate">' + valiate_email + '</p>');
                }
                validate2.push("block");
            } else {
                email.parent().find('.validate').text("");  
                validate2.push(true); 
            }   
        }
    }
  
    //console.log(validate);
    if(! validate2.includes("block") ) {
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