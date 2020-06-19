
    var parent_element;
    var input_element;
    var element;
    var value;
   
    var url = $('form.form_project').attr('action');
    var form = $('form.form_project');
    var form_data;
    var project_id;
    var mouse_is_inside = false;

    $('td.edit_name, td.edit_start_date, td.edit_end_date, td.edit_duration').click(function(){
        if(mouse_is_inside == false) {
            $('input').remove();
            $('td span').show();
            parent_element = $(this);
            element = parent_element.find('span.value');
            project_id = parent_element.parent().attr('id');
            project_id = project_id.replace('project_','');   // 37
        }
    });
    $('body').keypress(function (e) {
        if (e.which == 13) {
            return false;  
        }
    });
    $(document).click(function() {
        if(parent_element) {
            if (! parent_element.is(event.target) && ! parent_element.has(event.target).length ) {
                if(mouse_is_inside == true) {
                    form_data = form.serialize(); 
                    parent_element.find('input').remove();
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
                                $('tr#project_'+project_id).load(location.href + ' tr#project_'+project_id +' td', function() {
                                    $.getScript('/../js/project.js');
                                });
                                mouse_is_inside = false;
                            }, 
                            error: function(jqXhr, json, errorThrown) {
                                alert("Dizajn nije spremljen, došlo je do greške!");
                                $(".btn-submit").prop("disabled", false);
                                var data_to_send = { 'exception':  jqXhr.responseJSON.exception,
                                                    'message':  jqXhr.responseJSON.message,
                                                    'file':  jqXhr.responseJSON.file,
                                                    'line':  jqXhr.responseJSON.line };
                
                                console.log(data_to_send); 
                           
                                if(url.includes("users") && errorThrown == 'Unprocessable Entity' ) {
                                    alert(email_unique);
                                }  else {
                                    $('<div><div class="modal-header"><span class="img-error"></span></div><div class="modal-body"><div class="alert alert-danger alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button><strong>danger:</strong>' + error + '</div></div></div>').appendTo('body').modal();
                                    
                                    $.ajax({
                                        url: 'errorMessage',
                                        type: "get",
                                        data: data_to_send,
                                        success: function( response ) {
                                            console.log(response);
                                        }, 
                                        error: function(jqXhr, json, errorThrown) {
                                            console.log(jqXhr.responseJSON); 
                                          
                                        }
                                    });
                                }
                            }
                        });
                    }
                }
            } else {
                value = element.text();
                name = element.attr('title');
                if(name == 'name' || name == 'duration') {
                    type = 'text';
                } else {
                    type = 'date';
                }
                if(name == 'duration') {
                    pattern="\d*";
                    title="Dozvoljen unos samo cijelog broja"
                } else {
                    pattern='';
                    title='';
                }
                console.log(value);
                console.log(name);
                element.hide();
                if(parent_element.find('input').length == 0) {
                    parent_element.prepend('<input name="'+name+'" type="'+type+'" value="' + value + '" pattern="'+ pattern +'" title="' + title +'" required autofocus /><input name="id" type="hidden" value="' + project_id + '"/>');
                    input_element = parent_element.find('input');
                }
                mouse_is_inside = true;
            }
        }
       
    });

    $('.table_projects .inactive').hide();

    $('.show_inactive').click(function(){
        $('.table_projects .inactive').toggle();
        $('.table_projects .active').toggle();
        if($(this).text() == 'Prikaži neaktivne') {
            $(this).text('Prikaži aktivne');
        } else {
            $(this).text('Prikaži neaktivne');
        }
    });