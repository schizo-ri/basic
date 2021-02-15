
    var parent_element;
    var input_element;
    var element;
    var value;
    var name; 
    
    var url = $('form.form_project').attr('action');
    var form = $('form.form_project');
    var form_data;
    var project_id;
    var mouse_is_inside = false;

    if($('.json_managers').length >0 ) {
        managers = JSON.parse($('.json_managers').text());
    }
    if($('.json_designers').length >0 ) {
        designers = JSON.parse($('.json_designers').text());
    }

    if($('tr#project').length >0 ) {
        $('td.edit_name, td.edit_start_date, td.edit_end_date, td.edit_duration').on('click',function(){
            if(mouse_is_inside == false) {
                $('input').remove();
                $('td span').show();
                parent_element = $(this);
                element = parent_element.find('span.value');
                project_id = parent_element.parent().attr('id');
                project_id = project_id.replace('project_','');   // 37
            }
        });
    }
    if($('.contracts').length >0 ) {
        $('td.edit_name, td.edit_manager, td.edit_designer, td.edit_comment, td.edit_quantity').on('click',function(){
            if(mouse_is_inside == false) {
                $('input').remove();
                $('select').remove();
                $('td span').show();
                parent_element = $(this);
                element = parent_element.find('span.value');
                project_id = parent_element.parent().attr('id');
                project_id = project_id.replace('project_','');   // 37
            }
        });
    }
  /*   
    $('body').on('keypress',function (event) {
        if (event.which == 13 ) {
            return false;
        }
    }); */
   
    $('.editable').on('keypress',function (event) {
        if (event.which == 13 ) {
            submit_form ();
        }
    });

    $(document).on('click',function(event) {
        if(parent_element) {
            if (! parent_element.is(event.target) && ! parent_element.has(event.target).length ) {
                if(mouse_is_inside == true ) {
                    submit_form ();
                }
            } else {
                if(element.text()) {
                    value = element.text();
                } else {
                    value = element.val();
                }
               
                var name = element.attr('title');
                if(name == 'name' || name == 'duration' || name == 'comment' || name == 'manager' || name == 'designer' || name == 'quantity') {
                    type = 'text';
                } else {
                    type = 'date';
                }
                if(name == 'duration'/*  || name == 'quantity' */) {
                    pattern="\d*";
                    title="Dozvoljen unos samo cijelog broja"
                } else {
                    pattern='';
                    title='';
                }
                
                element.hide();
                if(parent_element.find('input').length == 0) {
                    if( parent_element.hasClass('select')) {
                        var option_manager;
                        var option_designer;
                        if(parent_element.hasClass('edit_manager')) {
                            $.each(managers, function( index_user, user ) {
                                user_first_name = user.first_name ? user.first_name : "";
                                user_last_name = user.last_name ? user.last_name : "";
                                option_manager+='<option value="'+user.id+'">'+ user_first_name +' '+user_last_name+'</option>' ;
                            });
                            parent_element.prepend('<select name="'+name+'" type="'+type+'" value="' + value + '" pattern="'+ pattern +'" title="' + title +'" required autofocus >'+option_manager+'</select><input name="id" type="hidden" value="' + project_id + '"/>');
                        }
                        if(parent_element.hasClass('edit_designer')) {
                            $.each(designers, function( index_user, user ) {
                                user_first_name = user.first_name ? user.first_name : "";
                                user_last_name = user.last_name ? user.last_name : "";
                                option_designer+='<option value="'+user.id+'">'+user_first_name+' '+user_last_name+'</option>' ;
                            });
                            parent_element.prepend('<select name="'+name+'" type="'+type+'" value="' + value + '" pattern="'+ pattern +'" title="' + title +'" required autofocus >'+option_designer+'</select><input name="id" type="hidden" value="' + project_id + '"/>');
                        }
                        input_element = parent_element.find('select');
                    } else {
                        parent_element.prepend('<input name="'+name+'" type="'+type+'" value="' + value + '" pattern="'+ pattern +'" title="' + title +'" required autofocus /><input name="id" type="hidden" value="' + project_id + '"/>');
                        input_element = parent_element.find('input');
                    }
                   
                }
                mouse_is_inside = true;
            }
        }
       
    });

    $('.table_projects .inactive').hide();

    $('.show_inactive').on('click',function(){
        $('.table_projects .inactive').toggle();
        $('.table_projects .active').toggle();
        if($(this).text() == 'Prikaži neaktivne') {
            $(this).text('Prikaži aktivne');
        } else {
            $(this).text('Prikaži neaktivne');
        }
    });

function newFunction() {
    return 'keypress';
}

function submit_form () {
    form_data = form.serialize(); 

    console.log("form_data");
    console.log(form_data);
    parent_element.find('input').remove();
    parent_element.find('select').remove();
    element.show();
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });  
    if(form_data) {
        $.ajax({
            url: url,
            type: "post",
            data: form_data,
            success: function( response ) {
                if( $('.group_title').length > 0) {
                    $('tbody').load(location.href + ' tbody tr', function() {
                        $.getScript('/../js/project.js');
                    });
                } else {
                    $('tr#project_'+project_id).load(location.href + ' tr#project_'+project_id +' td', function() {
                        $.getScript('/../js/project.js');
                    });
                }
                
                
                mouse_is_inside = false;
            }, 
            error: function(jqXhr, json, errorThrown) {
                $(".btn-submit").prop("disabled", false);
                var data_to_send = { 'exception':  jqXhr.responseJSON.exception,
                                    'message':  jqXhr.responseJSON.message,
                                    'file':  jqXhr.responseJSON.file,
                                    'line':  jqXhr.responseJSON.line };

                console.log(data_to_send); 
           
                if(url.includes("users") && errorThrown == 'Unprocessable Entity' ) {
                    alert(email_unique);
                }  else {
                    $('<div><div class="modal-header"><span class="img-error"></span></div><div class="modal-body"><div class="alert alert-danger alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button><strong>danger:</strong>' + "Podaci nisu spremljeni, došlo je do greške: " + data_to_send.message + '</div></div></div>').appendTo('body').modal();
                    /* 
                    $.ajax({
                        url: 'errorMessage',
                        type: "get",
                        data: data_to_send,
                        success: function( response ) {
                            console.log("response" + response);
                        }, 
                        error: function(jqXhr, json, errorThrown) {
                            console.log(jqXhr.responseJSON); 
                          
                        }
                    }); */
                }
            }
        });
    }
}