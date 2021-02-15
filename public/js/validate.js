var locale = $('.locale').text();
var validate_text;
var email_unique;
var error;
var request_send;
var status_requests;
var all_requests;
var done;
var saved;
var validate = [];

if(locale == 'en') {
    validate_text = "Required field";
    email_unique = "Email must be unique";
    error = "There was an error";
    saved = "Data saved successfully.";
    request_send = "Request sent";
    status_requests = "To see you request status and see all request visit All requests page";
    all_requests = "All requests";
    done = "Done";
    validate_name = "Required name entry";
    validate_lastname = "Required lastname entry ";
    validate_email = "Required e-mail entry";
    validate_password = "Required password entry";
    validate_passwordconf = "Password confirmation required";
    validate_password_lenght = "Minimum of 6 characters is required";
    validate_role = "Required role assignment";   
} else {
    validate_text = "Obavezno polje";
    email_unique = "E-mail mora biti jedinstven";
    error = "Došlo je do greške, poslana je poruka na podršku";
    saved = "Podaci su spremljeni";
    request_send = "Zahtjev je poslan";
    status_requests = "Da biste vidjeli status zahtjeva i pogledali sve zahtjeve posjetite Svi zahtjevi stranicu";
    all_requests = "Svi zahtjevi";
    done = "Gotovo";
    validate_name = "Obavezan unos imena";
    validate_lastname = "Obavezan unos prezimena";
    validate_email = "Obavezan unos emaila";
    validate_password = "Obavezan unos lozinke";
    validate_passwordconf = "Obavezna potvrda lozinke";
    validate_password_lenght = "Obavezan unos minimalno 6 znaka";
    validate_role = "Obavezna dodjela uloge";
}

$('.remove').on('click',function(){
    $(this).parent().remove();
});
var page = $('.admin_pages li').find('a.active_admin');
var modul_name = $('.admin_pages li').find('a.active_admin').attr('id');

