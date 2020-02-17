var locale = $('.locale').text();

if(locale == 'hr') {
    validate_text = "Obavezno polje";
} else if( locale = 'en') {
    validate_text = "Required field";            
} else {
    validate_text = "Obavezno polje";
}   

var page = $('.admin_pages li').find('a.active_admin');
var modul_name = $('.admin_pages li').find('a.active_admin').attr('id');

$('.btn-submit').click(function(event){
    event.preventDefault();
    var form = $(this).parents('form:first');
    let url = $(this).parents('form:first').attr('action');
    var form_data = form.serialize();
   console.log(form_data);
    
    var validate = false;
    
    $( "textarea" ).each(function( index ) {
        if($(this).attr('required') == 'required' ) {
            if( $(this).val().length == 0 ) {
                if( !$( this ).parent().find('.modal_form_group_danger').length) {
                    $( this ).parent().append('<p class="modal_form_group_danger">' + validate_text + '</p>');
                }
                validate = false;
            } else {
                $( this ).parent().find('.modal_form_group_danger').remove();
                validate = true;
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
                validate = false;
            } else {
                $( this ).parent().find('.modal_form_group_danger').remove();
                validate = true;
            }
        }      
    });

    $( "select" ).each(function( index ) {
        if($(this).attr('required') == 'required' ) {
            if( $(this).val() == null || $(this).val() == '' || $(this).val() == '') {
                if( ! $( this ).parent().find('.modal_form_group_danger').length) {
                    $( this ).parent().append('<p class="modal_form_group_danger">' + validate_text + '</p>');
                }
                validate = false;
            } else {
                $( this ).parent().find('.modal_form_group_danger').remove();
                validate = true;
            }
        }
    });
    
    if (tinyMCE.activeEditor) {
        if(tinyMCE.activeEditor.getContent().length == 0) {
            if( ! $('#mytextarea').parent().find('.modal_form_group_danger').length) {
                $('#mytextarea').parent().append('<p class="modal_form_group_danger">' + validate_text + '</p>');
            }
            validate = false;
        } else {
            $('#mytextarea').parent().find('.modal_form_group_danger').remove();
            validate = true;
        }
    }
    console.log(validate);
    if(validate == false) {
       // event.preventDefault();
    } else {
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
            }
          });
          
        if($(page).length > 0) {
            $(page).click();
        } else {
           $('.btn-submit').unbind();
        }
    }
});
