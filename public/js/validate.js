var locale = $('.locale').text();
var validate_text;
var email_unique;
var error;
var request_send;
var status_requests;
var all_requests;
var done;

if(locale == 'en') {
    validate_text = "Required field";
    email_unique = "Email must be unique";
    error = "There was an error";
    saved = "Data saved successfully.";
    request_send = "Request sent";
    status_requests = "To see you request status and see all request visit All requests page";
    all_requests = "All requests";
    done = "Done";
} else {
    validate_text = "Obavezno polje";
    email_unique = "E-mail mora biti jedinstven";
    error = "Došlo je do greške, poslana je poruka na podršku";
    saved = "Podaci su spremljeni";
    request_send = "Zahtjev je poslan";
    status_requests = "Da biste vidjeli status zahtjeva i pogledali sve zahtjeve posjetite Svi zahtjevi stranicu";
    all_requests = "Svi zahtjevi";
    done = "Gotovo";
}

$('.remove').click(function(){
    $(this).parent().remove();
});
var page = $('.admin_pages li').find('a.active_admin');
var modul_name = $('.admin_pages li').find('a.active_admin').attr('id');

$('.btn-submit').click(function(event){
    event.preventDefault();
    var form = $(this).parents('form:first');
    let url = $(this).parents('form:first').attr('action');
    var form_data = form.serialize();
  
    var url_load = window.location.href;
    var pathname = window.location.pathname;

    var validate = [];
    console.log(form_data);
    console.log("url " + url);
    console.log("url_load " +url_load);
    console.log("pathname " +pathname);

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
    console.log(validate);
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
                if(pathname == '/events' ) {
                    $('.all_events').load(url_load + ' .all_events .hour_in_day');
                } else if(url.includes("/vehical_services/")) {
                    url = window.location.origin + '/vehical_services';
                    $('.modal-body').load(url + " .modal-body table" );
                    $.getScript( '/../restfulizer.js');
                } else if(url_load.includes("/oglasnik")) {
                        url = window.location.origin + '/oglasnik';
                        $('.main_ads').load(url + " .main_ads article" );
                        $.getScript( '/../restfulizer.js');

                } else if(url.includes("/fuels/")) {                    
                    url = window.location.origin + '/fuels';
                    $('.modal-body').load(url + " .modal-body table" );
                    $.getScript( '/../restfulizer.js');
                } else if (url_load.includes("/admin_panel")) {
                    $('tbody').load($(page).attr('href') + " tbody>tr",function(){
                        $.getScript( '/../restfulizer.js');
                    });
                } else if (pathname.includes("/edit_user")) {
                    location.reload();
                } else if (url.includes("/events") && pathname == '/dashboard' ) {
                    $('.all_agenda').load(url + " .all_agenda .agenda");
                } else {
                   
                    $('.index_main').load(url + " .index_main>section",function(){
                        if(url.includes("/absences")) {
                            $('#index_table_filter').show();
                            $('#index_table_filter').prepend('<a class="add_new" href="' + location.origin+'/absences/create' +'" class="" rel="modal:open"><i style="font-size:11px" class="fa">&#xf067;</i>Novi zahtjev</a>');
                            $('.all_absences #index_table_filter').append('<span class="show_button"><i class="fas fa-download"></i></span>');
                            $.getScript( '/../restfulizer.js');
                        }
                    });
                }
                if(url.includes("/absences")) {
                  
                    $('<div><div class="modal-header"><span class="img-success"></span></div><div class="modal-body"><div class="alert alert-success alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button><strong>success:</strong>' + request_send + '<p>' + status_requests + '</p></div></div><div class="modal-footer"><span><button class="btn_all" ><a href="' + location.origin + '/absences' + '" >' + all_requests + '</a></button></span><button class="done"><a href="#close" rel="modal:close" >' + done + '</a></button></div></div>').appendTo('body').modal();
                } else {
                    $('<div><div class="modal-header"><span class="img-success"></span></div><div class="modal-body"><div class="alert alert-success alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button><strong>success:</strong>' + saved + '</div></div></div>').appendTo('body').modal();
                }
            }, 
            error: function(jqXhr, json, errorThrown) {
                
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