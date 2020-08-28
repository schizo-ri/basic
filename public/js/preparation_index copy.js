var str_roles = $('.roles').text();
var roles = str_roles.split(",");
var _token = $('meta[name="csrf-token"]').attr('content');

var id = $('.collapsible_project').text();  //project number
id = id.replace(':','_');

collapsProject (id);

function collapsProject (id) {  // id = project number
    /* $('.row_preparation_text.'+id).css('display','flex');
    $('.row_preparation_text.'+id).find('span:not(.not_remove)').remove(); */
    $.each( $('.row_preparation_text.'+id), function( index, value1 ) {
        var preparation_id =  $(this).attr('id');
        var preparation_json = $('.row_preparation_text.'+id+'#'+preparation_id).find('.preparation_json').text();
        var preparation = jQuery.parseJSON(preparation_json);
        /* console.log(preparation); */
        var users = jQuery.parseJSON($('span.users_json').text());
        var designed = '';
        var manager = '';
        var equipmentLists_json;
        var equipmentLists;

        if(users.find(x => x.id === preparation.project_manager) != undefined) {
            manager =  users.find(x => x.id === preparation.project_manager).first_name + ' ' + users.find(x => x.id === preparation.project_manager).last_name;
        }
        if(users.find(x => x.id === preparation.designed_by) != undefined) {
            designed = users.find(x => x.id === preparation.designed_by).first_name + ' ' + users.find(x => x.id === preparation.designed_by).last_name;
        }

        var tr_text = '';
        if ( preparation.active == 1) {
            tr_text += '<span><a class="open_upload_link"><i class="fas fa-upload"></i><span class="preparation_id" hidden>' + preparation.id + '</span></a></span><span class="td text_preparation file_input"></span>';
        }
        tr_text += '<span class="td text_preparation project_no_input">'+ preparation.project_no +'</span>';
        tr_text += '<span class="td text_preparation name_input">'+ preparation.name +'</span>';
        tr_text += '<span class="td text_preparation delivery_input">'+ preparation.delivery + '</span>';
        tr_text += '<span class="td text_preparation manager_input">'+ manager +'</span>';
        tr_text += '<span class="td text_preparation designed_input">'+ designed +'</span>';
        if ( preparation.active == 1) {
            var priprema = preparation.preparation;
            try {
                priprema = jQuery.parseJSON(priprema);
            } catch (error) {
                priprema = null;
            }
            tr_text += '<span class="td text_preparation date_change preparation_input">';
            if ( priprema) {
                $.each(priprema, function( index, value1 ) {
                    if (value1 == 'N/A') {
                        val = 'N_A';
                    } else {
                        val = value1;
                    }
                    tr_text += '<span class="status_' + val + '">' + index + '</span>';
                });
            }
            tr_text += '</span>';
            
            var mehanicka = preparation.mechanical_processing;
            mehanicka = jQuery.parseJSON(mehanicka);
            tr_text += '<span class="td text_preparation date_change mechanical_input">';
            if ( mehanicka) {
                $.each(mehanicka, function( index, value2 ) {
                    if (value2 == 'N/A') {
                        val = 'N_A';
                    } else {
                        val = value2;
                    }
                    tr_text += '<span class="status_' + val +'">' + index + '</span>';
                });
            }       
            tr_text += '</span>';
            var oznake = preparation.marks_documentation;
            oznake = jQuery.parseJSON(oznake);
            tr_text += '<span class="td text_preparation date_change marks_input">';
            if ( oznake) {
                $.each(oznake, function( index, value3 ) {
                    if (value3 == 'N/A') {
                        val = 'N_A';
                    } else {
                        val = value3;
                    }
                    tr_text += '<span class="status_' + val +'">' + index + '</span>';
                });
            }
        }
        tr_text += '</span>';
        // equipment_input
        tr_text += '<span class="td text_preparation equipment_input">';
       $.ajax({
            type: "get",
            url: location.origin + '/equipmentList/'+preparation.id,
            success: function (data) {
                equipmentLists_json = data.equipmentLists;
                var isporuceno = data.delivered;
                var hasmark = data.hasmark;
                equipmentLists = jQuery.parseJSON(equipmentLists_json);
               
                $('.row_preparation_text#'+preparation_id).find('.equipmentLists_json').text(equipmentLists_json);
                if ( preparation.active == 1) {
                    if(equipmentLists[0] != undefined) {
                      
                        if( equipmentLists[0].level1 == 1) {
                            $.each(equipmentLists, function( index, value1 ) {
                                tr_text += '<a href="' + location.origin + '/equipment_lists/' + this.id + '/edit" class="equipment_lists_open" >' +this.product_number + '</a>';
                            });
                        } else {
                            tr_text += '<a href="' + location.origin + '/equipment_lists/' + equipmentLists[0].id + '/edit" class="equipment_lists_open" >Upis opreme</a>' ;
                        }
                        
                        tr_text += '<a href="' + location.origin + '/multiReplaceItem/' + preparation.id + '" class="equipment_lists_open multi_replace" >Zamjena</a>';
                        
                        if (! roles.includes('list_view')) {
                            console.log();
                        if(hasmark == true) {
                            tr_text += '<a class="btn-file-input equipment_lists_mark" href="'+ location.origin + '/export/'+preparation.id +'" ><i class="fas fa-download"></i> Preuzmi oznake</a>'
                            } 
                        }
                    } else {
                        tr_text += "Nema zapisa";
                    }
                }
             
                tr_text += '<span class="delivered_items">Isporučeno: ' + isporuceno + '%</span>';
                tr_text += '</span>';
                $('.row_preparation_text#'+preparation_id).prepend(tr_text);
                $('.row_preparation_text#id_'+preparation.id + ' span.text_preparation.option_input').show();
                $('.equipment_lists_open').click(function(){
                    $.modal.defaults = {
                        closeExisting: false, 
                        escapeClose: true,
                        clickClose: false,
                        closeText: 'Close',
                        closeClass: '', 
                        showClose: true,
                        modalClass: "modal equipment_lists",
                        spinnerHtml: "<div id='loader'><span class='ajax-loader1'></span></div>",
                        showSpinner: true,
                        fadeDuration: null, 
                        fadeDelay: 0.5  
                        };
                });
                $('.open_upload_link').click(function(){
                    $.modal.defaults = {
                        closeExisting: false,    // Close existing modals. Set this to false if you need to stack multiple modal instances.
                        escapeClose: true,      // Allows the user to close the modal by pressing `ESC`
                        clickClose: false,       // Allows the user to close the modal by clicking the overlay
                        closeText: 'Close',     // Text content for the close <a> tag.
                        closeClass: '',         // Add additional class(es) to the close <a> tag.
                        showClose: true,        // Shows a (X) icon/link in the top-right corner
                        modalClass: "modal",    // CSS class added to the element being displayed in the modal.
                        // HTML appended to the default spinner during AJAX requests.
                        spinnerHtml: "<div id='loader'><span class='ajax-loader1'></span></div>",
                    
                        showSpinner: true,      // Enable/disable the default spinner during AJAX requests.
                        fadeDuration: null,     // Number of milliseconds the fade transition takes (null means no transition)
                        fadeDelay: 0.5          // Point during the overlay's fade-in that the modal begins to fade in (.5 = 50%, 1.5 = 150%, etc.)
                        };
                        var preparation_id = $( this ).find('.preparation_id').text();
                
                        $('.upload_links .prep_id').val(preparation_id);
                        $('.upload_links').modal();
                        return false;
                });
                $('.status_NE').css('background','rgba(256,0,0,0.3)');
                $('.status_DA').css('background','rgba(0,256,0,0.3)');
                $('.status_N_A').css('background','rgba(0,0,0,0.2)');
                $('.row_preparation_text#id_'+preparation.id).show();
                $('a.btn-cancel').click(function(event ){
                    event.preventDefault();
                    $('.row_preparation_text#id_'+preparation.id).show();
                    $(this).parent().parent().hide();
                    $(this).parent().siblings().not('.input_preparation.option_input').remove();
                    
                }); 
            },
            error: function(jqXhr, json, errorThrown) {
                alert("Projekt nema dovoljno podataka, došlo je do greške!");
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
    });
  
    $(this).next('.open_upload_link').toggle();
}

function collapsThisProject (id) { // id = project id
    var preparation_id =  id;
    
    $('.row_preparation_text#id_'+preparation_id).css('display','flex');
    var preparation_json = $('.row_preparation_text#id_'+preparation_id).find('.preparation_json').text();

    var preparation = jQuery.parseJSON(preparation_json);
   
    var users = jQuery.parseJSON($('span.users_json').text());
    var designed = '';
    var manager = '';
    var equipmentLists_json;
    var equipmentLists;
    if(users.find(x => x.id === preparation.project_manager) != undefined) {
        manager =  users.find(x => x.id === preparation.project_manager).first_name + ' ' + users.find(x => x.id === preparation.project_manager).last_name;
    }
    if(users.find(x => x.id === preparation.designed_by) != undefined) {
        designed = users.find(x => x.id === preparation.designed_by).first_name + ' ' + users.find(x => x.id === preparation.designed_by).last_name;
    }
    var tr_text = '';
    if ( preparation.active == 1) {
        tr_text += '<span><a class="open_upload_link"><i class="fas fa-upload"></i><span class="preparation_id" hidden>' + preparation.id + '</span></a></span><span class="td text_preparation file_input"></span>';
    }
    tr_text += '<span class="td text_preparation project_no_input">'+ preparation.project_no +'</span>';
    tr_text += '<span class="td text_preparation name_input">'+ preparation.name +'</span>';
    tr_text += '<span class="td text_preparation delivery_input">'+ preparation.delivery + '</span>';
    tr_text += '<span class="td text_preparation manager_input">'+ manager +'</span>';
    tr_text += '<span class="td text_preparation designed_input">'+ designed +'</span>';
    if ( preparation.active == 1) {
        var priprema = preparation.preparation;
        try {
            priprema = jQuery.parseJSON(priprema);
        } catch (error) {
            priprema = null;
        }
        priprema = jQuery.parseJSON(priprema);
        tr_text += '<span class="td text_preparation date_change preparation_input">';
        if ( priprema) {
            $.each(priprema, function( index, value1 ) {
                if (value1 == 'N/A') {
                    val = 'N_A';
                } else {
                    val = value1;
                }
                tr_text += '<span class="status_' + val + '">' + index + '</span>';
            });
        }
        tr_text += '</span>';
        
        var mehanicka = preparation.mechanical_processing;
        mehanicka = jQuery.parseJSON(mehanicka);
        tr_text += '<span class="td text_preparation date_change mechanical_input">';
        if ( mehanicka) {
            $.each(mehanicka, function( index, value2 ) {
                if (value2 == 'N/A') {
                    val = 'N_A';
                } else {
                    val = value2;
                }
                tr_text += '<span class="status_' + val +'">' + index + '</span>';
            });
        }       
        tr_text += '</span>';
        var oznake = preparation.marks_documentation;
        oznake = jQuery.parseJSON(oznake);
        tr_text += '<span class="td text_preparation date_change marks_input">';
        if ( oznake) {
            $.each(oznake, function( index, value3 ) {
                if (value3 == 'N/A') {
                    val = 'N_A';
                } else {
                    val = value3;
                }
                tr_text += '<span class="status_' + val +'">' + index + '</span>';
            });
        }
    }
    tr_text += '</span>';
    // equipment_input
    tr_text += '<span class="td text_preparation equipment_input">';
    $.ajax({
        type: "get",
        url: location.origin + '/equipmentList/'+preparation.id,
        success: function (data) {
            equipmentLists_json = data.equipmentLists;
            var isporuceno = data.delivered;
            equipmentLists = jQuery.parseJSON(equipmentLists_json);
            $('.row_preparation_text#'+preparation_id).find('.equipmentLists_json').text(equipmentLists_json);
            if ( preparation.active == 1) {
                if(equipmentLists[0].level1 == 1) {
                    $.each(equipmentLists, function( index, value1 ) {
                        tr_text += '<a href="' + location.origin + '/equipment_lists/' + this.id + '/edit" class="equipment_lists_open" rel="modal:open" >' +this.product_number + '</a>';
                    });
                } else {
                    tr_text += '<a href="' + location.origin + '/equipment_lists/' + equipmentLists[0].id + '/edit" class="equipment_lists_open" rel="modal:open" >Upis opreme</a>' ;
                }
                
                tr_text += '<a href="' + location.origin + '/multiReplaceItem/' + preparation.id + '" class="equipment_lists_open multi_replace" rel="modal:open">Zamjena</a>';

                if (! roles.includes('list_view')) {
                if(equipmentLists[0].mark != null) {
                    tr_text += '<a class="btn-file-input equipment_lists_mark" href="'+ location.origin + '/export/'+preparation.id +'" ><i class="fas fa-download"></i> Preuzmi oznake</a>'
                    } 
                }
            }
            
            tr_text += '<span class="delivered_items">Isporučeno: ' + isporuceno + '%</span>';
            tr_text += '</span>';
            console.log(preparation_id);
            $('.row_preparation_text#id_'+preparation_id).prepend(tr_text);
            $('.row_preparation_text#id_'+preparation.id + ' span.text_preparation.option_input').show();
            $('.equipment_lists_open').click(function(){
                $.modal.defaults = {
                    closeExisting: false, 
                    escapeClose: true,
                    clickClose: false,
                    closeText: 'Close',
                    closeClass: '', 
                    showClose: true,
                    modalClass: "modal equipment_lists",
                    spinnerHtml: "<div id='loader'><span class='ajax-loader1'></span></div>",
                    showSpinner: true,
                    fadeDuration: null, 
                    fadeDelay: 0.5  
                    };
            });
            $('.open_upload_link').click(function(){
                $.modal.defaults = {
                    closeExisting: false,
                    escapeClose: true,
                    clickClose: false,
                    closeText: 'Close',
                    closeClass: '',
                    showClose: true,
                    modalClass: "modal",
                    spinnerHtml: "<div id='loader'><span class='ajax-loader1'></span></div>",
                    showSpinner: true, 
                    fadeDuration: null, 
                    fadeDelay: 0.5
                    };
            });
            $('.status_NE').css('background','rgba(256,0,0,0.3)');
            $('.status_DA').css('background','rgba(0,256,0,0.3)');
            $('.status_N_A').css('background','rgba(0,0,0,0.2)');
            $('.open_upload_link').click(function(){
                var preparation_id = $( this ).find('.preparation_id').text();
            
                $('.upload_links .prep_id').val(preparation_id);
                $('.upload_links').modal();
                return false;
            });

            $('a.btn-cancel').click(function(event ){
                event.preventDefault();
                $('.row_preparation_text#id_'+preparation.id).show();
                $(this).parent().parent().hide();
                $(this).parent().siblings().not('.input_preparation.option_input').remove();
                /* $(this).parent().parent().remove(); */
            }); 
        },
        error: function(jqXhr, json, errorThrown) {
            alert("Projekt nema dovoljno podataka, došlo je do greške!");
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

    $(this).next('.open_upload_link').toggle();
}

$('.edit_preparation').submit(function(e){
    e.preventDefault();
    var form = $(this);
    var url = $(this).attr('action');
    var form_data = $(this).serialize(); 
    var id = form.attr('id');
    id = id.replace('form_','');
    var project_no = $(this).find('input[name=project_no]').val();
    project_no = project_no.replace(":","_");
    
    $.ajax({
        url: url,
        type: "post",
        data: form_data,
        success: function( response ) {
            $('.row_preparation_text.' + project_no + '#id_'+id).load(location + ' .row_preparation_text.' + project_no + '#id_'+id +'>span', function(){
                collapsThisProject(id);
                form.hide();
                form.children().not('.input_preparation.option_input').remove();
                $.getScript('/../restfulizer.js'); 
                btnEdit();
            });
        }, 
        error: function(jqXhr, json, errorThrown) {
            alert("Problem kod spramanja podataka!");
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
});

btnEdit();

function btnEdit () {
    $('a.btn-edit').click(function( event ){
        event.preventDefault();
        var id = $(this).attr('id');
        id = id.replace('edit_','');
        
        var preparation_json = $(this).parent().siblings('.preparation_json').text();
        var preparation = '';
        if (preparation_json != '') {
            preparation = jQuery.parseJSON(preparation_json)
        };
        var users = jQuery.parseJSON($('span.users_json').text());
        var manager = '';
        
        $.each(users, function( index, value_users ) {
            /*   console.log(value_users); */
            if(this.first_name != null && this.last_name != null) {
                manager += '<option value="'+ value_users.id +'" >'+ value_users.first_name + ' ' + value_users.last_name +'</option>';
            }
        });
        var i = 0;
        var priprema = preparation.preparation;
        var priprema_text = '';
        if (priprema != undefined) {
            priprema = jQuery.parseJSON(priprema);
            $.each(priprema, function( index, value1 ) {
                priprema_text += '<h5>'+index+'</h5><input type="hidden" name="preparation_title['+index+']"  ><span class="col-md-4"><label for="a_' + i + '" ><input type="radio" name="preparation['+index+']" id="a_' + i + '" value="DA"/> DA</label></span><span class="col-md-4"><label for="b_' + i + '" ><input type="radio" name="preparation['+index+']" id="b_' + i + '" value="NE" /> NE</label></span><span class="col-md-4"><label for="c_' + i + '" ><input type="radio" name="preparation['+index+']" id="c_' + i + '"  value="N/A" /> N/A</label></span>';
                i++;
            });
        }
        var mehanicka = preparation.mechanical_processing;
        var mehanicka_text = '';
        if (mehanicka != undefined) {
            mehanicka = jQuery.parseJSON(mehanicka);
            $.each(mehanicka, function( index, value2 ) {
                mehanicka_text += '<h5>' + index + '</h5><input type="hidden" name="mechanical_title['+index+']" ><span class="col-md-4"><label for="a_' + i + '" ><input type="radio" name="mechanical_processing['+index+']" id="a_' + i + '" value="DA"/> DA</label></span><span class="col-md-4"><label for="b_' + i + '"  ><input type="radio" name="mechanical_processing['+ index +']" id="b_' + i + '" value="NE" /> NE</label></span><span class="col-md-4"><label for="c_' + i + '"><input type="radio" name="mechanical_processing['+ index +']" id="c_' + i + '" value="N/A" /> N/A</label></span>';
                i++;
            });
        }
        var oznake = preparation.marks_documentation;
        var oznake_text = '';
        if (oznake != undefined) {
            oznake = jQuery.parseJSON(oznake);
            $.each(oznake, function( index, value3 ) {
                oznake_text += '<h5>' + index + '</h5><input type="hidden" name="marks_title['+ index +']" ><span class="col-md-4"><label for="a_' + i + '" ><input type="radio" name="marks_documentation['+ index +']" id="a_' + i + '" value="DA"/> DA</label></span><span class="col-md-4"><label for="b_' + i + '" ><input type="radio" name="marks_documentation['+ index +']" id="b_' + i + '" value="NE" /> NE</label></span><span class="col-md-4"><label for="c_' + i + '" ><input type="radio" name="marks_documentation['+ index +']" value="N/A" id="c_' + i + '"  /> N/A</label></span>';
                i++;
            });
        }
        if($( '.form_preparation.edit_preparation.'+id ).find('.file_input').length == 0) {
            $( '.form_preparation.edit_preparation.'+id ).prepend( '<span class="input_preparation file_input"></span> <span class="input_preparation project_no_input"> <input name="project_no" type="text" value="'+ preparation.project_no +'" maxlength="30" required /> </span> <span class="input_preparation name_input"> <input name="name" type="text" value="'+ preparation.name +'" maxlength="100"/> </span> <span class="input_preparation delivery_input"> <input name="delivery" type="date" value="'+ preparation.delivery +'" /> </span><span class="input_preparation manager_input"><select name="project_manager" class="project_manager" required ><option disabled  >Voditelj projekta</option>' + manager  + '</select></span><span class="input_preparation designed_input"> <select name="designed_by" class="designed_by" required> <option disabled >Projektant</option>' + manager  + '</select> </span> <span class="input_preparation preparation_input">'+ priprema_text +'</span> <span class="input_preparation mechanical_input">'+ mehanicka_text +'</span> <span class="input_preparation marks_input">'+ oznake_text +'</span> <input name="_token" value="'+ _token+'" type="hidden"> <input name="_method" value="PUT" type="hidden"> <span class="input_preparation equipment_input"></span>' );
        }
        
        $( '.form_preparation.edit_preparation.'+id ).css('display','flex');
        $( '.row_preparation_text#id_'+id ).hide();
        /* $('.row_preparation_text#id_'+id).find('span:not(.not_remove)').remove(); */
    
        if( preparation.project_manager ) {
            $.each($('select[name=project_manager] option'), function( index, value_manager ) {
                if($(this).val() == preparation.project_manager) {
                    $(this).attr("selected","true");
                }
            })
        }
        if( preparation.designed_by ) {
            $.each($('select[name=designed_by] option'), function( index, value_desiner ) {
                if($(this).val() == preparation.designed_by) {
                    $(this).attr("selected","true");
                }
            })
        }
        $.each(priprema, function( index, value_priprema ) {
            var naziv = index;
            var vrijednost = value_priprema;
            $.each($('span.input_preparation.preparation_input input'), function( index, value ) {
                var element = $(this);
                if( element.attr('name').includes(naziv) && element.val() == vrijednost) {
                    $(this).attr('checked','checked');
                } 
            });
        }); 
        $.each(mehanicka, function( index, value_mehanicka ) {
            var naziv = index;
            var vrijednost = value_mehanicka;
            $.each($('span.input_preparation.mechanical_input input'), function( index, value_span ) {
                var element = $(this);
                if( element.attr('name').includes(naziv) && element.val() == vrijednost) {
                    $(this).attr('checked','checked');
                } 
            });
        }); 
        $.each(oznake, function( index, value_oznake ) {
            var naziv = index;
            var vrijednost = value_oznake;
            $.each($('span.input_preparation.marks_input input'), function( index, value_span2 ) {
                var element = $(this);
                if( element.attr('name').includes(naziv) && element.val() == vrijednost) {
                    $(this).attr('checked','checked');
                } 
            });
        }); 
        
    });
}

$('.show_inactive').click(function(){
    $('.show_active').toggle();
    $('.show_inactive').toggle();
});
$('.show_active').click(function(){
    $('.show_active').toggle();
    $('.show_inactive').toggle();
});
$('.upload_file input[type=file]').change(function(){
    $(this).parent().parent().submit();
});
$('.upload_file_replace input[type=file]').change(function(){
    $(this).parent().parent().submit();
});

$('a.btn-cancel2').click(function(event ){
    event.preventDefault();
    $(this).parent().parent().prev('p').show();
    $(this).parent().parent().hide();
});

$('#mySearch_preparation').keyup(function() {
    var trazi = $( this ).val().toLowerCase();
    $('.collapsible_project').filter(function() {
        $(this).toggle($(this).text().toLowerCase().indexOf(trazi) > -1)
    });
    $('.row_preparation_text').filter(function() {
        $(this).toggle($(this).text().toLowerCase().indexOf(trazi) > -1)
    });
    $('.form_preparation:visible').filter(function() {
        $(this).toggle($(this).text().toLowerCase().indexOf(trazi) > -1)
    });
});	
$('.clearable__clear').click(function(){
    $('#mySearch_preparation').val('');
    $('.collapsible_project').show();

    //   $('.form_preparation').hide();
});
$('.table_preparations .tbody').width($('.table_preparations .thead').width());