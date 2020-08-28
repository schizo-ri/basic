var str_roles = $('.roles').text();
var roles = str_roles.split(",");
var _token = $('meta[name="csrf-token"]').attr('content');



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

$('a.btn-cancel').click(function(event ){
    event.preventDefault();
    var preparation_id = $(this).parent().parent().attr('id');
    preparation_id = preparation_id.replace('form_','');
console.log(preparation_id);
    $('.row_preparation_text#id_'+preparation_id).show();
    $(this).parent().parent().hide();
    $(this).parent().siblings().not('.input_preparation.option_input').remove();
    /* $(this).parent().parent().remove(); */
}); 


btnEdit();
css();

function css () {
    $('.status_NE').css('background','rgba(256,0,0,0.3)');
    $('.status_DA').css('background','rgba(0,256,0,0.3)');
    $('.status_N_A').css('background','rgba(0,0,0,0.2)');
}

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
        console.log(preparation);
        console.log(users);
        $.each(users, function( index, value_users ) {
            /*   console.log(value_users); */
            if(this.first_name != null && this.last_name != null) {
                manager += '<option value="'+ value_users.id +'" >'+ value_users.first_name + ' ' + value_users.last_name +'</option>';
            }
        });
        var i = 0;
        var priprema = preparation.preparation;
        console.log(priprema);
        var priprema_text = '';
        if (priprema != undefined && priprema != null) {
            priprema = jQuery.parseJSON(priprema);
            $.each(priprema, function( index, value1 ) {
                priprema_text += '<h5>'+index+'</h5><input type="hidden" name="preparation_title['+index+']"  ><span class="col-md-4"><label for="a_' + i + '" ><input type="radio" name="preparation['+index+']" id="a_' + i + '" value="DA"/> DA</label></span><span class="col-md-4"><label for="b_' + i + '" ><input type="radio" name="preparation['+index+']" id="b_' + i + '" value="NE" /> NE</label></span><span class="col-md-4"><label for="c_' + i + '" ><input type="radio" name="preparation['+index+']" id="c_' + i + '"  value="N/A" /> N/A</label></span>';
                i++;
            });
        }
        var mehanicka = preparation.mechanical_processing;
        var mehanicka_text = '';
        if (mehanicka != undefined && mehanicka !=  null) {
            mehanicka = jQuery.parseJSON(mehanicka);
            $.each(mehanicka, function( index, value2 ) {
                mehanicka_text += '<h5>' + index + '</h5><input type="hidden" name="mechanical_title['+index+']" ><span class="col-md-4"><label for="a_' + i + '" ><input type="radio" name="mechanical_processing['+index+']" id="a_' + i + '" value="DA"/> DA</label></span><span class="col-md-4"><label for="b_' + i + '"  ><input type="radio" name="mechanical_processing['+ index +']" id="b_' + i + '" value="NE" /> NE</label></span><span class="col-md-4"><label for="c_' + i + '"><input type="radio" name="mechanical_processing['+ index +']" id="c_' + i + '" value="N/A" /> N/A</label></span>';
                i++;
            });
        }
        var oznake = preparation.marks_documentation;
        var oznake_text = '';
        if (oznake != undefined && oznake != null) {
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
        form_submit ();
        
    });
}

function form_submit () {
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
                $('.row_preparation_text#id_'+id).load(location + ' .row_preparation_text#id_'+id +'>span', function(){
                   /*  collapsThisProject(id); */
                    form.hide();
                    form.children().not('.input_preparation.option_input').remove();
                    $( '.row_preparation_text#id_'+id ).show();
                    $.getScript('/../restfulizer.js'); 
                    btnEdit();
                    css();
                });
            }, 
            error: function(jqXhr, json, errorThrown) {
                alert("Promjene nisu spremljene, došlo je do greške!");
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
}
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
    console.log(trazi);
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
    $('.row_preparation_text.active').show();

    //   $('.form_preparation').hide();
});
$('.table_preparations .tbody').width($('.table_preparations .thead').width());