function validate_user_form () {
    validate = [];
    $('.roles').on('change',function(event){
        if( roles.is(':checked')) {
            validate.push(true);
        } else {
            validate.push("block");
        }
        console.log('validate roles');
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
        console.log('validate textarea');
    });
    $( "input" ).not('.roles').each(function( index ) {
        if( $(this).attr('required') == 'required' ) {
            if( $(this).val().length == 0 || $(this).val() == '') {
                if( ! $( this ).parent().find('.modal_form_group_danger').length) {
                    $( this ).parent().append('<p class="modal_form_group_danger">' + validate_text + '</p>');
                }
                validate.push("block");
            } else {
                $( this ).parent().find('.modal_form_group_danger').remove();
                validate.push(true);
            }
            console.log('validate input');
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
            console.log('validate select');  
        }
    });
     /*    if (tinyMCE.activeEditor) { */
    /*        if(tinyMCE.activeEditor.getContent().length == 0) { */
    /*            if( ! $('#mytextarea').parent().find('.modal_form_group_danger').length) { */
    /*                $('#mytextarea').parent().append('<p class="modal_form_group_danger">' + validate_text + '</p>'); */
    /*            } */
    /*            validate.push("block"); */
    /*            $('#mytextarea').parent().find('.modal_form_group_danger').remove(); */
    /*            validate.push(true); */
    /*        } */
    /*    } */
    if( $("#password").length > 0 ) {
        password = $("#password");
        conf_password = $("#conf_password");    
       
        if ($(password).length > 0 && $(password).text() != '') {
            if( password.val().length < 6) {
                if( password.parent().find('.validate').length  == 0 ) {
                    password.parent().append(' <p class="validate">' + validate_password_lenght + '</p>');
                } else {
                    password.parent().find('.validate').text(validate_password_lenght);  
                }
                validate.push("block");
            } else {
                password.parent().find('.validate').text("");     
                if( ! $(conf_password).val() || ($(password).val() != conf_password.val()) ) {
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
        console.log('validate password');  
    }
}
$('input[type="file"]').on('change',function(e){
    fileName = e.target.files[0].name;
    $('#file_name').text(fileName);
});

$('.btn-submit').on('click',function(event){
    /* event.preventDefault(); */
   
    var form = $(this).parents('form:first');
    let url = $(this).parents('form:first').attr('action');
    var form_data = form.serialize();
  
    var url_load = window.location.href;
    var pathname = window.location.pathname;
    validate_user_form ();
    console.log(url);
    console.log(form_data);
    console.log(validate);

    if(validate.includes("block") ) {
       event.preventDefault();
       validate = [];
    } else {
        $('.roles_form .checkbox').show();
      
       /*  $.ajaxSetup({
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
                if(pathname == '/events' && url.includes("/events/")) {  //event edit
                    $('.modal-header').load(url + ' .modal-header h5');
                    $('.modal-body').load(url + ' .modal-body p');
                    $('.main_calendar_day').load(url_load + ' .main_calendar_day>div');
                    $('.main_calendar_month').load(url_load + ' .main_calendar_month table');
                    $('.main_calendar_week').load(url_load + ' .main_calendar_week table');
                    $('.main_calendar_list').load(url_load + ' .main_calendar_list>list');
                    $('.all_events').load(url_load + ' .all_events .hour_in_day');
                } else if(pathname == '/events' ) {
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
                } else if (pathname.includes("/edit_user")) {
                    location.reload();
                } else if (url.includes("/loccos") && pathname == '/dashboard' ) {
                    $('.layout_button').load(url_load + " .layout_button button" ); 

                } else if (url.includes("/events") && pathname == '/dashboard' ) {
                    $('.all_agenda').load(url + " .all_agenda .agenda");
                } else if ( pathname == '/dashboard' ) {
                    $('.salary').load(url_load + " .salary>div");
                } else if (url.includes("/posts") ) {
                    if(pathname == '/dashboard') {
                        $('.all_post').load(url_load + " .all_post>div");
                    } else if (pathname == '/posts') {
                        $('.container').load(url_load + ' .container .posts_index',function(){
                            $('.tablink').first().trigger('click');
                            $('.tabcontent').first().show();
                            broadcastingPusher();
                            refreshHeight(tab_id);
                            setPostAsRead(post_id);
                        });
                    }
                } else {
                    if($('.index_admin').length > 0 ) {
                        if(url.includes("/work_records")) {
                          
                            $('.first_view tbody').load($(page).attr('href') + " .first_view tbody>tr",function(){
                                $.getScript( '/../restfulizer.js');
                            });
                        } else if(url_load.includes('/work_records_table')) {
                            console.log("second");
                            $('tbody.second').load(location.origin+'/work_records_table'+ " tbody.second>tr",function(){
                                $.getScript( '/../restfulizer.js');
                            });
                        } else {
                            $('tbody').load(location.href + " tbody>tr:not(.second_view tbody>tr)",function(){
                                $.getScript( '/../restfulizer.js');
                            });
                        }
                    } else {
                        $('.index_main').load(url + " .index_main>section",function(){
                            if(url.includes("/absences")) {
                                $('#index_table_filter').show();
                                $('#index_table_filter').prepend('<a class="add_new" href="' + location.origin+'/absences/create' +'" class="" rel="modal:open"><i style="font-size:11px" class="fa">&#xf067;</i>Novi zahtjev</a>');
                                $('.all_absences #index_table_filter').append('<span class="show_button"><i class="fas fa-download"></i></span>');
                                $('.index_page table.display.table').show();
                                
                                $.getScript( '/../restfulizer.js');
                            } else if(url.includes("/employees")) {
                                $.getScript( '/../js/users.js');
                            }
                        });
                    }
                   
                }
                if(url.includes("/absences")) {
                    $('<div><div class="modal-header"><span class="img-success"></span></div><div class="modal-body"><div class="alert alert-success alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button><strong>success:</strong>' + request_send + '<p>' + status_requests + '</p></div></div><div class="modal-footer"><span><button class="btn_all" ><a href="' + location.origin + '/absences' + '" >' + all_requests + '</a></button></span><button class="done"><a href="#close" rel="modal:close" >' + done + '</a></button></div></div>').appendTo('body').modal();
                } else if(! url.includes("/events/") && ! url.includes("/posts"))  {
                    $('<div><div class="modal-header"><span class="img-success"></span></div><div class="modal-body"><div class="alert alert-success alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button><strong>success:</strong>' + saved + '</div></div></div>').appendTo('body').modal();
                }
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
         */
        if($(page).length > 0) {
            $(page).trigger('click');
        } else {
           $('.btn-submit').trigger('unbind');
        }
    }
});

$('.form_user .btn-next').on('click',function(event){
    f_name = $("#first_name");
    l_name = $("#last_name");
    email = $("#email");
    password = $("#password");
    conf_password = $("#conf_password");
    file = $("#file");        
    
    validate = [];

    validate_user_form ();
    console.log(validate);
    if(validate.includes("block") ) {
        //
    } else  {
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

$('.form_user .btn-back').on('click',function(){
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